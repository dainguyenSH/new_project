<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class MerchantMiddleware
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
        if (Auth::merchant()->check()) {

            if (Auth::merchant()->get()->active == 1) {
                return $next($request);
            } elseif ( Auth::merchant()->get()->active == 3 ) {
                Auth::merchant()->logout();
                if ($request->ajax()) {
                    return response('Unauthorized.', 424);
                } else {
                    Auth::merchant()->logout();
                    return redirect('login')->withErrors('Tài khoản của bạn đã bị Deactive bởi SuperAdmin. Vui lòng liên hệ Admin để được trợ giúp');
                }
            }
                return $next($request);

        } else {

            if ($request->ajax()) {
                return response('Unauthorized.', 402);
            } else {
                return redirect('login')->withErrors('Phiên đăng nhập Merchant của bạn đã hết hạn. Vui lòng đăng nhập lại');
            }

        }
    }
}
