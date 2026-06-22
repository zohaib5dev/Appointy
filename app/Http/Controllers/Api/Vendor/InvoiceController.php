<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    protected function getVendor(Request $request)
    {
        return $request->user()->vendor;
    }

    public function index(Request $request)
    {
        
        $vendor = $this->getVendor($request);
        
        $query = Invoice::with(['customer','appointment'])
            ->where('vendor_id', $vendor->id);

        if ($request->filled('date_from')) {
            $query->whereDate('issue_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('issue_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%")
                         ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
            });
        }

        $invoices = $query->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($invoices);
    }
 
   
    public function show(Request $request, $id)
    {
        $vendor = $this->getVendor($request);
        
        $invoice = Invoice::with(['customer','appointment'])
            ->where('vendor_id', $vendor->id)
            ->findOrFail($id);

        $invoice->isOverdue = $invoice->status !== 'paid' && 
                              $invoice->status !== 'cancelled' && 
                              Carbon::parse($invoice->due_date)->isPast();

        return response()->json($invoice);
    }

   
    public function download(Request $request, $id)
    {
        $vendor = $this->getVendor($request);
        $invoice = Invoice::with(['customer', 'appointment'])
            ->where('vendor_id', $vendor->id)
            ->findOrFail($id);

        $pdf = Pdf::loadView('vendor.invoices.pdf', [
            'invoice' => $invoice,
            'vendor' => $vendor,
        ]);

        return $pdf->download('invoice_' . $invoice->invoice_number . '.pdf');
    }


 public function export(Request $request)
{
    $vendor = $this->getVendor($request);
    
    $query = Invoice::with(['customer', 'appointment'])
        ->where('vendor_id', $vendor->id);

    // Apply filters
    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('invoice_number', 'LIKE', "%{$search}%")
              ->orWhereHas('customer', function($cq) use ($search) {
                  $cq->where('name', 'LIKE', "%{$search}%")
                     ->orWhere('email', 'LIKE', "%{$search}%")
                     ->orWhere('phone', 'LIKE', "%{$search}%");
              })
              ->orWhereHas('appointment', function($aq) use ($search) {
                  $aq->where('booking_number', 'LIKE', "%{$search}%");
              });
        });
    }

    $invoices = $query->orderBy('created_at', 'desc')->get();

    // Generate CSV
    $csv = "Invoice #,Appointment #,Customer,Email,Phone,Amount,Date,Status\n";
    
    foreach ($invoices as $invoice) {
        $csv .= sprintf(
            "%s,%s,%s,%s,%s,%.2f,%s,%s\n",
            $invoice->invoice_number,
            $invoice->appointment->booking_number ?? 'N/A',
            $invoice->customer->name ?? 'N/A',
            $invoice->customer->email ?? 'N/A',
            $invoice->customer->phone ?? 'N/A',
            $invoice->total ?? 0,
            $invoice->created_at ? date('M d Y', strtotime($invoice->created_at)) : 'N/A',
            $invoice->appointment->payment_status ?? 'pending'
        );
    }

    return response($csv)
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="invoices_' . date('Y-m-d') . '.csv"');
}

 
    
}