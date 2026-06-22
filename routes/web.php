<?php

use Illuminate\Support\Facades\Route;

use App\Models\Vendor;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('app');
});

Route::get('/stripe/success', function (Request $request) {

    $vendor = Vendor::find($request->vendor_id);

    if ($vendor) {
        $vendor->stripe_account_status = 'pending_review';
        $vendor->status = 'pending';
        $vendor->save();
    }

    return redirect('/vendor/dashboard')
        ->with('success', 'Stripe onboarding completed successfully.');
})->name('stripe.success');


Route::get('/stripe/refresh', function () {
    return redirect('/vendor/onboarding');
})->name('stripe.refresh');


 

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
