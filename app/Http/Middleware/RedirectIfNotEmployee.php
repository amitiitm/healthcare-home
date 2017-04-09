<?php namespace App\Http\Middleware;

use Closure;

class RedirectIfNotEmployee {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        $loggedInUser = $request->user();

        if (!$loggedInUser || $loggedInUser->user_type_id!=1) {
            return redirect('/auth/login');
        }


        if (!$loggedInUser || $loggedInUser->user_type_id!=1) {
            return redirect('/auth/login');
        }

		return $next($request);
	}

}
