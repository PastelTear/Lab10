<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Masterclass;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function confirm(Masterclass $masterclass): View|RedirectResponse
    {
        $masterclass->load(['category', 'leader']);
        $masterclass->loadCount('bookings');

        $user = Auth::user();
        if ($masterclass->freePlaces() <= 0) {
            return redirect()
                ->route('category.show', $masterclass->category_id)
                ->with('error', 'Нет свободных мест.');
        }

        if (Booking::query()
            ->where('user_id', $user->id)
            ->where('masterclass_id', $masterclass->id)
            ->exists()) {
            return redirect()
                ->route('category.show', $masterclass->category_id)
                ->with('error', 'Вы уже записаны на этот мастер-класс.');
        }

        return view('booking.confirm', [
            'masterclass' => $masterclass,
        ]);
    }

    public function store(Request $request, Masterclass $masterclass): RedirectResponse
    {
        $action = (string) $request->input('action', '');

        if ($action === 'cancel') {
            return redirect()
                ->route('category.show', $masterclass->category_id)
                ->with('ok', 'Запись отменена.');
        }

        if ($action !== 'confirm') {
            return redirect()->route('home')->with('error', 'Некорректный запрос.');
        }

        $user = Auth::user();

        try {
            DB::transaction(function () use ($user, $masterclass): void {
                $locked = Masterclass::query()
                    ->whereKey($masterclass->id)
                    ->lockForUpdate()
                    ->withCount('bookings')
                    ->firstOrFail();

                if ($locked->freePlaces() <= 0) {
                    throw new \RuntimeException('no_places');
                }

                if (Booking::query()
                    ->where('user_id', $user->id)
                    ->where('masterclass_id', $locked->id)
                    ->exists()) {
                    throw new \RuntimeException('duplicate');
                }

                Booking::query()->create([
                    'user_id' => $user->id,
                    'masterclass_id' => $locked->id,
                ]);
            });
        } catch (QueryException) {
            return redirect()
                ->route('category.show', $masterclass->category_id)
                ->with('error', 'Не удалось записаться (возможно, места заняты или вы уже записаны).');
        } catch (\RuntimeException $exception) {
            $message = match ($exception->getMessage()) {
                'no_places' => 'Нет свободных мест.',
                'duplicate' => 'Вы уже записаны на этот мастер-класс.',
                default => 'Ошибка при записи. Попробуйте позже.',
            };

            return redirect()
                ->route('category.show', $masterclass->category_id)
                ->with('error', $message);
        }

        return redirect()
            ->route('category.show', $masterclass->category_id)
            ->with('ok', 'Вы успешно записаны на мастер-класс.');
    }
}
