<?php

// app/Http/Middleware/RoleMiddleware.php

// app/Http/Middleware/IsAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (Auth::check() && in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }
        return redirect()->route('roles.index')
            ->with('error', 'You do not have access to this page.');
    }
}

