<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ReviewRequest;
use App\Models\Appointment;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = Review::with(['vendor', 'customer', 'appointment']);

        if ($vendor = $request->query('vendor_id')) {
            $query->where('vendor_id', $vendor);
        }

        return $this->success($query->paginate(20));
    }

    public function store(Request $request)
    {
        $appointment = Appointment::find($request->appointment_id);

        $review = new Review();
        $review->customer_id = $appointment->customer_id;
        $review->vendor_id = $appointment->vendor_id;
        $review->service_id = $appointment->service_id;
        $review->appointment_id = $appointment->id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        return $this->success($review, 'Review posted successfully', 201);
    }

    public function show(Review $review)
    {
        return $this->success($review);
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $review->update($request->validated());
        return $this->success($review, 'Review updated successfully');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return $this->success([], 'Review removed successfully');
    }
}
