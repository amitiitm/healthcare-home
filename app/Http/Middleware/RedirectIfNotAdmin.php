<?php namespace App\Http\Middleware;
use App\Models\Enums\PramatiConstants;
use Closure;

class RedirectIfNotAdmin {

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

		if (!($loggedInUser && $loggedInUser->user_type_id!=PramatiConstants::EMPLOYEE_USER_TYPE)) {
			if (!($loggedInUser && $loggedInUser->is_admin)) {
				return redirect('/auth/login');
			}
		}

		return $next($request);
	}

}
