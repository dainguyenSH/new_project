<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Response;
use App\Merchant;
use App\InfoUser;
// use App\Manage;
use App\Store;
use Input;
use Mail;
use Auth;
use Hash;
use Session;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    

    public function getIndex() {
        return view('auth.merchant-login');
        // return view('auth.index');
    }

    //FOR MERCHANT
    //Get View Page Merchant
    public function getMerchant()
    {
    }

    //Excute post register merchant
    public function postMerchant(Request $request)
    {
        $remember = (Input::has('remember')) ? true : false;

        if ( Auth::merchant()->attempt(['email' => $request->email, 'password' => $request->password], $remember )) {
            $active = Auth::merchant()->get()->active;
            
            if ($active == -1) {
                Auth::merchant()->logout();
                return back()->withErrors('Tài khoản của bạn chưa được kích hoạt bằng Email. Vui lòng vào Email để xác nhận tài khoản')->withInput();
            } elseif ($active == 3) {
                Auth::merchant()->logout();
                return back()->withErrors('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Admin để được trợ giúp')->withInput();
            } else if ($active == 0 || $active == 1 || $active == 2) {
                return redirect('merchant');
            } else {
                Auth::merchant()->logout();
                return back()->withErrors('Tài khoản không thể hoạt động. Vui lòng liên hệ Admin')->withInput();
            }
        } else {
            Auth::merchant()->logout();
            return back()->withErrors('Sai tên tài khoản hoặc mật khẩu. Vui lòng thử lại')->withInput();
        }
    }


    //FOR MANAGE
    //Get View Page admin
    public function getManage()
    {
        return view('auth.manage-login');
    }

    //Excute post register admin
    public function postManage(Request $request)
    {
        $remember = (Input::has('remember')) ? true : false;
        if ( Auth::store()->attempt(['user_name' => $request->email, 'password' => $request->password], $remember )) {

            //Check Merchant Active
            $getMerchantIdByStoreId = Store::select('merchants_id')->where('user_name' , $request->email)->first()->merchants_id;
            $infoMerchant = Merchant::where('id' , $getMerchantIdByStoreId)->first()->active;

            if ($infoMerchant == 1) {
                $active = Auth::store()->get()->active;
                if ($active == 1) {
                    return redirect('manage');
                } else {
                    Auth::store()->logout();
                    return back()->withErrors('Của hàng chưa được kích hoạt. Vui lòng vào Cấu hình thẻ Merchant để kích hoạt tài khoản')->withInput();
                }
            } else {
                Auth::store()->logout();
                return back()->withErrors('Thương hiệu chưa được kích hoạt. Vui lòng liên hệ với Merchant hoặc liên hệ với chúng tôi để được trợ giúp')->withInput();
            }

        } else {
            Auth::store()->logout();
            return back()->withErrors('Tài khoản hoặc mật khẩu của bạn sai hoặc có thể chưa được thiết lập. Vui lòng thử lại')->withInput();
        }
    }


    //FOR ADMIN
    //Get View Page admin
    public function getAdmin()
    {
        return view('auth.admin-login');
    }

    //Excute post register admin
    public function postAdmin(Request $request)
    {
        $remember = (Input::has('remember')) ? true : false;

        if ( Auth::admin()->attempt(['email' => $request->email, 'password' => $request->password],  $remember )) {

            $active = Auth::admin()->get()->status;
            if ( $active == 1 ) {
                return redirect('admincp');
            } else {
                Auth::admin()->logout();
                return back()->withErrors('Tài khoản Admin đã bị vô hiệu hóa')->withInput();
            }
        } else {
            Auth::store()->logout();
            return back()->withErrors('Sai tên tài khoản hoặc mật khẩu. Vui lòng thử lại');
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    //get active account
    public function getActiveAccount($token) {
        Auth::merchant()->logout();
        $getUserActive = Merchant::where('active_token', $token)->first();
        if ($getUserActive == null) {
            // return '404';
            return redirect('register')->withErrors('Link không tồn tại hoặc đã được kích hoạt. Vui lòng kiểm tra lại');
        } else {
            Merchant::where('active_token', $token)->update(array(
                    'active_token'  => 'confirmed',
                    'active'        => 0,
                ));
            $html = 'emails.2';
            sendEmailFromMandrill('[AbbyCard] - Welcome to AbbyCard', $getUserActive->email, $getUserActive->email, $html, []);

            return redirect('login')->withSuccess('Kích hoạt tài khoản thành công. Vui lòng đăng nhập');
        }
    }


    public function getLogout(){
        Auth::merchant()->logout();
        return redirect('login')->withSuccess('Đăng xuất tài khoản Merchant thành công');
    }

    public function getLogoutAdmin() {
        Auth::admin()->logout();
        return redirect('login/admin')->withSuccess('Đăng xuất tài khoản Admin thành công');
    }

    public function getLogoutManage() {
        Auth::store()->logout();
        return redirect('login/manage')->withSuccess('Đăng xuất tài khoản thu ngân thành công');
    }

}
