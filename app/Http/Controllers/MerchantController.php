<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;
use App\OptionValue;
use App\Merchant;
use App\Store;
use App\RegisterPackage;
use App\HistoryAchievement;
use App\MerchantFeedbackAdmin;
use View;
use Route;
use Auth;
use Hash;
use Redirect;

class MerchantController extends Controller
{
    public function __construct(){
        
        $this->middleware('merchant');
        
        $titlePage = 'AbbyCard - Tất cả thẻ thành viên trong 1 ứng dung duy nhất!';
        $className = substr(__CLASS__,21);
        $actionName = substr(strrchr(Route::currentRouteAction(),"@"),1);
        View::share(array(
            'titlePage' => $titlePage,
            'className' => $className,
            'actionName' => $actionName,
        ));
    }

    public function phaitest() {
        return view('emails.11');
        
        // $html = 'emails.11';
        // $sendMail = sendEmailFromMandrill('Xác nhận hòm Mail', 'nguyenphai.cntt@gmail.com', 'PhaiNV', $html, []);
        // return $sendMail;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('merchant.index');
    }

    public function feedBacksMerchant(Request $request) {
        if (isset(Auth::merchant()->get()->id)) {
            $result = MerchantFeedbackAdmin::create([
                'merchants_id'  => Auth::merchant()->get()->id,
                'messages'      => $request->message
            ]);

            if ( $result != null ) {
                return response()->json([
                    'success'   => true,
                    'messages'  => 'Chúng tôi đã nhận được phản hồi của quý khách. Chúng tôi sẽ liên hệ lại với quý khách sớm nhất có thể',
                    'priority'  => 'success',
                ]);
            } else {
                return Response::json([
                    'success'   => false,
                    'priority'  => 'danger',
                    'messages'  => 'Có lỗi. Vui lòng thử lại'
                ]);
            }
        }
    }
    public function changePasswordMerchant(Request $request){
        $merchant_id = Auth::merchant()->get()->id;
        $data = $request->all();
        // dd($data);
        // $old_password = Hash::make('plain-text',$data['old_password']);
        $new_password = Hash::make($data['new_password']);
        $merchant_data = Merchant::getMerchantById($merchant_id);

        
       

        if(Hash::check($data['old_password'],$merchant_data['password'] )){
            // dd("Khac 0");
            $data_change['password'] = $new_password;
            Merchant::where('id',$merchant_id)->update($data_change);
            return Response::json([
                'success'   => true,
                'priority'  => 'success',
                'messages'  => 'Đổi mật khẩu thành công. Vui lòng đóng hộp thoại này để lưu thay đổi',
                "logout" => URL('logout')
            ]);
            
        } else {
            // dd(strcmp($old_password, $merchant_data['password']));
            
            return Response::json([
                'success'   => false,
                'priority'  => 'warning',
                'messages'  => 'Mật khẩu cũ không đúng. Vui lòng kiểm tra lại',
                
            ]);
        }

        return true;
    }

    
    public function upgradePackages(Request $request) {
        $idPackage = $request->input('package');
        return view('merchant.upgrade', [
            'idPackage' => $idPackage,
        ]);
    }

    public function budget(Request $request) {
        if ( $request->idPackage ) {
            $data = OptionValue::where('id', $request->idPackage)->first();
            $budget = json_decode($data->info,true)['budget'];
            if ( $data != null ) {
            return Response::json([
                    'success'   => true,
                    'budget'    => $budget,
                ],200);
            }
        }
    }

    /**
     * Save request Upgrade
     */

    public function upgradePackageRequest(Request $request) {
        $packageRequest = intval( $request->packages );
        $allPackages = OptionValue::where('options_id' , 2)->whereNotIn('id' , [ Auth::merchant()->get()->package ])->get();

        $inPackages = [];
        foreach ($allPackages as $row) {
            array_push($inPackages, $row->id);
        }

        if ( in_array($packageRequest, $inPackages) ) {

            $infoStoreChange = OptionValue::find($packageRequest)->info;
            $maxStorePackageChange = json_decode($infoStoreChange)->stores_max;

            $nowStoreActive = Store::where('merchants_id' , Auth::merchant()->get()->id)->where('active' , 1)->count();

            //Check count store

            if ( $nowStoreActive <= $maxStorePackageChange ) {
                if ( $packageRequest == Auth::merchant()->get()->package_status ) {
                    return response()->json([
                            'success'   => false,
                            'title'     => 'Chúng tôi đã nhận được yêu cầu',
                            'messages'  => 'Chúng tôi đã nhận được yêu cầu chuyển đổi gói dịch vụ này. Vui lòng chờ chúng tôi xử lý ',
                        ]);
                } else {
                    $result = RegisterPackage::create([
                        'merchants_id' =>   Auth::merchant()->get()->id,
                        'package'   =>      $packageRequest,
                        'month'     =>      intval( $request->time ),
                        'name'      =>      $request->fullname,
                        'mobile'    =>      $request->phone,
                        'email'     =>      $request->email,
                        'content'   =>      $request->content,
                    ]);
                    if ($result != null) {

                        $resultUpgrade = Merchant::where('id', Auth::merchant()->get()->id)->update([
                                'package_status' => $request->packages,
                            ]);
                        if ($resultUpgrade != null) {

                            $data = array();
                            $data['name']       = json_decode(Auth::merchant()->get()->information,true)['fullname'];
                            $data['package']    = checkPackageEmail($request->packages);
                            $data['trademark']  = Auth::merchant()->get()->name;
                            $data['email']      = Auth::merchant()->get()->email;

                            $html = 'emails.5';
                            $sendMail = sendEmailFromMandrill('Gửi yêu cầu kích hoạt thành công gói dịch vụ '.$data['package'], $request->email, $data['name'], $html, ['data'=>$data]);

                            return response()->json([
                                'success'   => 'dialog',
                                'title'     => 'GỬI THÀNH CÔNG',
                                'messages'  => 'Chúng tôi đã nhận được yêu cầu thay đổi tài khoản. Vui lòng đợi chúng tôi xử lý',
                            ]);
                        } else {
                            return response()->json([
                                'success'   => false,
                                'title'     => 'Lỗi',
                                'messages'  => 'Có lỗi khi yêu cầu nâng cấp tài khoản. Vui lòng thử lại',
                            ]);
                        }
                    }
                }
            } else {
                return response()->json([
                    'success'   => false,
                    'title'     => 'Vượt mức giới hạn',
                    'messages'  => 'Chúng tôi kiểm tra thì số lượng cửa hàng của bạn đang Hoạt động của bạn nhiều hơn số lượng tối đa cho phép của gói mà bạn cần giảm xuống. Vui lòng tạm dừng một số cửa hàng của bạn. Sau đó thử lại sau.',
                ]);
            }

            
        }else {
            return response()->json([
                'success'   => false,
                'title'     => 'Không có gói này',
                'messages'  => 'Chúng tôi hiện tại chưa áp dụng gói dịch vụ này. Nếu bạn cần gói dịch vụ riêng thì liên hệ với Admin để được trợ giúp',
            ]);
        }



        
    }

    /**
     * Transfer History By Merchant
     */

    public function transferHistory()
    {
        $getListStore = Store::where('merchants_id' , Auth::merchant()->get()->id)->get();
        $arrStore = [];


        foreach ($getListStore as $row) {
            array_push($arrStore, $row->id);
        }

        $allTransfersHistory = HistoryAchievement::getAllHistoryOfMerchant($arrStore);
        return view('merchant.transfer-history', array(
                'data' => $allTransfersHistory
            ));
    }
}
