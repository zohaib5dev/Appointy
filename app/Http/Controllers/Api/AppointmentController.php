<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Vendor; 
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent; 

class AppointmentController extends Controller
{
    protected $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    public function getAvailableSlots(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'staff_id' => 'nullable|exists:staff,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $service = Service::findOrFail($request->service_id);
        $date = Carbon::parse($request->date);

        $slots = $this->availabilityService->getAvailableSlots(
            $request->vendor_id,
            $service,
            $date,
            $request->staff_id
        );

        return response()->json(['slots' => $slots]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'service_id' => 'required|exists:services,id',
            'start_time' => 'required|date',
            'staff_id' => 'nullable|exists:staff,id',
            'customer_notes' => 'nullable|string',
            'payment_type' => 'required|in:now,later',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $service = Service::findOrFail($request->service_id);
        $startTime = Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addMinutes($service->duration);

        // Check availability
        $isAvailable = $this->availabilityService->checkAvailability(
            $request->vendor_id,
            $service,
            $startTime,
            $endTime,
            $request->staff_id
        );

        if (!$isAvailable) {
            return response()->json(['message' => 'Time slot is not available'], 422);
        }

        // Get vendor with stripe account
        $vendor = Vendor::with('user')->findOrFail($request->vendor_id);

        if (!$vendor->stripe_account_id) {
            return response()->json(['message' => 'Vendor has not set up payment account'], 422);
        }

        DB::beginTransaction();

        try {
            $user = auth()->user();


            // Create appointment
            $appointment = Appointment::create([
                'customer_id' => $user->id,
                'vendor_id' => $request->vendor_id,
                'service_id' => $request->service_id,
                'staff_id' => $request->staff_id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'customer_notes' => $request->customer_notes,
                'price' => $service->price,
                'payment_type' => $request->payment_type,
                'payment_status' => 'pending',
                'status' => 'pending'
            ]);

            // Create invoice
            $invoice = $this->createInvoice($appointment, $service);

            if ($request->payment_type === 'now') {

                Stripe::setApiKey(config('services.stripe.secret'));

                $stripeCustomer = $this->getOrCreateStripeCustomer($user);

                $amount = (int) ($service->price * 100);
                $applicationFee = (int) ($service->price * 0.10 * 100);

                $paymentIntent = PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => 'usd',
                    'customer' => $stripeCustomer->id,
                    'confirm' => true,
                    'payment_method' => $request->paymentMethodId,
                    'off_session' => false,

                    'automatic_payment_methods' => [
                        'enabled' => true,
                        'allow_redirects' => 'never',
                    ],

                    'application_fee_amount' => $applicationFee,
                    'transfer_data' => [
                        'destination' => $vendor->stripe_account_id,
                    ],

                    'metadata' => [
                        'appointment_id' => $appointment->id,
                        'vendor_id' => $vendor->id,
                        'customer_id' => $user->id,
                    ],
                ]);


                if ($paymentIntent->status !== 'succeeded') {
                    DB::rollBack();

                    return response()->json([
                        'message' => 'Payment failed or requires authentication',
                        'status' => $paymentIntent->status,
                    ], 422);
                }

                $appointment->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed'
                ]);

                DB::commit();

                return response()->json([
                    'message' => 'Appointment booked and payment completed successfully',
                    'appointment' => $appointment->load(['service', 'staff', 'vendor']),
                    'invoice' => $invoice,
                ], 201);
            }

            DB::commit();

            return response()->json([
                'message' => 'Appointment booked successfully. You can pay later.',
                'appointment' => $appointment->load(['service', 'staff', 'vendor']),
                'invoice' => $invoice,
            ], 201);
        } catch (\Stripe\Exception\CardException $e) {
            DB::rollBack();
            \Log::error('Stripe card error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Payment failed: ' . $e->getError()->message,
                'error_type' => 'card_error'
            ], 422);
        } catch (\Stripe\Exception\RateLimitException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Too many requests. Please try again later.'], 429);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Invalid payment parameters: ' . $e->getMessage()], 400);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Payment authentication failed. Please contact support.'], 500);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Network error. Please try again.'], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Appointment creation failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to book appointment: ' . $e->getMessage()], 500);
        }
    }

    protected function getOrCreateStripeCustomer($user)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        // Check if user already has a stripe customer ID
        if ($user->stripe_customer_id) {
            try {
                return Customer::retrieve($user->stripe_customer_id);
            } catch (\Exception $e) {
               
            $customer = Customer::create([
                    'email' => $user->email,
                    'name' => $user->name,
                    'metadata' => [
                        'user_id' => $user->id,
                    ],
                ]);

                $user->update(['stripe_customer_id' => $customer->id]);
                return $customer;
            }
        }

        // Create new customer
        $customer = Customer::create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        $user->update(['stripe_customer_id' => $customer->id]);
        return $customer;
    }

    protected function createInvoice($appointment, $service)
    {
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);

        $items = [
            [
                'service_id' => $service->id,
                'service_name' => $service->name,
                'description' => $service->description,
                'quantity' => 1,
                'price' => $service->price,
                'duration' => $service->duration,
            ]
        ];

        $subtotal = $service->price;
        $tax = $subtotal * 0.10; // 10% tax
        $discount = 0;
        $total = $subtotal + $tax - $discount;

        return Invoice::create([
            'invoice_number' => $invoiceNumber,
            'appointment_id' => $appointment->id,
            'customer_id' => auth()->id(),
            'vendor_id' => $appointment->vendor_id,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
            'currency' => 'USD',
            'items' => json_encode($items),
            'pdf_path' => null,
        ]);
    }
 

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Appointment::with(['service', 'vendor', 'staff', 'customer']);

        $query->where('customer_id', $user->id);

        if ($request->filled('status')) {
            if ($request->status === 'upcoming') {
                $query->whereIn('status', ['pending', 'confirmed', 'in_progress'])
                    ->where('start_time', '>=', now());
            } elseif ($request->status === 'past') {
                $query->where('status', 'completed')
                    ->where('start_time', '<', now());
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('date_from')) {
            $query->where('start_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('start_time', '<=', $request->date_to);
        }

        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        $appointments = $query->orderBy('start_time', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json($appointments);
    }

    public function show($id)
    {
        $user = auth()->user();
        $appointment = Appointment::with(['service', 'vendor', 'staff', 'customer', 'payment'])
            ->findOrFail($id);

        // Check authorization
        if (
            !$user->isAdmin() &&
            $appointment->customer_id !== $user->id &&
            ($user->isVendor() && $appointment->vendor_id !== $user->vendor->id)
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($appointment);
    }

    public function cancel($id)
    {
        $user = auth()->user();
        $appointment = Appointment::findOrFail($id);

        // Check authorization
        if (
            !$user->isAdmin() &&
            $appointment->customer_id !== $user->id &&
            ($user->isVendor() && $appointment->vendor_id !== $user->vendor->id)
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return response()->json(['message' => 'Cannot cancel this appointment'], 422);
        }

        // If payment was made, handle refund
        if ($appointment->payment_status === 'paid' && $appointment->payment_intent_id) {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $refund = \Stripe\Refund::create([
                    'payment_intent' => $appointment->payment_intent_id,
                ]);

                $appointment->update([
                    'status' => 'cancelled',
                    'payment_status' => 'refunded',
                ]);

                return response()->json([
                    'message' => 'Appointment cancelled and payment refunded',
                    'appointment' => $appointment
                ]);
            } catch (\Exception $e) {
                \Log::error('Refund failed: ' . $e->getMessage());
                return response()->json(['message' => 'Failed to process refund'], 500);
            }
        }

        $appointment->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Appointment cancelled successfully',
            'appointment' => $appointment
        ]);
    }

    public function reschedule(Request $request, $id)
    {
        $user = auth()->user();
        $appointment = Appointment::findOrFail($id);

        if ($appointment->customer_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $newStartTime = Carbon::parse($request->start_time);
        $newEndTime = $newStartTime->copy()->addMinutes($appointment->service->duration);

        $isAvailable = $this->availabilityService->checkAvailability(
            $appointment->vendor_id,
            $appointment->service,
            $newStartTime,
            $newEndTime,
            $appointment->staff_id
        );

        if (!$isAvailable) {
            return response()->json(['message' => 'New time slot is not available'], 422);
        }

        $appointment->update([
            'start_time' => $newStartTime,
            'end_time' => $newEndTime,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Appointment rescheduled successfully',
            'appointment' => $appointment
        ]);
    }

   
}
