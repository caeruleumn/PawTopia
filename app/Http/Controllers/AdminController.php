<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // method untuk proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // method logout admin
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login'); // arahkan ke admin login page
    }

    /**
     * Get calendar data for dashboard
     */
    public function getDashboardCalendar(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $bookings = \App\Models\Booking::whereYear('booking_date', $year)
            ->whereMonth('booking_date', $month)
            ->whereIn('status', ['pending', 'confirmed', 'checked-in'])
            ->select('booking_date', \DB::raw('COUNT(*) as total_booked'))
            ->groupBy('booking_date')
            ->get()
            ->keyBy(function($item) {
                return $item->booking_date->format('Y-m-d');
            });

        return response()->json([
            'success' => true,
            'bookings' => $bookings
        ]);
    }

    // halaman dashboard
    public function dashboard()
    {
        // Total Bookings
        $totalBookings = \App\Models\Booking::count();
        $thisWeekBookings = \App\Models\Booking::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
        
        // Registered Users (Members)
        $totalMembers = \App\Models\Member::count();
        $recentMembers = \App\Models\Member::where('created_at', '>=', now()->subDays(3))->count();
        
        // Today's Pick-Up (bookings dengan status on-pickup atau completed hari ini)
        $todayPickup = \App\Models\Booking::whereDate('booking_date', today())
            ->whereIn('status', ['on-pickup', 'completed'])
            ->count();
        $todayPickupCompleted = \App\Models\Booking::whereDate('booking_date', today())
            ->where('status', 'completed')
            ->count();
        $todayPickupRemaining = $todayPickup - $todayPickupCompleted;
        
        // Reviews (Testimonials + Feedback)
        $totalTestimonials = \App\Models\Testimonial::count();
        $totalFeedback = \App\Models\Feedback::count();
        $totalReviews = $totalTestimonials + $totalFeedback;
        
        // Recent Bookings for table
        $recentBookings = \App\Models\Booking::with('member')
            ->latest()
            ->take(10)
            ->get();
        
        // Booking statistics by status
        $pendingBookings = \App\Models\Booking::where('status', 'pending')->count();
        $confirmedBookings = \App\Models\Booking::where('status', 'confirmed')->count();
        $completedBookings = \App\Models\Booking::where('status', 'completed')->count();
        
        // Today's Activities
        $todayActivities = \App\Models\Booking::whereDate('created_at', today())->count();
        $dogsBoarded = \App\Models\Booking::where('pet_type', 'dog')
            ->whereDate('booking_date', '<=', today())
            ->whereIn('status', ['confirmed', 'checked-in'])
            ->count();
        $catsBoarded = \App\Models\Booking::where('pet_type', 'cat')
            ->whereDate('booking_date', '<=', today())
            ->whereIn('status', ['confirmed', 'checked-in'])
            ->count();
        
        // Monthly Stats
        $monthlyRevenue = \App\Models\Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereIn('status', ['completed', 'confirmed', 'checked-in'])
            ->sum('total_price');
        $monthlyPets = \App\Models\Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $averageRating = \App\Models\Testimonial::avg('rating') ?: 0;
        
        // Capacity Utilization (assuming max 20 pets per day)
        $activePets = \App\Models\Booking::whereDate('booking_date', '<=', today())
            ->whereIn('status', ['confirmed', 'checked-in'])
            ->count();
        $capacityUtilization = $activePets > 0 ? min(100, round(($activePets / 20) * 100)) : 0;
        
        // Recent Activities for timeline
        $recentActivities = \App\Models\Booking::with('member')
            ->whereDate('created_at', today())
            ->latest()
            ->take(5)
            ->get()
            ->map(function($booking) {
                return [
                    'type' => 'booking',
                    'text' => 'New Booking',
                    'detail' => $booking->member->name . ' booked ' . $booking->service_type . ' for ' . $booking->pet_name,
                    'time' => $booking->created_at->diffForHumans(),
                    'status' => $booking->status
                ];
            });
        
        return view('admin.dashboard', compact(
            'totalBookings',
            'thisWeekBookings',
            'totalMembers',
            'recentMembers',
            'todayPickup',
            'todayPickupCompleted',
            'todayPickupRemaining',
            'totalReviews',
            'recentBookings',
            'pendingBookings',
            'confirmedBookings',
            'completedBookings',
            'todayActivities',
            'dogsBoarded',
            'catsBoarded',
            'monthlyRevenue',
            'monthlyPets',
            'averageRating',
            'capacityUtilization',
            'recentActivities'
        ));
    }

    // dst...
    public function productmanagement()
    {
        // Preload products from DB so the view can render immediately
        \App\Models\Product::query(); // ensure model is autoloaded
        $all = \App\Models\Product::orderByDesc('created_at')->get();

        $petFood = $all->filter(function ($p) {
            $c = strtolower(trim((string)$p->category));
            return $c === 'cat food' || $c === 'dog food' || str_contains($c, 'food');
        })->take(3);
        $supplies = $all->filter(function ($p) {
            $c = strtolower(trim((string)$p->category));
            return $c === 'supplies' || str_contains($c, 'suppl');
        })->take(3);
        $vitamins = $all->filter(function ($p) {
            $c = strtolower(trim((string)$p->category));
            return $c === 'vitamin' || str_contains($c, 'vitamin');
        })->take(3);

        return view('admin.productmanagement', compact('petFood', 'supplies', 'vitamins'));
    }

    public function customer()
    {
        return view('admin.customer');
    }

    public function managebooking()
    {
        return view('admin.managebooking');
    }

    public function schedule()
    {
        return view('admin.schedule');
    }

    public function testimoni()
    {
        return view('admin.testimoni');
    }

    public function feedback() 
    {
        return view('admin.feedback');
    }

    public function petfood()
    {
        $all = \App\Models\Product::orderByDesc('created_at')->get();
        $items = $all->filter(function ($p) {
            $c = strtolower(trim((string)$p->category));
            return $c === 'cat food' || $c === 'dog food' || str_contains($c, 'food');
        });
        return view('admin.petfood', ['items' => $items]);
    }

    public function petsupplies()
    {
        $all = \App\Models\Product::orderByDesc('created_at')->get();
        $items = $all->filter(function ($p) {
            $c = strtolower(trim((string)$p->category));
            return $c === 'supplies' || str_contains($c, 'suppl');
        });
        return view('admin.petsupplies', ['items' => $items]);
    }

    public function petvitamins()
    {
        $all = \App\Models\Product::orderByDesc('created_at')->get();
        $items = $all->filter(function ($p) {
            $c = strtolower(trim((string)$p->category));
            return $c === 'vitamin' || str_contains($c, 'vitamin');
        });
        return view('admin.petvitamins', ['items' => $items]);
    }
}
