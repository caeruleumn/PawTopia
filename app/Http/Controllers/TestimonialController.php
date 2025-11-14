<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    /**
     * Store testimonial - HANYA untuk user yang sudah login dan punya booking completed
     */
    public function store(Request $request)
    {
        try {
            // Validasi: User harus login dengan member guard
            if (!Auth::guard('member')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to submit a testimonial.'
                ], 401);
            }

            $validated = $request->validate([
                'booking_id' => 'required|integer|exists:bookings,id',
                'rating' => 'required|integer|min:1|max:5',
                'message' => 'required|string|max:1000',
            ]);

            // Verify member exists in database
            $currentMember = Auth::guard('member')->user();
            $memberCheck = \App\Models\Member::find($currentMember->id);
            if (!$memberCheck) {
                \Log::error('Member ID from session does not exist in database', [
                    'session_member_id' => $currentMember->id
                ]);
                
                Auth::guard('member')->logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Session corrupted. Please login again.'
                ], 401);
            }

            $memberId = $memberCheck->id;
            $bookingId = $validated['booking_id'];

            // Cek apakah booking milik member ini dan statusnya completed
            $booking = Booking::where('id', $bookingId)
                ->where('member_id', $memberId)
                ->where('status', 'completed')
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only submit testimonials for your completed bookings.'
                ], 403);
            }

            // Cek apakah sudah ada testimonial untuk booking ini
            $existingTestimonial = Testimonial::where('member_id', $memberId)
                ->where('booking_id', $bookingId)
                ->first();

            if ($existingTestimonial) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already submitted a testimonial for this booking.'
                ], 422);
            }

            // Buat testimonial
            $testimonial = Testimonial::create([
                'member_id' => $memberId,
                'booking_id' => $bookingId,
                'rating' => $validated['rating'],
                'message' => $validated['message']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your testimonial!',
                'testimonial' => $testimonial->load('member', 'booking')
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Testimonial submission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit testimonial. Please try again.'
            ], 500);
        }
    }

    /**
     * Get user's completed bookings (untuk dropdown di form testimonial)
     */
    public function getCompletedBookings()
    {
        if (!Auth::guard('member')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $memberId = Auth::guard('member')->id();

        // Ambil booking yang completed dan belum ada testimonial
        $completedBookings = Booking::where('member_id', $memberId)
            ->where('status', 'completed')
            ->whereDoesntHave('testimonial')
            ->with('member')
            ->orderByDesc('booking_date')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'service_type' => $booking->service_type,
                    'pet_name' => $booking->pet_name,
                    'booking_date' => $booking->booking_date->format('M d, Y'),
                    'label' => "{$booking->service_type} - {$booking->pet_name} ({$booking->booking_date->format('M d, Y')})"
                ];
            });

        return response()->json([
            'success' => true,
            'bookings' => $completedBookings
        ]);
    }

    /**
     * Display testimonials for admin
     */
    public function adminIndex()
    {
        $query = Testimonial::with('member', 'booking')->latest();

        // Filter by rating
        if (request()->has('rating') && !empty(request('rating'))) {
            $query->where('rating', request('rating'));
        }

        // Search
        if (request()->has('search') && !empty(request('search'))) {
            $searchTerm = '%' . request('search') . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('message', 'like', $searchTerm)
                  ->orWhereHas('member', function($memberQuery) use ($searchTerm) {
                      $memberQuery->where('name', 'like', $searchTerm);
                  });
            });
        }

        $testimonials = $query->paginate(10)->appends(request()->query());

        // Statistics
        $totalTestimonials = Testimonial::count();
        $averageRating = Testimonial::avg('rating') ?: 0;
        $thisMonth = Testimonial::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $positiveReviews = Testimonial::whereIn('rating', [4, 5])->count();
        $positivePercentage = $totalTestimonials > 0 
            ? round(($positiveReviews / $totalTestimonials) * 100) 
            : 0;

        return view('admin.testimoni', [
            'testimonials' => $testimonials,
            'totalTestimonials' => $totalTestimonials,
            'averageRating' => number_format($averageRating, 1),
            'thisMonth' => $thisMonth,
            'positivePercentage' => $positivePercentage
        ]);
    }

    /**
     * Update testimonial
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string|max:1000',
                'rating' => 'required|integer|min:1|max:5'
            ]);

            $testimonial->update($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Testimonial updated successfully',
                    'testimonial' => $testimonial->fresh()
                ]);
            }

            return redirect()->back()->with('success', 'Testimonial updated successfully');

        } catch (\Exception $e) {
            \Log::error('Error updating testimonial: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update testimonial'
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to update testimonial');
        }
    }

    /**
     * Delete testimonial
     */
    public function destroy(Testimonial $testimonial)
    {
        try {
            $testimonial->delete();

            return response()->json([
                'success' => true,
                'message' => 'Testimonial deleted successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error deleting testimonial: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete testimonial'
            ], 500);
        }
    }
}
