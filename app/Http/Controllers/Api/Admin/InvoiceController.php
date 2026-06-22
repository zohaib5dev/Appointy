<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionInvoice;
use App\Models\Vendor;
use App\Models\Plan;
use App\Models\PlanPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Get invoice statistics
     */
    public function stats()
    {
        $stats = [
            'total' => SubscriptionInvoice::count(),
            'paid' => SubscriptionInvoice::where('status', 'paid')->count(),
            'pending' => SubscriptionInvoice::where('status', 'pending')->count(),
            'failed' => SubscriptionInvoice::where('status', 'failed')->count(),
            'revenue' => SubscriptionInvoice::where('status', 'paid')->sum('total')
        ];

        return response()->json($stats);
    }

    /**
     * List all invoices with filters
     */
    public function index(Request $request)
    {
        $query = SubscriptionInvoice::with(['vendor', 'vendor.user', 'plan', 'planPrice']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('vendor', function ($vendorQuery) use ($search) {
                      $vendorQuery->where('business_name', 'LIKE', "%{$search}%")
                                 ->orWhere('email', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('plan', function ($planQuery) use ($search) {
                      $planQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Order by latest first
        $query->orderBy('created_at', 'desc');

        $perPage = $request->input('per_page', 15);
        $invoices = $query->paginate($perPage);

        return response()->json($invoices);
    }

    /**
     * Get a single invoice with details
     */
    public function show($id)
    {
        $invoice = SubscriptionInvoice::with([
            'vendor',
            'vendor.user',
            'plan',
            'planPrice'
        ])->findOrFail($id);

        return response()->json($invoice);
    }

    /**
     * Generate a new invoice
     */
    public function generate(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'plan_price_id' => 'required|exists:plan_prices,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'amount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,paid'
        ]);

        try {
            DB::beginTransaction();

            $vendor = Vendor::findOrFail($request->vendor_id);
            $planPrice = PlanPrice::with('plan')->findOrFail($request->plan_price_id);

            // Calculate amounts if not provided
            $amount = $request->amount ?? $planPrice->price;
            $tax = $request->tax ?? 0;
            $total = $amount + $tax;

            // Generate unique invoice number
            $invoiceNumber = $this->generateInvoiceNumber();

            // Create invoice items from plan
            $items = [
                [
                    'description' => $planPrice->plan->name . ' - ' . ucfirst($planPrice->interval) . ' Subscription',
                    'amount' => $amount,
                    'plan_name' => $planPrice->plan->name,
                    'interval' => $planPrice->interval,
                    'price_id' => $planPrice->price_id
                ]
            ];

            // Add tax as a separate line item if tax > 0
            if ($tax > 0) {
                $items[] = [
                    'description' => 'Tax (' . number_format(($tax / $amount) * 100, 2) . '%)',
                    'amount' => $tax,
                    'type' => 'tax'
                ];
            }

            $invoice = SubscriptionInvoice::create([
                'invoice_number' => $invoiceNumber,
                'vendor_id' => $request->vendor_id,
                'plan_id' => $planPrice->plan_id,
                'plan_price_id' => $request->plan_price_id,
                'amount' => $amount,
                'tax' => $tax,
                'total' => $total,
                'currency' => 'USD',
                'status' => $request->status,
                'items' => $items,
                'billing_period_start' => $request->period_start,
                'billing_period_end' => $request->period_end,
                'paid_at' => $request->status === 'paid' ? now() : null,
            ]);

            // Generate PDF if needed
            // $this->generatePDF($invoice);

            DB::commit();

            return response()->json([
                'message' => 'Invoice generated successfully',
                'invoice' => $invoice->load(['vendor', 'plan', 'planPrice'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to generate invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark invoice as paid
     */
    public function markPaid($id)
    {
        try {
            DB::beginTransaction();

            $invoice = SubscriptionInvoice::findOrFail($id);
            
            if ($invoice->status === 'paid') {
                return response()->json([
                    'message' => 'Invoice is already paid'
                ], 400);
            }

            $invoice->update([
                'status' => 'paid',
                'paid_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Invoice marked as paid successfully',
                'invoice' => $invoice->load(['vendor', 'plan', 'planPrice'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to mark invoice as paid',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download invoice as PDF
     */
    public function download($id)
    {
        try {
            $invoice = SubscriptionInvoice::with([
                'vendor',
                'vendor.user',
                'plan',
                'planPrice'
            ])->findOrFail($id);

            // Check if PDF already exists
            if ($invoice->pdf_path && Storage::exists($invoice->pdf_path)) {
                return Storage::download($invoice->pdf_path, "invoice_{$invoice->invoice_number}.pdf");
            }

            // Generate PDF
            $pdf = $this->generatePDF($invoice);
            
            // Save PDF path
            $pdfPath = "invoices/invoice_{$invoice->invoice_number}.pdf";
            Storage::put($pdfPath, $pdf->output());
            $invoice->update(['pdf_path' => $pdfPath]);

            return $pdf->download("invoice_{$invoice->invoice_number}.pdf");

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to download invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

     /**
 * Export invoices to CSV
 */
public function export(Request $request)
{
    try {
        $query = SubscriptionInvoice::with(['vendor', 'vendor.user', 'plan']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('vendor', function ($vendorQuery) use ($search) {
                      $vendorQuery->where('business_name', 'LIKE', "%{$search}%")
                                 ->orWhere('email', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('plan', function ($planQuery) use ($search) {
                      $planQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $invoices = $query->orderBy('created_at', 'desc')->get();

        // Check if there are any invoices
        if ($invoices->isEmpty()) {
            // Return empty CSV with headers
            $headers = [
                'Invoice Number',
                'Vendor',
                'Plan',
                'Amount',
                'Tax',
                'Total',
                'Currency',
                'Status',
                'Billing Period Start',
                'Billing Period End',
                'Paid At',
                'Created At'
            ];

            return response()->stream(
                function () use ($headers) {
                    $file = fopen('php://output', 'w');
                    fputs($file, "\xEF\xBB\xBF");
                    fputcsv($file, $headers);
                    fputcsv($file, ['No invoices found matching the filters']);
                    fclose($file);
                },
                200,
                [
                    'Content-Type' => 'text/csv; charset=UTF-8',
                    'Content-Disposition' => "attachment; filename=\"invoices_export_" . date('Y-m-d') . ".csv\"",
                ]
            );
        }

        // Generate CSV headers
        $headers = [
            'Invoice Number',
            'Vendor',
            'Plan',
            'Amount',
            'Tax',
            'Total',
            'Currency',
            'Status',
            'Billing Period Start',
            'Billing Period End',
            'Paid At',
            'Created At'
        ];

        $rows = $invoices->map(function ($invoice) {
            return [
                $invoice->invoice_number,
                $invoice->vendor->business_name ?? 'N/A',
                $invoice->plan->name ?? 'N/A',
                number_format($invoice->amount, 2),
                number_format($invoice->tax, 2),
                number_format($invoice->total, 2),
                $invoice->currency ?? 'USD',
                $invoice->status,
                $invoice->billing_period_start ? date('Y-m-d', strtotime($invoice->billing_period_start)) : 'N/A',
                $invoice->billing_period_end ? date('Y-m-d', strtotime($invoice->billing_period_end)) : 'N/A',
                $invoice->paid_at ? date('Y-m-d H:i', strtotime($invoice->paid_at)) : 'N/A',
                $invoice->created_at ? date('Y-m-d H:i', strtotime($invoice->created_at)) : 'N/A'
            ];
        });

        $filename = "invoices_export_" . date('Y-m-d') . ".csv";

        return response()->stream(
            function () use ($headers, $rows) {
                $file = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for Excel compatibility
                fputs($file, "\xEF\xBB\xBF");
                
                // Write headers
                fputcsv($file, $headers);
                
                // Write rows
                foreach ($rows as $row) {
                    fputcsv($file, $row);
                }
                
                fclose($file);
            },
            200,
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]
        );
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to export invoices',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Get available plan prices for invoice generation
     */
    public function planPrices()
    {
        $prices = PlanPrice::with('plan')
            ->whereHas('plan', function ($query) {
                $query->where('is_active', true);
            })
            ->get();

        return response()->json([
            'data' => $prices
        ]);
    }

    /**
     * Generate a unique invoice number
     */
    private function generateInvoiceNumber()
    {
        $prefix = 'INV-';
        $year = date('Y');
        $month = date('m');
        
        // Get the last invoice number for the current month
        $lastInvoice = SubscriptionInvoice::where('invoice_number', 'LIKE', "{$prefix}{$year}{$month}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}{$year}{$month}{$newNumber}";
    }

    /**
     * Generate PDF for invoice
     */
    private function generatePDF($invoice)
    {
        $data = [
            'invoice' => $invoice,
            'vendor' => $invoice->vendor,
            'plan' => $invoice->plan,
            'items' => $this->getInvoiceItems($invoice),
            'company' => [
                'name' => config('app.name'),
                'email' => config('mail.from.address'),
                'phone' => config('app.phone', 'N/A'),
                'address' => config('app.address', 'N/A'),
                'website' => config('app.url'),
            ]
        ];

        $pdf = PDF::loadView('pdf.invoice', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf;
    }

    /**
     * Get invoice items from JSON
     */
    private function getInvoiceItems($invoice)
    {
        if ($invoice->items && is_array($invoice->items)) {
            return $invoice->items;
        }
        
        return [
            [
                'description' => $invoice->plan->name ?? 'Subscription',
                'amount' => $invoice->amount
            ]
        ];
    }

    /**
     * Delete an invoice
     */
    public function destroy($id)
    {
        try {
            $invoice = SubscriptionInvoice::findOrFail($id);
            
            // Delete PDF if exists
            if ($invoice->pdf_path && Storage::exists($invoice->pdf_path)) {
                Storage::delete($invoice->pdf_path);
            }
            
            $invoice->delete();

            return response()->json([
                'message' => 'Invoice deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refund an invoice
     */
    public function refund($id)
    {
        try {
            DB::beginTransaction();

            $invoice = SubscriptionInvoice::findOrFail($id);
            
            if ($invoice->status !== 'paid') {
                return response()->json([
                    'message' => 'Only paid invoices can be refunded'
                ], 400);
            }

            $invoice->update([
                'status' => 'refunded'
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Invoice refunded successfully',
                'invoice' => $invoice->load(['vendor', 'plan', 'planPrice'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to refund invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get invoice analytics
     */
    public function analytics(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        $monthlyRevenue = SubscriptionInvoice::where('status', 'paid')
            ->whereYear('paid_at', $year)
            ->selectRaw('MONTH(paid_at) as month, SUM(total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        $statusDistribution = SubscriptionInvoice::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return response()->json([
            'monthly_revenue' => $monthlyRevenue,
            'status_distribution' => $statusDistribution,
            'total_revenue' => SubscriptionInvoice::where('status', 'paid')->sum('total'),
            'average_invoice' => SubscriptionInvoice::where('status', 'paid')->avg('total'),
        ]);
    }

    /**
     * Bulk mark invoices as paid
     */
    public function bulkMarkPaid(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:subscription_invoices,id'
        ]);

        try {
            DB::beginTransaction();

            $count = SubscriptionInvoice::whereIn('id', $request->ids)
                ->where('status', 'pending')
                ->update([
                    'status' => 'paid',
                    'paid_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'message' => "{$count} invoices marked as paid successfully"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to mark invoices as paid',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}