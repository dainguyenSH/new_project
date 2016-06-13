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
use Mail;
use Auth;
use Hash;
use Session;

class RegisterController extends Controller
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
        return view('auth.register-merchant');
        // return view('auth.register');
    }

    public function getMerchant() {
    }

    public function postMerchant(Request $request) {
        $validator = Validator::make(
                [
                    'name'          => $request->name,
                    'email'         => $request->email,
                    'password'      => $request->password,
                    'repassword'    => $request->repassword,
                ],
                [
                    'name'          => 'required',
                    'email'         => 'required|unique:merchants,email|email',
                    'password'      => 'required|min:6',
                    'repassword'    => 'required|same:password'
                ],
                [
                    'name.required'     => "Họ tên không được để trống",
                    'email.required'    => "Email không được để trống",
                    'email.unique'      => "Địa chỉ email này đã được đăng ký trên hệ thống. Vui lòng kiểm tra lại thông tin",
                    'email.email'       => "Không đúng định dạng Email",
                    'password.required' => "Mật khẩu không được để trống",
                    'password.min'      => "Mật khẩu phải có ít nhất 6 ký tự",
                    'repassword.required'   => "Xác nhận mật khẩu không đúng",
                    'repassword.same'   => "Xác nhận mật khẩu không đúng",

                ]
        );
        if ($validator->fails()) {
            return back()->withErrors($validator->messages())->withInput();
        }
        else {
            $merchant = new Merchant;
            $merchant->user_name    = $request->email;
            $merchant->full_name    = $request->name;
            $merchant->email        = $request->email;
            $merchant->password     = Hash::make($request->password);
            $merchant->active_token = str_random(60);
            $merchant->active       = -1; //not active //Active mail update to 0

            if ($merchant->save()) {
                $data = array();
                $data['name']   = $request->name;
                $data['email']  = $merchant->email;
                $data['slug']   = URL('active'. '/' . $merchant->active_token);

                $html = 'emails.1';
                $sendMail = sendEmailFromMandrill('Xác nhận hòm Mail', $request->email, $request->name, $html, ['data'=>$data]);
                
                if ($sendMail[0]['status'] == 'sent') {
                    return back()->withMessages([
                        'title'     => 'Xác nhận đăng ký',
                        'messages'  => "Vui lòng kiểm tra hòm mail <span class='pink'>". $request->email ."</span> để xác nhận đăng ký tài khoản",
                    ]);
                } else {
                    return back()->withMessages([
                        'title'     => 'Hệ thống gửi Email lỗi',
                        'messages'  => "Vui lòng liên hệ Admin để được kích hoạt Email. Xin cảm ơn",
                    ]);
                }

                // Mail::send('emails.verify-account', ['data' => $data], function ($m) use ($data) {
                //     $m->to($data['email'], $data['name'])->subject('[AbbyCard] Kích hoạt tài khoản');
                // });
                // return back()->withSuccess("Vui lòng vào Email bạn vừa đăng ký để xác nhận tài khoản");
                
            } else {
                return back()->withErrros('Tạo tài khoản thất bại');
            }

        }
    }

    

}
