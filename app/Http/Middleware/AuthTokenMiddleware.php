<?php

namespace App\Http\Middleware;

use App\Helpers\AppHelper;
use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class AuthTokenMiddleware
{

    private $AppHelper;
    private $User;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->User = new User();
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if (is_null($request->token) || empty($request->token)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } else {
                $user = $this->User->query_find_by_token($request->token);

                $yesterday = $this->AppHelper->day_time() - 86400;

                if (empty($user) || ($user['login_time'] < $yesterday)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return $next($request);
    }
}
