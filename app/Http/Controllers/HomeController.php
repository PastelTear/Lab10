<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CraftCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = CraftCategory::query()->orderBy('id')->get();
        $myBookings = collect();

        $user = Auth::user();
        if ($user !== null && $user->isVisitor()) {
            $myBookings = $user->bookings()
                ->with(['masterclass.category', 'masterclass.leader'])
                ->get()
                ->sortByDesc(static function (Booking $booking) {
                    $mc = $booking->masterclass;

                    return $mc->mc_date->format('Y-m-d').' '.$mc->start_time;
                })
                ->values();
        }

        return view('home', [
            'categories' => $categories,
            'myBookings' => $myBookings,
        ]);
    }
}
