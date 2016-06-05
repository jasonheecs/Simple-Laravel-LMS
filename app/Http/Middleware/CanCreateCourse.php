<?php

namespace App\Http\Middleware;

use Closure;

// use App\Course;

class CanCreateCourse
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
        if (!$request->user()->canCreateCourse()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['response' => 'Unauthorized access'], 401);
            }

            flash('You do not have permission to access this page', 'danger');
            return back();
        }

        return $next($request);
    }
}
