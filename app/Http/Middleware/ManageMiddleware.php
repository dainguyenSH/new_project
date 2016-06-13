<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Store;
use App\Merchant;

class ManageMiddleware
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

        if (Auth::store()->check()) {

            $getMerchantId = Store::find(Auth::store()->get()->id)->merchants_id;

            $merchant = Merchant::find($getMerchantId)->active;

            if ($merchant == 1) {

                if (Auth::store()->get()->active == 1) {
                    return $next($request);
                } else {
                    Auth::store()->logout();
                    if ($request->ajax()) {
                        return response('Unauthorized.', 423);
                    } else {
                        return redirect('login/manage')->withErrors('Tài khoản của bạn đã bị Deactive bởi Merchant');
                    }
                }

            } else {

                Auth::store()->logout();
                if ($request->ajax()) {
                    return response('Unauthorized.', 401);
                } else {
                    return redirect('login/manage')->withErrors('Thương hiệu của bạn đã bị Deactive bởi SuperAdmin');
                }
            }

        } else {

            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect('login/manage')->withErrors('Phiên đăng nhập Thu ngân của bạn đã hết hạn. Vui lòng đăng nhập lại');
            }

        }
    }
}
