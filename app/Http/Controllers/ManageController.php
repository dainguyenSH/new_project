<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Response;
use App\Http\Controllers\Controller;
use App\Merchant;
use App\Customer;
use App\CustomerMerchant;
use App\HistoryAchievement;
use App\Store;
use View;
use Route;
use Auth;
use App\Notification;
use Redirect;

class ManageController extends Controller
{
    public function __construct(){

        $this->middleware('manage');

        $titlePage = 'Quản trị thu ngân - AbbyCard';
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
    public function getIndex()
    {
        $id = Auth::store()->get()->id;
        $currentDate = substr(date('Y-m-d H:i:s'),0,-8);
        $info = HistoryAchievement::getAllHistoryAchievementByCustomerId($id, $currentDate);
        return view('manage.index', array(
            'data' => $info
        ));
    }

    // Tìm kiếm khách hàng tích điểm
    public function postExcutesearch(Request $request) {
  
        $storeId = Auth::store()->get()->id;
        $infoStore = Store::where('id' , $storeId)->first();
        $getMerchantId = $infoStore->merchants_id;

        $getValueConfig = Merchant::find($getMerchantId);
        $data = json_decode($getValueConfig->card_info,true);
        $point = $data['value'][0]['value'][0]['unit'];
        $chops = $data['value'][0]['value'][0]['unit'];

        $customerInfo = Customer::getAcountInfoByID($getMerchantId, $request->keywords);

        if ($customerInfo == null) {
            $htmlBoxInfo = "<center><div class='alert alert-info' role='alert'> Xin lỗi chúng tôi không tìm thấy khách hàng nào có mã <strong class='pink'>".$request->keywords."</strong></div></center>";
            return Response::json([
                    'success'   => false,
                    'message'   => 'Xin lỗi không tìm thấy khách hàng',
                    'append'    => $htmlBoxInfo,
                ]);
        } else {
            $checkStoreType = Merchant::select('card_type')->where('id' , $getMerchantId)->first();

            if ( $customerInfo->status == 0 ) {
                $htmlBoxInfo = "<center><div class='alert alert-info' role='alert'> Khách hàng <strong class='pink'>".$customerInfo->name."</strong> đã bỏ theo dõi thương hiệu của bạn.</div></center>";
                return Response::json([
                        'success'   => false,
                        'message'   => 'Khách hàng đã bỏ theo dõi thương hiệu',
                        'append'    => $htmlBoxInfo,
                    ]);
            } else if ( $customerInfo->status == 1 ) {

                if ( $checkStoreType->card_type == 3 ) {

                    $getValueConfig = Merchant::find($getMerchantId);
                    $data = json_decode($getValueConfig->card_info,true);
                    $point = $data['value'][0]['value'][0]['unit'];
                    
                    $htmlBoxInfo = View::make('html.box-info-merchant' , array(
                        'info'      => $customerInfo,
                    ))->render();
                    return Response::json([
                        'success'   => true,
                        'append'    => $htmlBoxInfo,
                        'point'     => $point,
                        'types'     => 3,
                    ]);

                } elseif ( $checkStoreType->card_type == 4 ) {

                    $getValueConfig = Merchant::find($getMerchantId);
                    $data = json_decode($getValueConfig->card_info,true);
                    $chops = $data['value'][0]['value'][0]['unit'];
                    
                    $htmlBoxInfo = View::make('html.box-info-merchant-chops' , array(
                            'info'      => $customerInfo,
                        ))->render();
                    return Response::json([
                        'success'   => true,
                        'append'    => $htmlBoxInfo,
                        'chops'     => $customerInfo->current_point,
                        'config'    => $chops,
                        'types'     => 4,
                    ]);

                } else {
                    return Response::json([
                            'success'   => false,
                            'append'    => 'Có lỗi xảy ra. Vui lòng thử lại hoặc liên hệ với Admin để được trợ giúp',
                        ]);
                }
            } else {
                return Response::json([
                        'success'   => false,
                        'append'    => 'Có lỗi xảy ra. Vui lòng thử lại hoặc liên hệ với Admin để được trợ giúp',
                    ]);
            }
        }
    }

    // TÌm kiếm khách hàng đổi điểm
    public function postExcuteSearch2(Request $request) {
        
        $storeId = Auth::store()->get()->id;
        $infoStore = Store::where('id' , $storeId)->first();
        $getMerchantId = $infoStore->merchants_id;

        $getValueConfig = Merchant::find($getMerchantId);
        $data = json_decode($getValueConfig->card_info,true);
        $point = $data['value'][0]['value'][0]['unit'];
        $chops = $data['value'][0]['value'][0]['unit'];

        $customerInfo = Customer::getAcountInfoByID($getMerchantId, $request->keywords);

        //Check exist customer
        if ( $customerInfo == null ) {
            $htmlBoxInfo = "<center><div class='alert alert-info' role='alert'> Xin lỗi chúng tôi không tìm thấy khách hàng nào có mã <strong class='pink'>".$request->keywords."</strong></div></center>";
            return Response::json([
                    'success'   => false,
                    'message'   => 'Xin lỗi không tìm thấy khách hàng',
                    'append'    => $htmlBoxInfo,
                ]);
        } else {
            // If isset Customer
            if ( $customerInfo->status == 0 ) {
                $htmlBoxInfo = "<center><div class='alert alert-info' role='alert'> Khách hàng <strong class='pink'>".$customerInfo->name."</strong> đã bỏ theo dõi thương hiệu của bạn.</div></center>";
                return Response::json([
                        'success'   => false,
                        'message'   => 'Khách hàng đã bỏ theo dõi thương hiệu',
                        'append'    => $htmlBoxInfo,
                    ]);
            } elseif ( $customerInfo->status == 1 ) {
                
                $checkStoreType = Merchant::select('card_type')->where('id' , $getMerchantId)->first();
                if ( $checkStoreType->card_type == 3 ) {

                    $htmlBoxInfo = View::make('html.discount-level' , array(
                            'info'      => $customerInfo,
                        ))->render();
                    return Response::json([
                            'success'   => true,
                            'append'    => $htmlBoxInfo,
                            'point'     => $point,
                            'types'     => 3,
                        ]);

                } elseif ( $checkStoreType->card_type == 4 ) {

                    $htmlBoxInfo = View::make('html.discount-chops' , array(
                            'info'      => $customerInfo,
                            'config'    => $data,
                        ))->render();
                    return Response::json([
                            'success'   => true,
                            'append'    => $htmlBoxInfo,
                            'chops'     => $customerInfo->current_point,
                            'types'     => 4,
                        ]);

                } else {
                    return Response::json([
                            'success'   => false,
                            'append'    => 'Có lỗi xảy ra. Vui lòng thử lại hoặc liên hệ với Admin để được trợ giúp',
                        ]);
                }

            } else {
                return Response::json([
                    'success'   => false,
                    'append'    => 'Có lỗi xảy ra. Vui lòng thử lại hoặc liên hệ với Admin để được trợ giúp',
                ]);
            }
        }
    }
    // End đổi điểm


    //Nhập điểm cho khách hàng
    public function postStorepoint(Request $request) {

        $storeId = Auth::store()->get()->id;
        $infoStore = Store::where('id' , $storeId)->first();
        $getMerchantId = $infoStore->merchants_id;

        $getValueConfig = Merchant::find($getMerchantId);
        $data = json_decode($getValueConfig->card_info,true);
        $setPoint = $data['value'][0]['value'][0]['unit'];
        if (!$setPoint) {
            return Response::json([
                'success'       => 'dialog',
                'title'         => 'Chưa cấu hình thẻ',
                'messages'      => 'Vui lòng cấu hình công thức tích điểm. Hoặc liên hệ Admin để được hỗ trợ',
                'priority'      => 'danger',
            ]);
        } else {

            $pointChange = floor($request->point_change / $setPoint);

            if ( $pointChange >= 1 ) {
                $storeId = Auth::store()->get()->id;
                $infoStore = Store::where('id' , $storeId)->first();
                $getMerchantId = $infoStore->merchants_id;

                $customerInfo = CustomerMerchant::where('customers_id' , $request->id)->where('merchants_id' , $getMerchantId)->first();
                if ( $customerInfo != null ) {

                    $totalPoint = $customerInfo->point + $pointChange;
                    $currentPoint = $customerInfo->current_point +  $pointChange;
                    $level = getLevel($totalPoint, $data);

                    $result = CustomerMerchant::where('customers_id' , $request->id)->where('merchants_id' , $getMerchantId)->update(array(
                            'point'         => $totalPoint,
                            'current_point' => $currentPoint,
                            'level'         => $level,
                        ));

                    if ($result != null) {
                        $tran = HistoryAchievement::create(array(
                                'customers_id'      => $request->id,
                                'stores_id'         => $storeId,
                                'order_id'          => $request->order_id,
                                'type'              => 2,
                                'change_points'     => $pointChange,
                            ));

                        $noti = Notification::create(array(
                            'merchants_id'=> $getMerchantId,
                            'history_achievements_id' => $tran->id,
                            'count' => Customer::countCustomerActive($getMerchantId),
                            'content' => $getValueConfig->name .' tặng bạn '. $pointChange . ' điểm thưởng.',
                            'type' => 2
                        ));

                        $customer = Customer::find($request->id);
                        $countNotification = $customer->count_notifications;
                        Customer::where('id',$request->id)->update(array(
                            'count_notifications' => intval($countNotification) + 1
                        ));

                        //push notification Android
                        if ($customer->device_type == 2) {

                            $message = array(
                                'notification' => array(
                                    "id" => $noti->id,
                                    "content" => $noti->content,
                                    "sendmsg" =>'show',
                                    "name" => $getValueConfig->name,
                                    "logo" => $getValueConfig->logo,
                                    "created_at"=> calculateTimeAgo(strtotime($noti->created_at)),
                                    "type" => 2,
                                    "change_points" => $pointChange,
                                    // nếu tồn tại dealsID => đây là notification thuộc loại non-transaction
                                    "dealsID" => null,
                                ),
                                "parent" => "gcm"
                            );
                            pushNotificationForAndroid($message, [$customer->device_token]);
                        }

                        //push notification IOS
                        else if ($customer->device_type == 1) {
                            $alert = $noti->content;
                            $response = array(
                                'notification' => array(
                                    'id' => $noti->id,
                                    'content' =>$noti->content,
                                    'name' => $getValueConfig->name, 
                                    'logo' => $getValueConfig->logo, 
                                    'created_at' => calculateTimeAgo(strtotime($noti->created_at)),
                                    'type' =>2, 
                                    'change_points' => $pointChange, 
                                    'dealsID' => null)
                                );
                            pushNotificationForIOS($alert, $response, [$customer->device_token]);
                        }

                        return Response::json([
                            'success'       => true,
                            'messages'      => "Nhập điểm thành công",
                            'priority'      => 'success',
                        ]);
                    } else {
                        return Response::json([
                            'success'       => false,
                            'messages'      => 'Nhập điểm thất bại. Vui lòng thử lại',
                            'priority'      => 'danger',
                        ]);
                    }
                } else {
                    return Response::json([
                            'success'       => false,
                            'messages'      => 'Nhập điểm thất bại. Vui lòng thử lại',
                            'priority'      => 'danger',
                        ]);
                }
            } else {
                return Response::json([
                    'success'       => false,
                    'messages'      => 'Số điểm tích lũy cho khách hàng phải lớn hơn 0. Vui lòng thử lại',
                    'priority'      => 'danger',
                ]);
            }
        }
    }   

    //Function này không sử dụng

    //Đổi điểm cho khách hàng là Level
    public function postChangePoint(Request $request) {

        // dd('go change point');
        $storeId = Auth::store()->get()->id;
        $infoStore = Store::where('id' , $storeId)->first();
        $getMerchantId = $infoStore->merchants_id;



        $getValueConfig = Merchant::find($getMerchantId);
        // $data = json_decode($getValueConfig->card_info,true);
        // $setPoint = $data['value'][0]['value'][0]['unit'];

        $pointChange = floor($request->point_change);


        $storeId = Auth::store()->get()->id;
        $infoStore = Store::where('id' , $storeId)->first();
        $getMerchantId = $infoStore->merchants_id;

        $customerInfo = CustomerMerchant::where('customers_id' , $request->id)->where('merchants_id' , $getMerchantId)->first();
        if ( $customerInfo != null ) {

            // $totalPoint = $customerInfo->point + $pointChange;

            $currentPoint = $customerInfo->current_point;
            if ( $currentPoint < $pointChange ) {
                return Response::json([
                    'success'       => false,
                    'messages'      => 'Số điểm bạn đổi vượt quá mức điểm tích lũy. Vui lòng thử lại' ,
                    'priority'      => 'danger',
                ]);
            } else {
                $pointAfterChange = $customerInfo->current_point - $pointChange;
                $result = CustomerMerchant::where('customers_id' , $request->id)->where('merchants_id' , $getMerchantId)->update(array(
                        // 'point'         => $totalPoint,
                        'current_point' => $pointAfterChange
                    ));
                // dd($result);
                if ($result != null) {

                   $tran = HistoryAchievement::create(array(
                            'customers_id'      => $request->id,
                            'stores_id'         => $storeId,
                            'order_id'          => $request->order_id,
                            'type'              => 1,
                            'change_points'     => $pointChange,
                        ));

                    $noti = Notification::create(array(
                        'merchants_id'=> $getMerchantId,
                        'history_achievements_id' => $tran->id,
                        'count' => Customer::countCustomerActive($getMerchantId),
                        'content' => 'Đổi' . $pointChange . 'điểm thành công tại ' . $getValueConfig->name,
                        'type' => 1
                    ));
                    $customer = Customer::find($request->id);
                    $countNotification = $customer->count_notifications;

                    Customer::where('id',$request->id)->update(array(
                        'count_notifications' => intval($countNotification) + 1
                    ));

                    //push notification Android
                    if ($customer->device_type == 2) {

                        $message = array(
                            'notification' => array(
                                "id" => $noti->id,
                                "content" => $noti->content,
                                "sendmsg" =>'show',
                                "name" => $getValueConfig->name,
                                "logo" => $getValueConfig->logo,
                                "created_at"=> calculateTimeAgo(strtotime($noti->created_at)),
                                "type" => 1,
                                "change_points" => $pointChange,
                                // nếu tồn tại dealsID => đây là notification thuộc loại non-transaction
                                "dealsID" => null,
                            ),
                            "parent" => "gcm"
                        );
                        pushNotificationForAndroid($message, [$customer->device_token]);
                    }
                    //push notification IOS
                    else if ($customer->device_type == 1) {
                        $alert = $data['content'];
                        $response = array(
                                        'notification' => array(
                                                    'id' => $noti->id,
                                                    'content' =>$noti->content,
                                                    'name' => $getValueConfig->name, 
                                                    'logo' => $getValueConfig->logo, 
                                                    'created_at' => calculateTimeAgo(strtotime($noti->created_at)),
                                                    'type' =>1, 
                                                    'change_points' => $pointChange, 
                                                    'dealsID' => null)
                                        );
                        pushNotificationForIOS($alert, $response, [$customer->device_token]);
                    }


                    return Response::json([
                        'success'       => true,
                        'messages'      => "Đổi điểm cho khách hàng thành công",
                        'priority'      => 'success',
                    ]);
                } else {
                    return Response::json([
                        'success'       => false,
                        'messages'      => 'Đổi điểm thất bại. Vui lòng thử lại',
                        'priority'      => 'danger',
                    ]);
                }
            }

        } else {
            return Response::json([
                    'success'       => false,
                    'messages'      => 'Đổi điểm thất bại. Vui lòng thử lại',
                    'priority'      => 'danger',
                ]);
        }
    }

    //Đổi điểm cho khách hàng là Chops

    public function postDiscountChop(Request $request) {
        $chopChange = floor($request->chop_change);

        if ($chopChange <= 0 ) {
            return Response::json([
                    'success'       => false,
                    'messages'      => 'Giá trị chops cần đổi phải lớn hơn hoặc bằng 1',
                    'priority'      => 'danger',
                ]);
        } elseif ( $chopChange > 15 ) {
            return Response::json([
                'success'       => false,
                'messages'      => 'Giá trị chops cần đổi phải nhỏ hơn 15',
                'priority'      => 'danger',
            ]);
        } else {

            $storeId = Auth::store()->get()->id;
            $infoStore = Store::where('id' , $storeId)->first();
            $getMerchantId = $infoStore->merchants_id;

            $getValueConfig = Merchant::find($getMerchantId);

            $storeId = Auth::store()->get()->id;

            $customerInfo = CustomerMerchant::where('customers_id' , $request->id)->where('merchants_id' , $getMerchantId)->first();
            if ( $customerInfo != null ) {

                $currentPoint = $customerInfo->current_point;
                if ( $currentPoint < $chopChange ) {
                    return Response::json([
                        'success'       => false,
                        'messages'      => 'Số Chops bạn đổi vượt quá mức điểm tích lũy. Vui lòng thử lại' ,
                        'priority'      => 'danger',
                    ]);
                } else {
                    $config = [];
                    foreach (json_decode($getValueConfig->card_info,true)['value'][0]['value'] as $row) {
                        array_push($config, $row['point']);
                    }
                    
                    if (in_array($chopChange, $config)) {
                        $pointAfterChange = $customerInfo->current_point - $chopChange;
                        $result = CustomerMerchant::where('customers_id' , $request->id)->where('merchants_id' , $getMerchantId)->update(array(
                                // 'point'         => $totalPoint,
                                'current_point' => $pointAfterChange
                            ));
                        if ($result != null) {

                            $tran = HistoryAchievement::create(array(
                                    'customers_id'      => $request->id,
                                    'stores_id'         => $storeId,
                                    'order_id'          => $request->order_id,
                                    'type'              => 4,
                                    'change_points'     => $chopChange,
                                ));

                            $noti = Notification::create(array(
                                'merchants_id'=> $getMerchantId,
                                'history_achievements_id' => $tran->id,
                                'count' => Customer::countCustomerActive($getMerchantId),
                                'content' => 'Đổi '. $chopChange . ' chops thành công tại ' .$getValueConfig->name,
                                'type' => 4
                            ));
                            $customer = Customer::find($request->id);
                            $countNotification = $customer->count_notifications;
                            Customer::where('id',$request->id)->update(array(
                                'count_notifications' =>intval($countNotification) + 1
                            ));

                        //push notification Android
                        if ($customer->device_type == 2) {

                            $message = array(
                                'notification' => array(
                                    "id" => $noti->id,
                                    "content" => $noti->content,
                                    "sendmsg" =>'show',
                                    "name" => $getValueConfig->name,
                                    "logo" => $getValueConfig->logo,
                                    "created_at"=> calculateTimeAgo(strtotime($noti->created_at)),
                                    "type" => 4,
                                    "change_points" => $chopChange,
                                    // nếu tồn tại dealsID => đây là notification thuộc loại non-transaction
                                    "dealsID" => null,
                                ),
                                "parent" => "gcm"
                            );
                            pushNotificationForAndroid($message, [$customer->device_token]);
                        }
                        //push notification IOS
                        else if ($customer->device_type == 1) {
                            $alert = $noti->content;
                            $response = array(
                                            'notification' => array(
                                                        'id' => $noti->id,
                                                        'content' =>$noti->content,
                                                        'name' => $getValueConfig->name, 
                                                        'logo' => $getValueConfig->logo, 
                                                        'created_at' => calculateTimeAgo(strtotime($noti->created_at)),
                                                        'type' =>4, 
                                                        'change_points' => $chopChange, 
                                                        'dealsID' => null)
                                            );
                            pushNotificationForIOS($alert, $response, [$customer->device_token]);
                        }

                            return Response::json([
                                'success'       => true,
                                'messages'      => "Đổi Chops cho khách hàng thành công",
                                'priority'      => 'success',
                            ]);
                        } else {
                            return Response::json([
                                'success'       => false,
                                'messages'      => 'Đổi Chops thất bại. Vui lòng thử lại',
                                'priority'      => 'danger',
                            ]);
                        }
                    } else {
                        return Response::json([
                            'success'       => false,
                            'messages'      => 'Giá trị đổi Chops chưa được cấu hình',
                            'priority'      => 'danger',
                        ]);
                    }
                }

            } else {
                return Response::json([
                    'success'       => false,
                    'messages'      => 'Đổi điểm thất bại. Vui lòng thử lại',
                    'priority'      => 'danger',
                ]);
            }
        }
    }

    // Nhập điểm cho khách hàng là Chop
    public function postStoreChop(Request $request) {

        // $setChop = 100000;

        if ( $request->point_change > 5000000) {
            return Response::json([
                    'success'       => false,
                    'messages'      => 'Bạn đã vượt quá hạn mức giao dịch. Vui lòng chọn giá trị thấp hơn hoặc nhập lại nhiều lần !',
                    'priority'      => 'danger',
                ]);
        } else {

            $storeId = Auth::store()->get()->id;
            $infoStore = Store::where('id' , $storeId)->first();
            $getMerchantId = $infoStore->merchants_id;

            $getValueConfig = Merchant::find($getMerchantId);
            $data = json_decode($getValueConfig->card_info,true);
            if ( !$setChop = $data['value'][0]['value'][0]['unit'] ){
                return Response::json([
                    'success'       => false,
                    'messages'      => 'Chưa cấu hình tích điểm. Vui lòng liên hệ Admin để được trợ giúp',
                    'priority'      => 'danger',
                ]);
            } else {
                $chopChange = floor($request->point_change / $setChop);

                if ( $chopChange != 0 ) {

                    $storeId = Auth::store()->get()->id;
                    $infoStore = Store::where('id' , $storeId)->first();
                    $getMerchantId = $infoStore->merchants_id;

                    $customerInfo = CustomerMerchant::where('customers_id' , $request->id)->where('merchants_id' , $getMerchantId)->first();
                    if ( $customerInfo != null ) {

                        $totalPoint = $customerInfo->point + $chopChange;

                        $currentPoint = $customerInfo->current_point +  $chopChange;

                        $result = CustomerMerchant::where('customers_id' , $request->id)->where('merchants_id' , $getMerchantId)->update(array(
                                'point'         => $totalPoint,
                                'current_point' => $currentPoint
                            ));
                        if ($result != null) {
                            $tran = HistoryAchievement::create(array(
                                    'customers_id'      => $request->id,
                                    'stores_id'         => $storeId,
                                    'order_id'          => $request->order_id,
                                    'type'              => 3,
                                    'change_points'     => $chopChange,
                                ));
                            // dd($tran);
                            $noti = Notification::create(array(
                                'merchants_id'=> $getMerchantId,
                                'history_achievements_id' => $tran->id, 
                                'count' => Customer::countCustomerActive($getMerchantId),
                                'content' => $getValueConfig->name .' tặng bạn ' . $chopChange . ' chops',
                                'type' => 3
                            ));

                            $customer = Customer::find($request->id);
                            $countNotification = $customer->count_notifications;
                            Customer::where('id',$request->id)->update(array(
                                'count_notifications' => intval($countNotification) + 1
                            ));

                            //push notification Android
                            if ($customer->device_type == 2) {

                                $message = array(
                                    'notification' => array(
                                        "id" => $noti->id,
                                        "content" => $noti->content,
                                        "sendmsg" =>'show',
                                        "name" => $getValueConfig->name,
                                        "logo" => $getValueConfig->logo,
                                        "created_at"=> calculateTimeAgo(strtotime($noti->created_at)),
                                        "type" => 3,
                                        "change_points" => $chopChange,
                                        // nếu tồn tại dealsID => đây là notification thuộc loại non-transaction
                                        "dealsID" => null,
                                    ),
                                    "parent" => "gcm"
                                );
                                pushNotificationForAndroid($message, [$customer->device_token]);
                            }
                            //push notification IOS
                            else if ($customer->device_type == 1) {
                                // dd($data);
                                $alert = $noti->content;
                                $response = array(
                                                'notification' => array(
                                                            'id' => $noti->id,
                                                            'content' =>$noti->content,
                                                            'name' => $getValueConfig->name, 
                                                            'logo' => $getValueConfig->logo, 
                                                            'created_at' => calculateTimeAgo(strtotime($noti->created_at)),
                                                            'type' =>3, 
                                                            'change_points' => $chopChange, 
                                                            'dealsID' => null)
                                                );
                                pushNotificationForIOS($alert, $response, [$customer->device_token]);
                            }

                        
                            return Response::json([
                                'success'       => true,
                                'messages'      => "Tích Chops thành công",
                                'priority'      => 'success',
                            ]);
                        } else {
                            return Response::json([
                                'success'       => false,
                                'messages'      => 'Tích Chops thất bại. Vui lòng thử lại',
                                'priority'      => 'danger',
                            ]);
                        }
                    } else {
                        return Response::json([
                                'success'       => false,
                                'messages'      => 'Tích Chops thất bại. Vui lòng thử lại',
                                'priority'      => 'danger',
                            ]);
                    }

                } else {
                    return Response::json([
                        'success'       => false,
                        'messages'      => 'Số Chops tích lũy cho khách hàng phải lớn hơn 0. Vui lòng thử lại',
                        'priority'      => 'danger',
                    ]);
                }
            }   
        }
    }

    
}
