<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!in_array(auth()->user()->role_type, $roles)) {
			return redirect()->back()->with('error', 'Không có quyền truy cập');
        }

        return $next($request);
    }
}