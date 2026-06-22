
<?php

use App\Http\Controllers\Api\Admin\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\Admin\InvoiceController;
use App\Http\Controllers\Api\Admin\PlanController;
use App\Http\Controllers\Api\Admin\ServiceCategoryController;
use App\Http\Controllers\Api\Admin\VendorController;
use App\Http\Controllers\Api\Admin\HomeController as AdminHomeController;

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\HomeController;

use App\Http\Controllers\Api\Vendor\DashboardController;
use App\Http\Controllers\Api\Vendor\ProfileController;
use App\Http\Controllers\Api\Vendor\InvoiceController as VendorInvoiceController;
use App\Http\Controllers\Api\Vendor\AppointmentController as VendorAppointmentController;
use App\Http\Controllers\Api\Vendor\StaffController as VendorStaffController;
use App\Http\Controllers\Api\Vendor\HolidayController as VendorHolidayController;
use App\Http\Controllers\Api\Vendor\ReportController as VendorReportController;
use App\Http\Controllers\Api\Vendor\ScheduleController as VendorScheduleController;
use App\Http\Controllers\Api\Vendor\ServiceController as VendorServiceController;
use App\Http\Controllers\Api\Vendor\SubscriptionController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/categories', [HomeController::class, 'categories']);
Route::get('/vendors', [HomeController::class, 'vendors']);
Route::get('/vendors/{slug}', [HomeController::class, 'vendor']);
Route::get('/vendors/{vendor}/services', [HomeController::class, 'getServices']);
Route::get('/vendors/{vendor}/staff', [HomeController::class, 'getStaff']);
Route::get('/vendors/{vendor}/schedule', [HomeController::class, 'getSchedule']);
Route::get('/services', [HomeController::class, 'services']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Appointments
    Route::get('/appointments/available-slots', [AppointmentController::class, 'getAvailableSlots']);
    Route::ApiResource('appointments', AppointmentController::class);
    Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel']);
    Route::post('/appointments/{appointment}/reschedule', [AppointmentController::class, 'reschedule']);

    // Reviews
    Route::ApiResource('reviews', ReviewController::class);
    Route::put('customer/profile', [HomeController::class, 'updateProfile']);



    // Vendor specific
    Route::get('vendor/subscription/status', [DashboardController::class, 'checkStatus']);
    Route::middleware(['role:vendor', 'vendorSubscription'])->prefix('vendor')->group(function () {

        Route::get('/settings', [ProfileController::class, 'show']);
        Route::put('/settings', [ProfileController::class, 'update']);
        Route::post('/settings/upload', [ProfileController::class, 'uploadImage']);

        Route::put('profile', [ProfileController::class, 'updateProfile']);
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');



        Route::get('/dashboard', [DashboardController::class, 'index']);

        Route::ApiResource('services', VendorServiceController::class);
        Route::ApiResource('staff', VendorStaffController::class);
        Route::ApiResource('schedules', VendorScheduleController::class);

        Route::get('holidays/calendar', [VendorHolidayController::class, 'calendar']);
        Route::ApiResource('holidays', VendorHolidayController::class);

        Route::get('/reports', [VendorReportController::class, 'index']);
        Route::get('/reports/export', [VendorReportController::class, 'export']);

        Route::get('/appointments', [VendorAppointmentController::class, 'index']);
        Route::get('/appointments/{id}', [VendorAppointmentController::class, 'show']);
        Route::put('/appointments/{id}/status', [VendorAppointmentController::class, 'updateStatus']);
        Route::put('/appointments/{id}/staff', [VendorAppointmentController::class, 'updateStaff']);
        Route::put('/appointments/{id}/notes', [VendorAppointmentController::class, 'updateNotes']);
        Route::post('/appointments/{id}/remind', [VendorAppointmentController::class, 'sendReminder']);

        Route::get('/onboarding', [SubscriptionController::class, 'stripeOnboarding']);

        Route::prefix('subscriptions')->group(function () {
            Route::get('/current', [SubscriptionController::class, 'currentSubscription']);
            Route::get('/plans', [SubscriptionController::class, 'availablePlans']);
            Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
            Route::post('/cancel', [SubscriptionController::class, 'cancelSubscription']);
            Route::get('/invoices', [SubscriptionController::class, 'invoiceHistory']);
            Route::get('/invoices/{invoiceId}', [SubscriptionController::class, 'showInvoice']);
            Route::get('/invoices/{invoiceId}/download', [SubscriptionController::class, 'downloadInvoice']);
        });


        Route::get('/invoices', [VendorInvoiceController::class, 'index']);
        Route::get('/invoices/export', [VendorInvoiceController::class, 'export']);
        Route::get('/invoices/{id}', [VendorInvoiceController::class, 'show']);
        Route::get('/invoices/{id}/download', [VendorInvoiceController::class, 'download']);
    });


    // Admin specific
    Route::middleware('role:super_admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminHomeController::class, 'dashboard']);

        Route::put('profile', [AdminHomeController::class, 'updateProfile']);
        Route::put('/profile/password', [AdminHomeController::class, 'updatePassword'])->name('profile.password');

        Route::ApiResource('categories', ServiceCategoryController::class);
        Route::ApiResource('/plans', PlanController::class);
        Route::ApiResource('vendors', VendorController::class);
        Route::ApiResource('customers', CustomerController::class);

        Route::get('appointments', [AdminHomeController::class, 'appointments']);
        Route::get('appointments/{appointment}', [AdminHomeController::class, 'appointment']);

        Route::post('/subscriptions/plans', [AdminHomeController::class, 'createPlan']);
        Route::get('/reports', [AdminHomeController::class, 'generateReports']);
        Route::put('/settings', [AdminHomeController::class, 'updateSettings']);

        Route::get('/invoices/stats', [InvoiceController::class, 'stats']);
        Route::get('/invoices/export', [InvoiceController::class, 'export']);
        Route::get('/invoices/analytics', [InvoiceController::class, 'analytics']);
        Route::get('/plan-prices', [InvoiceController::class, 'planPrices']);
        Route::get('/invoices', [InvoiceController::class, 'index']);
        Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
        Route::post('/invoices/generate', [InvoiceController::class, 'generate']);
        Route::put('/invoices/{id}/mark-paid', [InvoiceController::class, 'markPaid']);
        Route::put('/invoices/{id}/refund', [InvoiceController::class, 'refund']);
        Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy']);
        Route::get('/invoices/{id}/download', [InvoiceController::class, 'download']);
        Route::post('/invoices/bulk-mark-paid', [InvoiceController::class, 'bulkMarkPaid']);
    });
});
