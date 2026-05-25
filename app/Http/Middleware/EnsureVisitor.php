<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || ! $user->isVisitor()) {
            return redirect()
                ->route('home')
                ->with('error', 'Запись доступна только посетителям.');
        }

        return $next($request);
    }
}
