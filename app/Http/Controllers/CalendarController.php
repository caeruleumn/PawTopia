<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    /**
     * Get calendar data with booking counts for user calendar
     */
    public function getCalendarData(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $startOfMonth = now()->setDate($year, $month, 1)->startOfDay();
        $endOfMonth = (clone $startOfMonth)->endOfMonth();

        // Maximum capacity per day (20 pets)
        $maxCapacity = 20;
        
        // Ambil semua booking aktif yang rentangnya memotong bulan ini
        // (booking_date .. booking_date + duration_days - 1) beririsan dengan
        // rentang [startOfMonth, endOfMonth]
        $bookings = Booking::whereIn('status', ['pending', 'confirmed', 'checked-in'])
            ->whereDate('booking_date', '<=', $endOfMonth->toDateString())
            ->whereRaw('DATE_ADD(booking_date, INTERVAL COALESCE(duration_days, 1) - 1 DAY) >= ?', [$startOfMonth->toDateString()])
            ->get(['booking_date', 'duration_days']);

        $bookingCounts = [];

        foreach ($bookings as $booking) {
            $duration = max(1, (int) ($booking->duration_days ?? 1));

            for ($offset = 0; $offset < $duration; $offset++) {
                $date = $booking->booking_date->copy()->addDays($offset);

                // Hanya hitung tanggal yang masih di bulan & tahun yang diminta
                if ((int) $date->format('Y') !== $year || (int) $date->format('n') !== $month) {
                    continue;
                }

                $key = $date->format('Y-m-d');
                $bookingCounts[$key] = ($bookingCounts[$key] ?? 0) + 1;
            }
        }

        $bookingCountsCollection = collect($bookingCounts);

        // Determine which dates are full and calculate availability levels
        $fullDates = [];
        $availabilityLevels = [];
        
        foreach ($bookingCountsCollection as $date => $count) {
            if ($count >= $maxCapacity) {
                $fullDates[] = $date;
                $availabilityLevels[$date] = 'full'; // 100% booked
            } elseif ($count >= $maxCapacity * 0.75) {
                $availabilityLevels[$date] = 'low'; // 75-99% booked (few slots)
            } elseif ($count >= $maxCapacity * 0.5) {
                $availabilityLevels[$date] = 'medium'; // 50-74% booked
            } else {
                $availabilityLevels[$date] = 'high'; // 0-49% booked (many slots)
            }
        }

        return response()->json([
            'success' => true,
            'month' => $month,
            'year' => $year,
            'booking_counts' => $bookingCountsCollection,
            'full_dates' => $fullDates,
            'availability_levels' => $availabilityLevels,
            'max_capacity' => $maxCapacity
        ]);
    }

    /**
     * Check if a specific date is available
     */
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date'
        ]);

        $maxCapacity = 20;

        $date = $validated['date'];

        // Hitung semua booking yang rentangnya (booking_date .. booking_date + duration_days - 1)
        // mencakup tanggal yang dicek.
        $bookedCount = Booking::whereIn('status', ['pending', 'confirmed', 'checked-in'])
            ->whereDate('booking_date', '<=', $date)
            ->whereRaw('DATE_ADD(booking_date, INTERVAL COALESCE(duration_days, 1) - 1 DAY) >= ?', [$date])
            ->count();

        $availableSlots = $maxCapacity - $bookedCount;
        $isAvailable = $availableSlots > 0;

        return response()->json([
            'success' => true,
            'date' => $validated['date'],
            'is_available' => $isAvailable,
            'available_slots' => $availableSlots,
            'booked_count' => $bookedCount,
            'max_capacity' => $maxCapacity
        ]);
    }
}
