<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        $user = Auth::user();
        if ($user !== null) {
            return redirect()->route($user->isLeader() ? 'cabinet' : 'home');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'Неверный e-mail или пароль.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(
            Auth::user()->isLeader() ? route('cabinet') : route('home')
        );
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fio' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['required', 'string', 'max:50', function (string $attribute, mixed $value, \Closure $fail): void {
                $digits = preg_replace('/\D+/', '', (string) $value);
                if ($digits === null || $digits === '') {
                    $fail('Некорректный номер телефона.');

                    return;
                }
                $ok = (strlen($digits) === 11 && ($digits[0] === '7' || $digits[0] === '8'))
                    || (strlen($digits) === 10 && $digits[0] === '9');
                if (! $ok) {
                    $fail('Телефон: укажите российский номер (10 цифр с 9 или 11 с 7/8).');
                }
            }],
        ]);

        $user = User::query()->create([
            'fio' => $validated['fio'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'],
            'role' => 'visitor',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('home')
            ->with('ok', 'Регистрация успешна. Добро пожаловать!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}