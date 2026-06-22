<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vendors table
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('slug')->unique(); // Unique constraint implies index
            $table->enum('status', ['pending', 'active', 'suspended', 'rejected'])->default('pending')->index(); // Often filtered
            $table->string('subscription_status')->default('trial')->index(); // Often filtered
            $table->string('stripe_customer_id')->nullable()->index(); // Lookups by Stripe ID
            $table->string('stripe_subscription_id')->nullable()->unique();
            $table->string('stripe_account_id')->nullable();
            $table->string('stripe_account_status')->nullable();
            $table->string('plan_id')->nullable();
            $table->string('price_id')->nullable();
            $table->timestamp('trial_ends_at')->nullable(); // Made nullable if not all have trials
            $table->timestamp('subscription_starts_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();

            $table->string('business_name');
            $table->text('description')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('timezone')->default('UTC');

            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable()->index(); // Common filter
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();

            $table->boolean('is_verified')->default(true)->index();
            $table->timestamp('verified_at')->nullable();
            $table->decimal('commission_rate', 5, 2)->nullable();
            $table->decimal('earnings', 12, 2)->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);

            $table->json('settings')->nullable();
            $table->json('social_links')->nullable();
            $table->timestamps();
        });

        // Service categories
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('color')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Services
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('service_categories');
            $table->string('name');
            $table->string('slug');
            $table->text('description');
            $table->integer('duration'); // in minutes
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('buffer_time')->default(0); // minutes before/after
            $table->integer('max_bookings_per_slot')->default(1);
            $table->timestamps();
        });

        // Staff members
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('position');
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('permissions')->nullable();
            $table->timestamps();
        });

        Schema::create('staff_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Schedules (working hours)
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('breaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained()->onDelete('cascade');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });


        // Holidays
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Appointments
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained()->onDelete('set null');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show']);
            $table->text('customer_notes')->nullable();
            $table->text('vendor_notes')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'refunded', 'failed'])->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_id')->nullable();
            $table->boolean('is_waitlist')->default(false);
            $table->timestamps();

            $table->index(['vendor_id', 'start_time']);
            $table->index(['customer_id', 'status']);
        });

        // Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('vendor_id')->constrained();
            $table->foreignId('service_id')->constrained();
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->text('vendor_response')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });

        // Coupons
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->string('code')->unique();
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount_value', 10, 2);
            $table->decimal('minimum_spend', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('vendor_id')->constrained();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('payment_method');
            $table->string('transaction_id')->unique();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded']);
            $table->json('payment_details')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // Plans (subscription)
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->integer('services_limit');
            $table->integer('staff_limit');
            $table->integer('appointments_limit');
            $table->integer('storage_limit')->default(5000);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // System notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('type');
            $table->json('data')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('appointment_id')->constrained();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('vendor_id')->constrained();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->json('items');
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });

        // Audit logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('action');
            $table->string('module');
            $table->text('description');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('breaks');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_categories');
        Schema::dropIfExists('vendors');
    }
};
