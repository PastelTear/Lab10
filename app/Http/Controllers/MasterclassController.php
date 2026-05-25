<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CraftCategory;
use App\Models\Masterclass;
use App\Support\TimeSlots;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MasterclassController extends Controller
{
    public function create(): View
    {
        $categories = CraftCategory::query()->orderBy('id')->get();

        return view('masterclass.create', [
            'categories' => $categories,
            'timeSlots' => TimeSlots::SLOTS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $validated = $this->validateMasterclass($request);

        $startTime = TimeSlots::normalize($validated['start_time']);

        if (Masterclass::query()
            ->where('leader_id', $user->id)
            ->where('mc_date', $validated['mc_date'])
            ->where('start_time', $startTime)
            ->exists()) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Это время у вас уже занято.']);
        }

        try {
            Masterclass::query()->create([
                'leader_id' => $user->id,
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'mc_date' => $validated['mc_date'],
                'start_time' => $startTime,
                'max_participants' => $validated['max_participants'],
                'price' => $validated['price'],
            ]);
        } catch (QueryException) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Это время у вас уже занято.']);
        }

        return redirect()
            ->route('cabinet')
            ->with('ok', 'Мастер-класс добавлен.');
    }

    public function edit(Masterclass $masterclass): View|RedirectResponse
    {
        $this->authorizeOwnership($masterclass);

        return view('masterclass.edit', [
            'masterclass' => $masterclass,
        ]);
    }

    public function update(Request $request, Masterclass $masterclass): RedirectResponse
    {
        $this->authorizeOwnership($masterclass);

        $validated = $request->validate([
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $masterclass->update([
            'description' => $validated['description'],
            'price' => round((float) $validated['price'], 2),
        ]);

        return redirect()
            ->route('cabinet')
            ->with('ok', 'Изменения сохранены.');
    }

    public function busySlots(Request $request): JsonResponse
    {
        $user = Auth::user();
        if ($user === null || ! $user->isLeader()) {
            return response()->json(['busy' => [], 'error' => 'forbidden'], 403);
        }

        $date = $request->query('date');
        if (! is_string($date) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return response()->json(['busy' => []]);
        }

        $busy = Masterclass::query()
            ->where('leader_id', $user->id)
            ->whereDate('mc_date', $date)
            ->pluck('start_time')
            ->map(fn ($time) => TimeSlots::normalize((string) $time))
            ->values()
            ->all();

        return response()->json(['busy' => $busy]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateMasterclass(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'integer', Rule::exists('craft_categories', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'mc_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', Rule::in(array_keys(TimeSlots::SLOTS))],
            'max_participants' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
        ], [
            'mc_date.after_or_equal' => 'Дата не может быть в прошлом.',
            'start_time.in' => 'Выберите время из сетки 9—11, 11—13, 13—15, 15—17.',
        ]);
    }

    private function authorizeOwnership(Masterclass $masterclass): void
    {
        if ($masterclass->leader_id !== Auth::id()) {
            abort(403);
        }
    }
}
