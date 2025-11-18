<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController; 
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomerController;

// Guest Pages
Route::get('/', [UserController::class, 'home'])->name('home');
Route::get('/booking', [BookingController::class, 'create'])->name('booking');
Route::get('/shop', [UserController::class, 'shop'])->name('shop');
Route::get('/contact', [UserController::class, 'contact'])->name('contact');
Route::get('/calendar', [UserController::class, 'calendar'])->name('calendar');

// Booking Routes (Member only)
Route::middleware('auth:member')->group(function () {
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/booking/{booking}/success', [BookingController::class, 'success'])->name('booking.success');
    Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    // Redirect old booking history URL to payment history so there is only one history page
    Route::get('/history', function () {
        return redirect()->route('payment.history');
    })->name('history');
});

// Feedback Routes (dari contact form - TIDAK PERLU LOGIN)
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

// Testimonial Routes (member only - harus login dan punya booking completed)
Route::middleware('auth:member')->group(function () {
    Route::post('/testimonials', [\App\Http\Controllers\TestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials/bookings', [\App\Http\Controllers\TestimonialController::class, 'getCompletedBookings'])->name('testimonials.bookings');
});

// Pet Management Routes (member only)
Route::middleware('auth:member')->group(function () {
    Route::get('/pets', [\App\Http\Controllers\PetController::class, 'index'])->name('pets.index');
    Route::post('/pets', [\App\Http\Controllers\PetController::class, 'store'])->name('pets.store');
    Route::put('/pets/{pet}', [\App\Http\Controllers\PetController::class, 'update'])->name('pets.update');
    Route::delete('/pets/{pet}', [\App\Http\Controllers\PetController::class, 'destroy'])->name('pets.destroy');
    Route::post('/pets/calculate-delivery', [\App\Http\Controllers\PetController::class, 'calculateDeliveryFee'])->name('pets.calculate-delivery');
});

// Payment Routes (member only)
Route::middleware('auth:member')->group(function () {
    Route::post('/payment/booking/{booking}', [PaymentController::class, 'createBookingPayment'])->name('payment.booking');
    Route::get('/payment/success/{orderId}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed/{orderId}', [PaymentController::class, 'failed'])->name('payment.failed');
    Route::get('/payment/history', [PaymentController::class, 'history'])->name('payment.history');
    Route::get('/payment/transaction/{orderId}', [PaymentController::class, 'show'])->name('payment.show');
});

// Midtrans Notification Callback (public - no auth required)
Route::post('/payment/notification', [PaymentController::class, 'handleNotification'])->name('payment.notification');

// Calendar API Routes (public)
Route::get('/calendar/data', [\App\Http\Controllers\CalendarController::class, 'getCalendarData'])->name('calendar.data');
Route::post('/calendar/check-availability', [\App\Http\Controllers\CalendarController::class, 'checkAvailability'])->name('calendar.check');

// Member Auth
Route::get('/register', function () {
    return view('register'); // tampilkan form
})->name('register.page');
Route::post('/register', [MemberController::class, 'register'])->name('register');

// Pet Registration Page (after member registration)
Route::get('/register/pets', function () {
    return view('register-pets');
})->middleware('auth:member')->name('register.pets');

Route::get('/login', function () {
    return view('register'); // tampilkan form
})->name('register.page');
Route::post('/login', [MemberController::class, 'login'])->name('login');

Route::post('/logout', [MemberController::class, 'logout'])->name('logout');

// Admin Auth Routes - menggunakan member login dengan role admin
Route::post('/admin/logout', [MemberController::class, 'logout'])->name('admin.logout');

Route::get('/profile', [UserController::class, 'profile'])
    ->middleware('auth:member') // ✅ hanya bisa diakses kalau login
    ->name('profile');
Route::get('/check-auth', function() {
    return response()->json([
        'authenticated' => auth()->check()
    ]);
})->name('check.auth');

// Debug: List all members
Route::get('/debug/members', function() {
    $members = \App\Models\Member::select('id', 'name', 'phone', 'email')->orderBy('id')->get();
    return response()->json([
        'total' => $members->count(),
        'members' => $members
    ]);
});

// Force logout & clear all sessions
Route::get('/force-logout', function() {
    // Logout from all guards
    Auth::guard('member')->logout();
    Auth::logout();
    
    // Clear session
    session()->flush();
    session()->regenerate();
    
    // Clear all cookies
    foreach ($_COOKIE as $key => $value) {
        setcookie($key, '', time() - 3600, '/');
    }
    
    return redirect('/login')->with('success', 'All sessions cleared. Please login again.');
})->name('force.logout');

// Debug: Check orphaned pets
Route::get('/debug/orphaned-pets', function() {
    $orphanedPets = DB::table('pets')
        ->leftJoin('members', 'pets.member_id', '=', 'members.id')
        ->whereNull('members.id')
        ->select('pets.*')
        ->get();
    
    $validMembers = \App\Models\Member::select('id', 'name')->get();
    
    return response()->json([
        'orphaned_pets' => $orphanedPets,
        'orphaned_count' => $orphanedPets->count(),
        'valid_members' => $validMembers,
        'suggestion' => $orphanedPets->count() > 0 ? 'Run: php artisan fix:orphaned-pets' : 'All pets are valid!'
    ]);
});

// Debug route - remove after testing
Route::get('/debug/pet-ownership', function() {
    $member = Auth::guard('member')->user();
    if (!$member) {
        return response()->json(['error' => 'Not logged in']);
    }
    
    $pets = \App\Models\Pet::where('member_id', $member->id)->get();
    
    return response()->json([
        'current_member' => [
            'id' => $member->id,
            'id_type' => gettype($member->id),
            'name' => $member->name,
        ],
        'pets' => $pets->map(function($pet) {
            return [
                'id' => $pet->id,
                'name' => $pet->name,
                'member_id' => $pet->member_id,
                'member_id_type' => gettype($pet->member_id),
                'match' => ($pet->member_id == Auth::guard('member')->id() ? 'YES' : 'NO'),
                'match_strict' => ($pet->member_id === Auth::guard('member')->id() ? 'YES' : 'NO'),
            ];
        })
    ]);
})->middleware('auth:member');

// Admin Pages
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/dashboard/calendar', [AdminController::class, 'getDashboardCalendar'])->name('admin.dashboard.calendar');
    Route::get('/admin/productmanagement', [AdminController::class, 'productmanagement']);
    Route::get('/admin/customer', [AdminController::class, 'customer']);
    Route::get('/admin/managebooking', [BookingController::class, 'adminIndex'])->name('admin.managebooking');
    // Admin Booking JSON APIs
    Route::get('/admin/bookings', [BookingController::class, 'adminList'])->name('admin.bookings.index');
    Route::post('/admin/bookings', [BookingController::class, 'adminStore'])->name('admin.bookings.store');
    Route::get('/admin/bookings/{booking}', [BookingController::class, 'adminShow'])->name('admin.bookings.show');
    Route::put('/admin/bookings/{booking}', [BookingController::class, 'adminUpdate'])->name('admin.bookings.update');
    Route::post('/admin/booking/{booking}/status', [BookingController::class, 'updateStatus'])->name('admin.booking.status');
    Route::delete('/admin/booking/{booking}', [BookingController::class, 'destroy'])->name('admin.booking.destroy');
    
    // Admin Schedule Routes (JSON APIs)
    Route::prefix('admin/schedule')->name('admin.schedule.')->group(function () {
        Route::get('/calendar', [\App\Http\Controllers\ScheduleController::class, 'getCalendarSchedule'])->name('calendar');
        Route::get('/list', [\App\Http\Controllers\ScheduleController::class, 'getScheduleList'])->name('list');
        Route::get('/date/{date}', [\App\Http\Controllers\ScheduleController::class, 'getBookingsByDate'])->name('date');
        Route::post('/capacity', [\App\Http\Controllers\ScheduleController::class, 'updateCapacity'])->name('capacity');
        Route::get('/statistics', [\App\Http\Controllers\ScheduleController::class, 'getStatistics'])->name('statistics');
    });
    
    Route::get('/admin/schedule', [AdminController::class, 'schedule']);
    
    // Admin Testimonial Routes (dari user yang sudah booking)
    Route::prefix('admin/testimonials')->name('admin.testimonials.')->group(function () {
        Route::get('/', [\App\Http\Controllers\TestimonialController::class, 'adminIndex'])->name('index');
        Route::put('/{testimonial}', [\App\Http\Controllers\TestimonialController::class, 'update'])->name('update');
        Route::delete('/{testimonial}', [\App\Http\Controllers\TestimonialController::class, 'destroy'])->name('destroy');
    });
    
    // Admin Feedback Routes (dari contact form)
    Route::prefix('admin/feedback')->name('admin.feedback.')->group(function () {
        Route::get('/', [FeedbackController::class, 'index'])->name('index');
        Route::put('/{feedback}', [FeedbackController::class, 'update'])->name('update');
        Route::delete('/{feedback}', [FeedbackController::class, 'destroy'])->name('destroy');
    });
    
    // Admin Product Routes (JSON APIs)
    Route::prefix('admin/products')->name('admin.products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Admin Customer Routes (JSON APIs) - manage members with role=member
    Route::prefix('admin/customers')->name('admin.customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{member}', [CustomerController::class, 'show'])->name('show');
        Route::put('/{member}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{member}', [CustomerController::class, 'destroy'])->name('destroy');
    });

    Route::get('/admin/settings', [AdminController::class, 'settings']);
    Route::get('/admin/petfood', [AdminController::class, 'petfood'])->name('admin.petfood');
    Route::get('/admin/petsupplies', [AdminController::class, 'petsupplies'])->name('admin.petsupplies'); 
    Route::get('/admin/petvitamins', [AdminController::class, 'petvitamins'])->name('admin.petvitamins');
    Route::get('/admin/testimoni', [\App\Http\Controllers\TestimonialController::class, 'adminIndex'])->name('admin.testimoni');
});

// Debug Route (member only)
Route::get('/debug/payment', function() {
    if (!Auth::guard('member')->check()) {
        return response('<h1>❌ Not Logged In</h1><p>Please <a href="/login">login</a> first.</p>', 401);
    }

    $member = Auth::guard('member')->user();
    $bookings = \App\Models\Booking::where('member_id', $member->id)
        ->with(['transaction', 'pet'])
        ->latest()
        ->take(10)
        ->get();

    $html = "
    <html>
    <head>
        <title>Debug Payment</title>
        <style>
            body { font-family: sans-serif; padding: 20px; }
            table { border-collapse: collapse; width: 100%; margin: 20px 0; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background: #f4a28c; color: #674337; }
            .success { color: green; font-weight: bold; }
            .error { color: red; font-weight: bold; }
            pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
        </style>
    </head>
    <body>
        <h1>🔍 Debug Payment Issues</h1>
        <p class='success'>✅ Logged in as: <strong>{$member->name}</strong> (ID: {$member->id})</p>
        
        <h2>📋 Your Recent Bookings</h2>";

    if ($bookings->isEmpty()) {
        $html .= "<p>No bookings found.</p>";
    } else {
        $html .= "<table>
            <tr>
                <th>ID</th>
                <th>Service</th>
                <th>Pet</th>
                <th>Member ID<br>(booking)</th>
                <th>Current<br>Member ID</th>
                <th>Match?</th>
                <th>Booking<br>Status</th>
                <th>Payment<br>Status</th>
                <th>Transaction ID</th>
            </tr>";
        
        foreach ($bookings as $booking) {
            $match = (int)$booking->member_id === (int)$member->id;
            $matchClass = $match ? 'success' : 'error';
            $matchIcon = $match ? '✅' : '❌';
            
            $html .= "<tr>
                <td>{$booking->id}</td>
                <td>".ucfirst($booking->service_type)."</td>
                <td>".($booking->pet ? $booking->pet->name : 'N/A')."</td>
                <td>{$booking->member_id}<br><small>(".gettype($booking->member_id).")</small></td>
                <td>{$member->id}<br><small>(".gettype($member->id).")</small></td>
                <td class='{$matchClass}'>{$matchIcon}</td>
                <td>{$booking->booking_status}</td>
                <td>".($booking->transaction ? $booking->transaction->transaction_status : 'NO TRANSACTION')."</td>
                <td>".($booking->transaction ? $booking->transaction->order_id : '-')."</td>
            </tr>";
        }
        
        $html .= "</table>";

        // Test first booking
        if ($bookings->isNotEmpty()) {
            $testBooking = $bookings->first();
            $canPay = $testBooking->transaction 
                      && $testBooking->transaction->transaction_status === 'pending' 
                      && $testBooking->booking_status === 'pending';
            
            $html .= "<h2>🧪 Test Validation (Booking #{$testBooking->id})</h2>";
            $html .= "<pre>";
            $html .= "Booking Member ID: {$testBooking->member_id} (".gettype($testBooking->member_id).")\n";
            $html .= "Current Member ID: {$member->id} (".gettype($member->id).")\n\n";
            $html .= "Direct (===): ".($testBooking->member_id === $member->id ? '✅ TRUE' : '❌ FALSE')."\n";
            $html .= "Loose (==): ".($testBooking->member_id == $member->id ? '✅ TRUE' : '❌ FALSE')."\n";
            $html .= "Casted: ".((int)$testBooking->member_id === (int)$member->id ? '✅ TRUE' : '❌ FALSE')."\n";
            $html .= "</pre>";

            $html .= "<h3>Can Pay This Booking?</h3>";
            if ($canPay) {
                $html .= "<p class='success'>✅ YES - Should see 'Pay Now' button</p>";
            } else {
                $html .= "<p class='error'>❌ NO - Reason:</p><ul>";
                if (!$testBooking->transaction) {
                    $html .= "<li>No transaction found</li>";
                } elseif ($testBooking->transaction->transaction_status !== 'pending') {
                    $html .= "<li>Transaction status: {$testBooking->transaction->transaction_status}</li>";
                }
                if ($testBooking->booking_status !== 'pending') {
                    $html .= "<li>Booking status: {$testBooking->booking_status}</li>";
                }
                $html .= "</ul>";
            }
        }
    }

    $html .= "
        <h2>⚙️ Midtrans Config</h2>
        <pre>Client Key: ".config('midtrans.client_key')."
Server Key: ".substr(config('midtrans.server_key'), 0, 20)."...
Is Production: ".(config('midtrans.is_production') ? 'true' : 'false')."</pre>
        
        <hr>
        <p><a href='/history'>← Back to History</a> | <a href='/booking'>Make New Booking</a></p>
    </body>
    </html>";

    return $html;
})->middleware('auth:member')->name('debug.payment');
