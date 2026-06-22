<?php
// database/migrations/xxxx_xx_xx_create_subscription_invoices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();;
            $table->foreignId('plan_id')->constrained('plans');
            $table->foreignId('plan_price_id')->constrained('plan_prices');
            $table->decimal('amount', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->json('items');
            $table->json('payment_details')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('stripe_invoice_id')->nullable()->unique();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('pdf_path')->nullable();
            $table->date('billing_period_start');
            $table->date('billing_period_end');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_invoices');
    }
};