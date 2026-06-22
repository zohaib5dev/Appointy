<?php
// app/Http/Controllers/Api/Vendor/SubscriptionController.php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Vendor;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\SubscriptionInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Subscription as StripeSubscription;

class SubscriptionController extends BaseApiController
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

 
    public function currentSubscription(Request $request)
    {
        $vendor = $request->user()->vendor;

         $plan = null;
        $planPrice = null;

        if ($vendor->plan_id && $vendor->subscription_status === 'active') {
            $plan = Plan::find($vendor->plan_id);
            $planPrice = PlanPrice::where('id', $vendor->price_id)->first();
        }

        if (!$vendor->plan_id || $vendor->subscription_status !== 'active') {
            return response()->json([
                'has_active_subscription' => false,
                'current_subscription' => null,
                'message' => 'No active subscription found'
            ]);
        }

        // Calculate days remaining
        $daysRemaining = 0;
        if ($vendor->subscription_ends_at) {
            $daysRemaining = Carbon::now()->diffInDays($vendor->subscription_ends_at, false);
            if ($daysRemaining < 0) $daysRemaining = 0;
        }

        // Get latest invoice
        $latestInvoice = SubscriptionInvoice::where('vendor_id', $vendor->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'has_active_subscription' => true,
            'current_subscription' => [
                'plan_name' => $plan ? $plan->name : null,
                'interval' => $planPrice ? $planPrice->interval : null,
                'price' => $planPrice ? $planPrice->price : 0,
                'price_id' => $vendor->price_id,
                'start_date' => $vendor->subscription_starts_at,
                'end_date' => $vendor->subscription_ends_at,
                'days_remaining' => $daysRemaining,
                'status' => $vendor->subscription_status,
                'latest_invoice' => $latestInvoice,
                'usage' => $this->getUsageMetrics($vendor, $plan)
            ]
        ]);
    }

   
    public function availablePlans()
    {
        $plans = Plan::with(['prices' => function ($query) {
            $query->orderBy('price', 'asc');
        }])
            ->where('is_active', true)
            ->get();

        return response()->json([
            'stripe_key' => config('services.stripe.key'),
            'plans' => $plans,
            'stripe_account_status' => Auth::user()->vendor->stripe_account_status
        ]);
    }
 

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'price_id' => 'required|exists:plan_prices,id',
            'payment_method_id' => 'required|string',
        ]);

        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return response()->json([
                'message' => 'Vendor account not found.'
            ], 404);
        }

        // Check if vendor already has active subscription
        if ($vendor->subscription_status === 'active' && $vendor->subscription_ends_at > Carbon::now()) {
            return response()->json([
                'message' => 'You already have an active subscription.'
            ], 400);
        }

        $plan = Plan::findOrFail($validated['plan_id']);
        $planPrice = PlanPrice::findOrFail($validated['price_id']);

        DB::beginTransaction();

        try {
            // Create/Get Stripe Customer
            $stripeCustomer = $this->getOrCreateStripeCustomer($vendor);

            // Attach payment method
            $paymentMethod = \Stripe\PaymentMethod::retrieve($validated['payment_method_id']);
            $paymentMethod->attach(['customer' => $stripeCustomer->id]);

            // Set default payment method
            \Stripe\Customer::update($stripeCustomer->id, [
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethod->id,
                ],
            ]);

            // Create subscription
            $stripeSubscription = StripeSubscription::create([
                'customer' => $stripeCustomer->id,
                'items' => [
                    [
                        'price' => $planPrice->price_id,
                    ],
                ],
                'default_payment_method' => $paymentMethod->id,
                'expand' => ['latest_invoice', 'latest_invoice.payment_intent'],
            ]);


            $subscriptionItem = $stripeSubscription->items->data[0] ?? null;

            if (!$subscriptionItem) {
                throw new \Exception('Subscription period information not found.');
            }

            $startDate = Carbon::createFromTimestamp($subscriptionItem->current_period_start);
            $endDate = Carbon::createFromTimestamp($subscriptionItem->current_period_end);

            Vendor::where('id', $vendor->id)->update([
                'plan_id' => $plan->id,
                'price_id' => $planPrice->id,
                'stripe_subscription_id' => $stripeSubscription->id,
                'stripe_customer_id' => $stripeCustomer->id,
                'subscription_starts_at' => $startDate,
                'subscription_ends_at' => $endDate,
                'subscription_status' => 'active',
            ]);

            $vendor = Vendor::where('id', $vendor->id)->first();


            $invoice = $this->createSubscriptionInvoice(
                $vendor,
                $plan,
                $planPrice,
                $startDate,
                $endDate,
                $stripeSubscription->latest_invoice ?? null
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed to ' . $plan->name,
                'subscription' => [
                    'plan_name' => $plan->name,
                    'interval' => $planPrice->interval,
                    'price' => $planPrice->price,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'active'
                ],
                'invoice' => $invoice,
            ]);
        } catch (\Stripe\Exception\CardException $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getError()->message,
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subscription Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function cancelSubscription(Request $request)
    {
        $vendor = $request->user()->vendor;

        if ($vendor->subscription_status !== 'active') {
            return response()->json([
                'message' => 'No active subscription found'
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Cancel at period end in Stripe
            if ($vendor->stripe_subscription_id) {
                $stripeSubscription = StripeSubscription::retrieve($vendor->stripe_subscription_id);
                $stripeSubscription->cancel_at_period_end = true;
                $stripeSubscription->save();
            }

            $vendor->subscription_status = 'cancelled';
            $vendor->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Subscription cancelled successfully. You will have access until ' . Carbon::parse($vendor->subscription_ends_at)->format('Y-m-d'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to cancel subscription: ' . $e->getMessage()
            ], 500);
        }
    }

   
    public function invoiceHistory(Request $request)
    {
        $vendor = $request->user()->vendor;

        $invoices = SubscriptionInvoice::where('vendor_id', $vendor->id)
            ->with(['plan', 'planPrice'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($invoices);
    }

   
    public function showInvoice(Request $request, $invoiceId)
    {
        $vendor = $request->user()->vendor;

        $invoice = SubscriptionInvoice::where('id', $invoiceId)
            ->where('vendor_id', $vendor->id)
            ->with(['plan', 'planPrice'])
            ->firstOrFail();

        return response()->json($invoice);
    }

   
    public function downloadInvoice(Request $request, $invoiceId)
    {
        $vendor = $request->user()->vendor;

        $invoice = SubscriptionInvoice::where('id', $invoiceId)
            ->where('vendor_id', $vendor->id)
            ->firstOrFail();

        // Generate PDF here
        return response()->json([
            'invoice' => $invoice,
            'download_url' => null
        ]);
    }


    private function getOrCreateStripeCustomer($vendor)
    {
        if ($vendor->stripe_customer_id) {
            return Customer::retrieve($vendor->stripe_customer_id);
        }

        $customer = Customer::create([
            'email' => $vendor->email,
            'name' => $vendor->business_name,
            'phone' => $vendor->phone,
            'metadata' => [
                'vendor_id' => $vendor->id,
                'vendor_name' => $vendor->business_name
            ]
        ]);

        $vendor->update(['stripe_customer_id' => $customer->id]);

        return $customer;
    }


    private function createSubscriptionInvoice($vendor, $plan, $planPrice, $startDate, $endDate, $stripeInvoice = null)
    {
        $invoiceNumber = 'SUB-INV-' . strtoupper(uniqid());

        $items = [
            [
                'description' => $plan->name . ' Plan - ' . ucfirst($planPrice->interval) . ' subscription',
                'interval' => $planPrice->interval,
                'price_id' => $planPrice->price_id,
                'quantity' => 1,
                'unit_price' => $planPrice->price,
                'total' => $planPrice->price
            ]
        ];

        $invoiceData = [
            'invoice_number' => $invoiceNumber,
            'vendor_id' => $vendor->id,
            'plan_id' => $plan->id,
            'plan_price_id' => $planPrice->id,
            'amount' => $planPrice->price,
            'tax' => 0,
            'total' => $planPrice->price,
            'currency' => 'USD',
            'status' => 'Paid',
            'items' => $items,
            'billing_period_start' => $startDate,
            'billing_period_end' => $endDate,
            'paid_at' => now()
        ];

        if ($stripeInvoice) {
            $invoiceData['stripe_invoice_id'] = $stripeInvoice->id;
            $invoiceData['stripe_payment_intent_id'] = $stripeInvoice->payment_intent->id ?? null;
        }

        return SubscriptionInvoice::create($invoiceData);
    }

   
    private function getUsageMetrics($vendor, $plan)
    {
        if (!$plan) return null;

        $currentMonth = Carbon::now()->startOfMonth();

        return [
            'services' => [
                'used' => $vendor->services()->count(),
                'limit' => $plan->services_limit,
                'percentage' => $this->calculatePercentage(
                    $vendor->services()->count(),
                    $plan->services_limit
                )
            ],
            'staff' => [
                'used' => $vendor->staff()->count(),
                'limit' => $plan->staff_limit,
                'percentage' => $this->calculatePercentage(
                    $vendor->staff()->count(),
                    $plan->staff_limit
                )
            ],
            'appointments' => [
                'used' => $vendor->appointments()
                    ->where('start_time', '>=', $currentMonth)
                    ->count(),
                'limit' => $plan->appointments_limit,
                'percentage' => $this->calculatePercentage(
                    $vendor->appointments()->where('start_time', '>=', $currentMonth)->count(),
                    $plan->appointments_limit
                )
            ]
        ];
    }

    
    private function calculatePercentage($used, $limit)
    {
        if ($limit === -1) return 0;
        if ($used >= $limit) return 100;
        return round(($used / $limit) * 100);
    }

   

    public function stripeOnboarding()
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $user = Auth::user();
        $vendor = $user->vendor;

        $account = $stripe->v2->core->accounts->create([
            'contact_email' => $user->email,
            'display_name' => $vendor->business_name,

            'identity' => [
                'country' => 'US',
                'entity_type' => 'company',
                'business_details' => [
                    'registered_name' => $vendor->business_name,
                ],
            ],

            'configuration' => [
                'merchant' => [
                    'capabilities' => [
                        'card_payments' => [
                            'requested' => true,
                        ],
                    ],
                ],
            ],

            'defaults' => [
                'responsibilities' => [
                    'fees_collector' => 'stripe',
                    'losses_collector' => 'stripe',
                ],
            ],

            'dashboard' => 'full',
        ]);
 
        $accountLink = $stripe->accountLinks->create([
            'account' => $account->id,
            'refresh_url' => route('stripe.refresh'),
            'return_url' => route('stripe.success', [
                'vendor_id' => $vendor->id
            ]),
            'type' => 'account_onboarding',
        ]);

        $vendor->update([
            'stripe_account_id' => $account->id,
            'stripe_account_status' => 'pending',
        ]);

        return response()->json([
            'account_id' => $account->id,
            'account_link_url' => $accountLink->url
        ]);
        
    }
}
