<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EnsureCustomGuardIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = null, $redirectTo = 'login')
    {
        if (! $request->user($guard) ||
            ($request->user($guard) instanceof MustVerifyEmail &&
            ! $request->user($guard)->hasVerifiedEmail())) {
            
            //check if the request is an ajax request.
            if ($request->expectsJson()) {
                //send a json response
                return abort(403, 'Your email address is not verified.');
            } else {
                //redirect to the hotel dashboard.
                return Redirect::route($redirectTo);
            }
        }

        return $next($request);
    }
}
