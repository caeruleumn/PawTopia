<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class FeedbackController extends Controller
{
    /**
     * Store feedback from contact form - TIDAK PERLU LOGIN
     * Feedback berasal dari contact form, bukan testimonial
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'message' => 'required|string|max:1000',
                'user_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);

            // Feedback tidak perlu user_id atau booking_id
            // Ini untuk contact form saja
            Feedback::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your feedback!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Feedback submission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit feedback. Please try again.'
            ], 500);
        }
    }

    /**
     * Display all feedbacks for admin.
     */
    public function index()
    {
        // Get all feedback ordered by latest
        $query = Feedback::latest();

        // Filter by rating if provided
        if (request()->has('rating') && !empty(request('rating'))) {
            $query->where('rating', request('rating'));
        }

        // Search in message and user_name if search term provided
        if (request()->has('search') && !empty(request('search'))) {
            $searchTerm = '%' . request('search') . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('message', 'like', $searchTerm)
                  ->orWhere('user_name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm);
            });
        }

        // Paginate the results
        $feedbacks = $query->paginate(10)->appends(request()->query());
        
        // Calculate statistics
        $totalFeedback = $feedbacks->total();
        $averageRating = $feedbacks->avg('rating') ?: 0;
        
        // Count feedback from current month
        $thisMonth = Feedback::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        // Calculate positive feedback percentage (4-5 stars)
        $positiveReviews = Feedback::whereIn('rating', [4, 5])->count();
        $positivePercentage = $totalFeedback > 0 ? round(($positiveReviews / $totalFeedback) * 100) : 0;
        
        return view('admin.feedback', [
            'feedbacks' => $feedbacks,
            'totalFeedback' => $totalFeedback,
            'averageRating' => number_format($averageRating, 1),
            'thisMonth' => $thisMonth,
            'positivePercentage' => $positivePercentage
        ]);
    }

    /**
     * Remove the specified feedback from storage.
     */
    public function destroy(Feedback $feedback)
    {
        try {
            $feedback->delete();
            
            return redirect()
                ->route('admin.feedback.index')
                ->with('success', 'Feedback deleted successfully');
                
        } catch (\Exception $e) {
            \Log::error('Error deleting feedback: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.feedback.index')
                ->with('error', 'Failed to delete feedback. Please try again.');
        }
    }

    /**
     * Update the specified feedback.
     */
    public function update(Request $request, Feedback $feedback)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string|max:1000',
                'rating' => 'required|integer|min:1|max:5'
            ]);

            $feedback->update($validated);
            
            // If it's an AJAX request, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Feedback updated successfully',
                    'feedback' => $feedback->fresh()
                ]);
            }
            
            // For regular form submission
            return redirect()
                ->route('admin.feedback.index')
                ->with('success', 'Feedback updated successfully');
            
        } catch (\Exception $e) {
            \Log::error('Error updating feedback: ' . $e->getMessage());
            
            // If it's an AJAX request, return JSON error
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update feedback. Please try again.'
                ], 500);
            }
            
            // For regular form submission
            return back()
                ->withInput()
                ->with('error', 'Failed to update feedback. Please try again.');
        }
    }
}
