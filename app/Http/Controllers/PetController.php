<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    /**
     * Get all pets for current logged-in member
     */
    public function index()
    {
        $member = Auth::guard('member')->user();
        $pets = $member->activePets()->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'pets' => $pets
        ]);
    }

    /**
     * Store a new pet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:dog,cat',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0|max:30',
            'weight' => 'nullable|numeric|min:0|max:100',
            'medical_notes' => 'nullable|string',
            'special_requirements' => 'nullable|string',
            'photo' => 'nullable|image|max:2048'
        ]);

        // Verify member exists in database
        $member = Auth::guard('member')->user();
        $memberCheck = \App\Models\Member::find($member->id);
        if (!$memberCheck) {
            \Log::error('Member ID from session does not exist in database', [
                'session_member_id' => $member->id
            ]);
            
            Auth::guard('member')->logout();
            return response()->json([
                'success' => false,
                'message' => 'Session corrupted. Please login again.'
            ], 401);
        }
        
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('pets', 'public');
        }

        $validated['member_id'] = $memberCheck->id;
        
        $pet = Pet::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pet added successfully!',
            'pet' => $pet
        ], 201);
    }

    /**
     * Update an existing pet
     */
    public function update(Request $request, Pet $pet)
    {
        // Check ownership
        if ($pet->member_id !== Auth::guard('member')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:dog,cat',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0|max:30',
            'weight' => 'nullable|numeric|min:0|max:100',
            'medical_notes' => 'nullable|string',
            'special_requirements' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($pet->photo) {
                Storage::disk('public')->delete($pet->photo);
            }
            $validated['photo'] = $request->file('photo')->store('pets', 'public');
        }

        $pet->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pet updated successfully!',
            'pet' => $pet
        ]);
    }

    /**
     * Delete (soft delete) a pet
     */
    public function destroy(Pet $pet)
    {
        // Check ownership
        if ($pet->member_id !== Auth::guard('member')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Soft delete - just mark as inactive
        $pet->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Pet removed successfully!'
        ]);
    }

    /**
     * Calculate delivery fee based on distance
     */
    public function calculateDeliveryFee(Request $request)
    {
        $validated = $request->validate([
            'distance_km' => 'required|numeric|min:0',
            'drop_off_type' => 'required|in:owner,daycare',
            'pick_up_type' => 'required|in:owner,daycare'
        ]);

        $distance = $validated['distance_km'];
        $dropOffFee = 0;
        $pickUpFee = 0;

        // Calculate fee based on distance
        // Rp 10,000 per km, minimum Rp 20,000
        if ($validated['drop_off_type'] === 'daycare') {
            $dropOffFee = max(20000, $distance * 10000);
        }

        if ($validated['pick_up_type'] === 'daycare') {
            $pickUpFee = max(20000, $distance * 10000);
        }

        $totalDeliveryFee = $dropOffFee + $pickUpFee;

        return response()->json([
            'success' => true,
            'drop_off_fee' => $dropOffFee,
            'pick_up_fee' => $pickUpFee,
            'total_delivery_fee' => $totalDeliveryFee,
            'per_km_rate' => 10000,
            'minimum_fee' => 20000
        ]);
    }
}
