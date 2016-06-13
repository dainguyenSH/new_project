<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Customer;
use App\OptionValue;
use App\Deal;
use App\merchant;
use View;
use Route;
use Auth;
use Response;
use App\CustomerMerchant;
use App\Notification;
use Redirect;

class MessagesController extends Controller
{

    public function __construct(){
        
        $this->middleware('merchant');
        
        $titlePage = 'Gửi tin nhắn - AbbyCard';
        $className = substr(__CLASS__,21);
        $actionName = substr(strrchr(Route::currentRouteAction(),"@"),1);
        View::share(array(
            'titlePage' => $titlePage,
            'className' => $className,
            'actionName' => $actionName,
        ));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // dd("da vao");


        if ( Auth::merchant()->get()->package == 0 ) {
            return redirect()->action('InitializeCardController@index')->withMessages([
                'title'     => 'Bạn chưa thể thực hiện chức năng này',
                'messages'  => 'Vui lòng hoàn thành bước Khởi tạo thẻ trước khi Gửi tin nhắn cho thành viên',
            ]);
        } else {
        

            if(Auth::merchant()->get()) {
                $package = $this->getMerchantPackage();

        
                //Lay thong tin deal max cua user hien tai
                $message_max = $this->getMessageMax($package);
                //Lay so luong deal da tao trong thang hien tai
                $message_of_month = $this->getCountMessageOfMonth();
                // dd($message_of_month);
 
        
                $merchant_id = Auth::merchant()->get()->id;

                $info_last_notification = Notification::getLastNotification($merchant_id);
                // dd($info_last_notification['content']);
                $info = Notification::getMessageInfo($merchant_id);

                foreach ($info as $key => $value) {
                    # code...
                    if ($value['percentage'] >= 0){
                        $value['string_percentage'] = " + ".abs($value['percentage'])." % ";
                    } else {
                        $value['string_percentage'] = " - ".abs($value['percentage'])." % ";
                    }
                }

                $info_deals = Deal::getDealActive($merchant_id);
                // dd($info_deals);
                $count_customer = Customer::countCustomerActive($merchant_id);

                return view('merchant.messages', array(
                    "infos" => $info, 
                    "deals" => $info_deals,
                    "count_customer" => $count_customer,
                    
                ));
                
            } else {
                return redirect('/login');
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPercentage($last_count, $current_count) {
        $temp = ($current_count - $last_count) / $last_count;
        $temp = round($temp, 2);
        return intval($temp*100);
    }

    public function store(Request $request)
    {

        $checkMaxMessage = OptionValue::find(Auth::merchant()->get()->package);
        if (Auth::merchant()->get()->active == 2) {
            return response()->json([
                'success'   => 'dialog',
                'title'     => 'Tài khoản chưa được kích hoạt',
                'messages'  => "Tài khoản của bạn chưa được kích hoạt nên không thể sử dụng chức năng này. Vui lòng chờ kích hoạt hoặc liên hệ với nhân tư vấn dịch vụ tại số điện thoại <span class='pink'>0462592111</span>",
            ]);
        } else if (json_decode($checkMaxMessage->info,true)['messages_max'] == 0) {
            return response()->json([
                'success'   => 'dialog',
                'title'     => 'Bạn đang dùng gói Miễn phí',
                'messages'  => 'Bạn đang dùng gói Miễn phí. Vui lòng nâng cấp lên gói Premium để được sử dụng chức năng này',
            ]);
        } else {

            $data = $request->all();
            // dd($data);
            $merchant_id = Auth::merchant()->get()->id;

            $package = $this->getMerchantPackage();
            //Lay thong tin deal max cua user hien tai
            $message_max = $this->getMessageMax($package);
            //Lay so luong deal da tao trong thang hien tai
            $message_of_month = $this->getCountMessageOfMonth();

            if($message_of_month < $message_max) {
                $data_merchant = Merchant::where('id',$merchant_id)->first();
                // dd($data_merchant->name);
                $data_customer = Customer::getActiveAcountInfo($merchant_id);
                $count_customer = Customer::countCustomerActive($merchant_id);

                if($count_customer > 0) {
                    //dem so luot gui thanh cong lan truoc //chi lay nhung thanh la nontransation, fixed
                    $last_count_customer = Notification::getLastNotification($merchant_id);
                    // dd($last_count_customer['count']);
                    if($last_count_customer == null){
                        $percentage = 0;
                    } else {
                        $percentage = $this->getPercentage(intval($last_count_customer['count']), intval($count_customer));
                    
                    }

                    // dd($percentage);
                    // dd($count_customer);
                    $dataMessage = array(
                        "merchants_id" => $merchant_id,
                        "deals_id" => $data['deal_id'],
                        "content" => ucfirst($data['content']),
                        "count" => $count_customer,
                        "percentage" => $percentage,
                    );
                    // dd($dataMessage);

                    $device_tokensAndroid = [];
                    $device_tokensIos = [];
                    foreach ($data_customer as $key => $value) {
                        if (Customer::getDevice($value['device_token']) == 2) {
                            array_push($device_tokensAndroid,$value['device_token']);
                        } else {
                            array_push($device_tokensIos,$value['device_token']);
                        }
                    }

                    if ($device_tokensAndroid) {
                        $device_tokensAndroid = array_chunk($device_tokensAndroid, 100);
                    }

                    if ($device_tokensIos) {
                        $device_tokensIos = array_chunk($device_tokensIos, 100);
                    }


                    $result = new Notification;
                    $result->merchants_id = $dataMessage['merchants_id'];
                    $result->deals_id = ($dataMessage['deals_id'] == 0) ? null : $dataMessage['deals_id'];
                    // dd(($dataMessage['deals_id'] == 0) ? null : $dataMessage['deals_id']);
                    $result->content = $dataMessage['content'];
                    $result->count = $dataMessage['count'];
                    $result->percentage = $dataMessage['percentage'];
                    
                    $result->save();

                    
                    if ($result->save()) {

                        //lay tat ca cac customer cua merchant va update lai
                        $list = CustomerMerchant::where('merchants_id',$merchant_id)->where('status',1)->get();
                        $arrID = array();
                        if ($list != null) {
                            foreach ($list as $key => $xx) {
                                array_push($arrID, $xx->customers_id);
                            }
                        }
                        // dd($arrID);
                        if (count($arrID)) {
                            foreach ($arrID as $key => $id) {
                                $u = Customer::find($id);
                                if($u) {
                                    $count = $u->count_notifications;
                                    Customer::where('id',$id)->update(array(
                                        'count_notifications' => $count+1
                                    ));
                                }
                                // dd($u->count_notifications." va ".$id);
                                
                            }
                            
                        }

                        // $count = 0;
                        $notify_data = Notification::getLastNotification($merchant_id);
                        // dd($notify_id['id']);

                        // Push notification for ANDROID
                        if ($device_tokensAndroid) {
                            $message = array(
                                'notification' => array(
                                    "id" => $result->id,
                                    "content" => ucfirst($data['content']),
                                    "sendmsg" =>($result->history_achievements_id == null) ?'none' : 'show',
                                    "name" => $data_merchant->name,
                                    "logo" => $data_merchant->logo,
                                    "created_at"=> calculateTimeAgo(strtotime($result->created_at)),
                                    "type" => null,
                                    "change_points" => 0,
                                    // nếu tồn tại dealsID => đây là notification thuộc loại non-transaction
                                    "dealsID" =>  $result->deals_id,
                                ),
                                "parent" => "gcm"
                            );

                            foreach ($device_tokensAndroid as $key => $value) {
                               
                                pushNotificationForAndroid($message, $value);
                            }
                        }
                        // Push notification for IOS

                        // $alert = ucfirst($data['content']);
                        // $response = array(
                        //     'notification' => array(
                        //         'id' => $result->id,
                        //         'content' =>ucfirst($data['content']) , 
                        //         'name' => $data_merchant->name, 
                        //         'logo' => $data_merchant->logo, 
                        //         'created_at' => calculateTimeAgo(strtotime($result->created_at)), 
                        //         'type' =>null, 
                        //         'change_points' => 0, 
                        //         'dealsID' => $result->deals_id)
                        //     );
                    
                        // pushNotificationForIOS($alert, $response, ['a89692878f1d485f932b511dffae4dc50fbf722993264ae6bd113ce309da3ae9aa39a6e87889f727'],true);
                        if ($device_tokensIos) {
                            $alert = $data_merchant->name .': '.ucfirst($data['content']);
                            $response = array(
                                'notification' => array(
                                    'id' => $result->id,
                                    'content' =>ucfirst($data['content']) , 
                                    'name' => $data_merchant->name, 
                                    'logo' => $data_merchant->logo, 
                                    'created_at' => calculateTimeAgo(strtotime($result->created_at)), 
                                    'type' =>null, 
                                    'change_points' => 0, 
                                    'dealsID' => $result->deals_id
                                )
                            );

                            
                            foreach ($device_tokensIos as $key => $value) {
                                
                                pushNotificationForIOS($alert, $response, $value);
                            }
                        }

                        return Response::json([
                            'success'   => true,
                            'priority'  => 'Gửi tin nhắn thành công',
                            'messages'  => 'Đã gửi tin nhắn thành công tới '.$count_customer.' khách hàng'
                        ]);
                    } else {
                        return Response::json([
                            'success'   => false,
                            'priority'  => 'Gửi tin nhắn thất bại',
                            'messages'  => 'Đã có lỗi xảy ra. Vui lòng thử lại sau'
                        ]);
                    }
                } else {
                    return Response::json([
                        'success'   => false,
                        'priority'  => 'Gửi tin nhắn thất bại',
                        'messages'  => 'Bạn chưa có thành viên để gửi tin nhắn'
                    ]);
                }

                
            } else {
                return Response::json([
                    'success'   => false,
                    'priority'  => 'Vượt quá số lượng tin nhắn', 
                    'messages'  => 'Bạn đã vượt quá số lượng tin nhắn được gửi trong tháng này. Vui lòng nâng cấp gói dịch vụ để gửi được thêm tin nhắn.'
                ]);
            }

            
        }

    }

    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function getCountMessageOfMonth() {
        return Notification::whereRaw('MONTH(CAST(created_at as date)) = MONTH(NOW()) AND YEAR(CAST(created_at as date)) = YEAR(NOW()) and history_achievements_id is NULL and merchants_id = '.Auth::merchant()->get()->id)->count();
        
    }

    public function getMerchantPackage() {
        $id = Auth::merchant()->get()->id;
        $merchant_data = Merchant::where('id','=',Auth::merchant()->get()->id)->get();
        // $merchant_id = Merchant::where('id','=',1)->get();
        $package = $merchant_data[0]['package']; // getPackage of Merchant
        return $package;
        // return $option_info;
        
    }

    public function getMessageMax($package) {
        $option_data = OptionValue::where('id','=',$package)->get();
        $option_info = $option_data[0]['info'];
        $option = json_decode($option_info, true);
        return $option["messages_max"]; 
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
