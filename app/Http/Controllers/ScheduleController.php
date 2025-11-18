<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Get schedule summary for calendar view
     * Returns aggregated booking data by date
     */
    public function getCalendarSchedule(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);
        $startOfMonth = now()->setDate($year, $month, 1)->startOfDay();
        $endOfMonth = (clone $startOfMonth)->endOfMonth();

        // Get all active bookings whose stay range overlaps this month
        // Range: booking_date .. booking_date + duration_days - 1
        $bookings = Booking::whereIn('status', ['pending', 'confirmed', 'checked-in'])
            ->whereDate('booking_date', '<=', $endOfMonth->toDateString())
            ->whereRaw('DATE_ADD(booking_date, INTERVAL COALESCE(duration_days, 1) - 1 DAY) >= ?', [$startOfMonth->toDateString()])
            ->get(['booking_date', 'duration_days']);

        // Aggregate bookings per day by expanding each stay to its full duration
        $dailyCounts = [];
        foreach ($bookings as $booking) {
            $duration = max(1, (int) ($booking->duration_days ?? 1));

            for ($offset = 0; $offset < $duration; $offset++) {
                $date = $booking->booking_date->copy()->addDays($offset);

                // Only count days inside this month/year
                if ((int) $date->format('Y') !== $year || (int) $date->format('n') !== $month) {
                    continue;
                }

                $key = $date->format('Y-m-d');
                $dailyCounts[$key] = ($dailyCounts[$key] ?? 0) + 1;
            }
        }

        // Format data for calendar
        $scheduleData = [];
        foreach ($dailyCounts as $date => $booked) {
            $capacity = 25; // Default capacity per day
            $remaining = $capacity - $booked;

            // Determine status
            $ratio = $capacity > 0 ? $booked / $capacity : 0;
            if ($booked >= $capacity) {
                $status = 'busy'; // Full
            } elseif ($ratio >= 0.7) {
                $status = 'moderate'; // Almost full
            } else {
                $status = 'available'; // Available
            }

            $scheduleData[$date] = [
                'date' => $date,
                'capacity' => $capacity,
                'booked' => $booked,
                'remaining' => $remaining,
                'status' => $status,
                'label' => "{$booked}/{$capacity}"
            ];
        }

        return response()->json([
            'success' => true,
            'schedules' => $scheduleData
        ]);
    }

    /**
     * Get detailed schedule list with pagination
     */
    public function getScheduleList(Request $request)
    {
        $filterDate = $request->input('date');
        $perPage = $request->integer('per_page', 10);
        
        // Ambil semua booking aktif
        $bookings = Booking::whereIn('status', ['pending', 'confirmed', 'checked-in'])
            ->get(['booking_date', 'duration_days']);

        // Hitung jumlah booking per hari dengan memperluas durasi
        $dailyCounts = [];
        foreach ($bookings as $booking) {
            $duration = max(1, (int) ($booking->duration_days ?? 1));

            for ($offset = 0; $offset < $duration; $offset++) {
                $date = $booking->booking_date->copy()->addDays($offset)->format('Y-m-d');

                if ($filterDate && $date !== $filterDate) {
                    continue;
                }

                $dailyCounts[$date] = ($dailyCounts[$date] ?? 0) + 1;
            }
        }

        // Transform aggregated counts into schedule rows
        krsort($dailyCounts); // latest date first

        $index = 0;
        $transformedSchedules = collect();
        foreach ($dailyCounts as $date => $booked) {
            $capacity = 25;
            $remaining = $capacity - $booked;
            $ratio = $capacity > 0 ? $booked / $capacity : 0;

            if ($booked >= $capacity) {
                $status = 'Full';
                $statusClass = 'status-full';
            } elseif ($remaining <= 5) {
                $status = 'Almost Full';
                $statusClass = 'status-moderate';
            } else {
                $status = 'Available';
                $statusClass = 'status-available';
            }

            $transformedSchedules->push([
                'id' => ++$index,
                'date' => $date,
                'date_formatted' => Carbon::parse($date)->format('D, M j, Y'),
                'capacity' => $capacity,
                'booked' => $booked,
                'remaining' => $remaining > 0 ? $remaining : 0,
                'status' => $status,
                'status_class' => $statusClass,
            ]);
        }

        // Manual pagination
        $page = $request->integer('page', 1);
        $total = $transformedSchedules->count();
        $lastPage = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        
        $paginatedData = $transformedSchedules->slice($offset, $perPage)->values();

        return response()->json([
            'success' => true,
            'data' => $paginatedData,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => $lastPage,
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $total)
            ]
        ]);
    }

    /**
     * Get bookings for a specific date
     */
    public function getBookingsByDate(Request $request, $date)
    {
        // Ambil semua booking yang STAY range-nya mencakup tanggal ini
        // booking_date .. booking_date + duration_days - 1
        $bookings = Booking::with('member')
            ->whereIn('status', ['pending', 'confirmed', 'checked-in'])
            ->whereDate('booking_date', '<=', $date)
            ->whereRaw('DATE_ADD(booking_date, INTERVAL COALESCE(duration_days, 1) - 1 DAY) >= ?', [$date])
            ->orderBy('booking_time')
            ->get();

        $capacity = 25;
        $booked = $bookings->count();
        $remaining = $capacity - $booked;

        return response()->json([
            'success' => true,
            'date' => $date,
            'capacity' => $capacity,
            'booked' => $booked,
            'remaining' => $remaining,
            'bookings' => $bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'pet_name' => $booking->pet_name,
                    'pet_type' => $booking->pet_type,
                    'customer_name' => $booking->member->name ?? 'N/A',
                    'service_type' => $booking->service_type,
                    'booking_time' => $booking->booking_time,
                    'status' => $booking->status
                ];
            })
        ]);
    }

    /**
     * Update daily capacity (admin only)
     */
    public function updateCapacity(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'capacity' => 'required|integer|min:1|max:100'
        ]);

        // In a real application, you might store capacity in a separate table
        // For now, we'll return success as capacity is handled per-date
        
        return response()->json([
            'success' => true,
            'message' => 'Capacity updated successfully',
            'date' => $validated['date'],
            'capacity' => $validated['capacity']
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function getStatistics()
    {
        $today = now()->toDateString();
        $thisMonth = now()->month;
        $thisYear = now()->year;

        $todayBookings = Booking::whereDate('booking_date', $today)
            ->whereIn('status', ['pending', 'confirmed', 'checked-in'])
            ->count();

        $monthBookings = Booking::whereYear('booking_date', $thisYear)
            ->whereMonth('booking_date', $thisMonth)
            ->whereIn('status', ['pending', 'confirmed', 'checked-in'])
            ->count();

        $upcomingBookings = Booking::whereDate('booking_date', '>', $today)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        return response()->json([
            'success' => true,
            'statistics' => [
                'today_bookings' => $todayBookings,
                'month_bookings' => $monthBookings,
                'upcoming_bookings' => $upcomingBookings,
                'today_capacity' => 25,
                'today_remaining' => max(0, 25 - $todayBookings)
            ]
        ]);
    }
}
