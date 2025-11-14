<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Testimonial;

class UserController extends Controller
{
    public function home()
    {
        // Fetch recent positive testimonials (4-5 stars) dari user yang sudah booking
        $testimonials = Testimonial::with('member', 'booking')
            ->whereIn('rating', [4, 5])
            ->latest()
            ->take(8)
            ->get();

        return view('landing', compact('testimonials'));
    }

    public function calendar()
    {
        return view('calendar');
    }

    public function shop()
    {
        $all = Product::orderByDesc('created_at')->get();

        // Normalize category to lower for checks
        $normalize = function ($v) {
            return strtolower(trim((string)$v));
        };

        $catFood = $all->filter(function ($p) use ($normalize) {
            $c = $normalize($p->category);
            return str_contains($c, 'cat') && str_contains($c, 'food');
        });

        $dogFood = $all->filter(function ($p) use ($normalize) {
            $c = $normalize($p->category);
            return str_contains($c, 'dog') && str_contains($c, 'food');
        });

        $supplies = $all->filter(function ($p) use ($normalize) {
            $c = $normalize($p->category);
            return str_contains($c, 'supplies') || str_contains($c, 'supply');
        });

        $catSupplies = $supplies->filter(function ($p) use ($normalize) {
            $c = $normalize($p->category);
            $n = $normalize($p->name);
            return str_contains($c, 'cat') || str_contains($n, 'cat');
        });
        $dogSupplies = $supplies->filter(function ($p) use ($normalize) {
            $c = $normalize($p->category);
            $n = $normalize($p->name);
            return str_contains($c, 'dog') || str_contains($n, 'dog');
        });

        $vitamins = $all->filter(function ($p) use ($normalize) {
            $c = $normalize($p->category);
            return str_contains($c, 'vitamin');
        });

        $catVitamins = $vitamins->filter(function ($p) use ($normalize) {
            $c = $normalize($p->category);
            $n = $normalize($p->name);
            return str_contains($c, 'cat') || str_contains($n, 'cat');
        });
        $dogVitamins = $vitamins->filter(function ($p) use ($normalize) {
            $c = $normalize($p->category);
            $n = $normalize($p->name);
            return str_contains($c, 'dog') || str_contains($n, 'dog');
        });

        return view('shop', compact('catFood', 'dogFood', 'catSupplies', 'dogSupplies', 'catVitamins', 'dogVitamins'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function profile()
    {
        $member = auth('member')->user();
        $pets = $member->pets()->where('is_active', true)->get();
        
        return view('profile', compact('pets'));
    }

}
