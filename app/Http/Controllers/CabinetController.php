<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CraftCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CabinetController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $categories = CraftCategory::query()->orderBy('id')->get();

        $masterclasses = $user->ledMasterclasses()
            ->with(['bookings.user'])
            ->withCount('bookings')
            ->orderByDesc('mc_date')
            ->orderByDesc('start_time')
            ->get();

        return view('cabinet.index', [
            'user' => $user,
            'categories' => $categories,
            'masterclasses' => $masterclasses,
        ]);
    }
}
