<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Merchant;
use Response;
use Hash;
use Mail;



class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getEmail(){
        return view('auth/reset-password');
    }
    public function postEmail(Request $request) {
        $email = $request->email;
        
        

        // dd($email);
        $check_email = Merchant::where("email","=",$email)->get()->count();

        if($check_email != 0){
            $token = str_random(108);
            $link = URL('password/reset/'.$token); //Link send to email to active
            

            //Xu ly send mail cho nguoi dung de xac nhan. PHAI GUI HO TO EMAIL CHUA LINK NAY DE DOI MAT KHAU NHE
            // dd($link);

                // $sendMail = Mail::send('emails.forgot-password', ['link' => $link], function ($m) use ($email) {
                //     $m->from($email, 'AbbyCard');
                //     $m->to($email, $email)->subject('[AbbyCard] - Khôi phục mật khẩu');
                // });

                $merchant = Merchant::where('email' , $email)->first();

                $data = array();
                $data['link']       = $link;
                $data['name']       = json_decode($merchant->information,true)['fullname'];

                $html = 'emails.7';
                $sendMail = sendEmailFromMandrill('Khôi phục mật khẩu', $email, $merchant->name, $html, ['data'=>$data]);

                if ($sendMail[0]['status'] == 'sent') {
                    $result = Merchant::where('email',$email)->update(array(
                        'password_confirmation' => $token
                    ));
                
                    if ($result == true){
                        return Response::json([
                            'success'   => true,
                            'priority'  => 'THÀNH CÔNG',
                            'messages'  => 'Vui lòng check email của bạn để xác nhận yêu cầu reset mật khẩu'
                        ]);
                    } else {
                        return Response::json([
                            'success'   => true,
                            'priority'  => 'CÓ LỖI XẢY RA',
                            'messages'  => 'Đã xảy ra sự cố. Vui lòng thử lại lần sau'
                        ]);
                    }
                } else {
                    return Response::json([
                            'success'   => true,
                            'priority'  => 'CÓ LỖI XẢY RA',
                            'messages'  => 'Đã xảy ra sự cố. Vui lòng thử lại lần sau'
                        ]);
                }

                
        } else {
            return Response::json([
                'success'   => false,
                'priority'  => 'CÓ LỖI XẢY RA',
                'messages'  => 'Email bạn nhập không tồn tại trên hệ thống. Vui lòng nhập lại'
            ]);
        }
        // dd($email);
    }
    public function getReset($token){
        // dd($token);
        $merchant_data = Merchant::where('password_confirmation',$token)->first();
        if($merchant_data != null){
            return view('auth/confirm-reset-password', array(
                'token_password' => $token
            ));
        } else {
            return redirect('login')->withErrors('Đường dẫn không tồn tại hoặc đã được kích hoạt. Vui lòng thử lại');
        }
        

    }

    public function postReset(Request $request) {
        $data = $request->all();
        
        $merchant = Merchant::where('password_confirmation' , $data['token'])->first();


        $new_password = Hash::make($data['new_password']);
        $data_change['password'] = $new_password;
        $data_change['password_confirmation'] = null;
        $result = Merchant::where("password_confirmation", $data['token'])->update($data_change);
        if($result == true) {

            $data = array();
            $data['name']       = json_decode($merchant->information,true)['fullname'];
            $data['new_password']  = substr($request->new_password, 0, 3)."***********";

            $html = 'emails.8';
            $sendMail = sendEmailFromMandrill('Thay đổi mật khẩu thành công', $merchant->email, $data['name'], $html, ['data'=>$data]);


            return Response::json([
                'success'   => true,
                'priority'  => 'success',
                'messages'  => 'Đổi mật khẩu thành công. Vui lòng đóng hộp thoại này để lưu thay đổi',
                "logout" => URL('logout')
            ]);
        }
    }
    
}
