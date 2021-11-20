<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AssignGuard extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::guard('admin')->user() instanceof Admin&&Auth::guard('admin')->user()!=null){
            return $next($request);
        }

        return response()->json(['status' => 'Authorization Token not found']);

    }
}