<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CraftCategory;
use App\Models\Masterclass;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function show(CraftCategory $category): View
    {
        $categories = CraftCategory::query()->orderBy('id')->get();

        $masterclasses = Masterclass::query()
            ->with('leader')
            ->withCount('bookings')
            ->where('category_id', $category->id)
            ->orderBy('mc_date')
            ->orderBy('start_time')
            ->get();

        $bookedIds = [];
        $user = Auth::user();
        if ($user !== null && $user->isVisitor()) {
            $bookedIds = $user->bookings()
                ->whereIn('masterclass_id', $masterclasses->pluck('id'))
                ->pluck('masterclass_id')
                ->all();
        }

        return view('category.show', [
            'category' => $category,
            'categories' => $categories,
            'masterclasses' => $masterclasses,
            'bookedIds' => $bookedIds,
        ]);
    }
}
