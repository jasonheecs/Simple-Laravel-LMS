<?php

namespace App\Http\Middleware;

use Closure;

class IsAdministrator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->is('admin') || $request->user()->is('superadmin')) {
            return $next($request);
        }
        
        flash('You do not have access to this page!', 'danger');
        return back();
    }
}
