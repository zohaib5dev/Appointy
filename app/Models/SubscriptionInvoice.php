<?php
// app/Models/SubscriptionInvoice.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionInvoice extends Model
{
    protected $table = 'subscription_invoices';

    protected $fillable = [
        'invoice_number',
        'vendor_id',
        'subscription_id',
        'plan_id',
        'plan_price_id',
        'amount',
        'tax',
        'total',
        'currency',
        'status',
        'items',
        'payment_details',
        'paid_at',
        'stripe_invoice_id',
        'stripe_payment_intent_id',
        'pdf_path',
        'billing_period_start',
        'billing_period_end'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'items' => 'array',
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'billing_period_start' => 'date',
        'billing_period_end' => 'date'
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function planPrice(): BelongsTo
    {
        return $this->belongsTo(PlanPrice::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function markAsPaid(string $paymentIntentId = null): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'stripe_payment_intent_id' => $paymentIntentId ?? $this->stripe_payment_intent_id
        ]);
    }

    public function markAsFailed(array $paymentDetails = null): void
    {
        $this->update([
            'status' => 'failed',
            'payment_details' => $paymentDetails
        ]);
    }

    public function markAsRefunded(): void
    {
        $this->update(['status' => 'refunded']);
    }
}