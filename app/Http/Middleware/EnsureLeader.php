<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLeader
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || ! $user->isLeader()) {
            return redirect()
                ->route('home')
                ->with('error', 'Доступно только ведущему мастер-класса.');
        }

        return $next($request);
    }
}
