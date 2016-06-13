<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Facebook;
use App\Customer;
use App\Merchant;
use App\Deal;
use App\CustomerMerchant;
use App\Notification;
use App\Store;
use App\OptionValue;
use App\Feedback;
use App\HistoryAchievement;
use App\CrawlingData;
use App\CustomerDeal;

class APIController extends Controller
{
    /**
     * Index page
     */
    function getIndex()
    {
        return 'Nếu là một người dùng bình thường, sẽ không bao giờ truy cập vào đường link này!';
    }

    function getVuiPush()
    {
        $isDev = false;
        $pushIOS = true;
        $pushANDROID = true;

        if ( $isDev ) {
            $message = 'Chào mừng bạn đến với AbbyCard!';
            $deviceTokensIOS = [];
            $deviceTokensANDROID = [];
            foreach (Customer::get() as $customer) {
                if ( $customer->device_type === 1 ) {
                    if ( $pushIOS && $customer->device_token && $customer->device_token !== 'simulator' ) {
                        $deviceTokensIOS[] = $customer->device_token;
                    }
                } elseif ( $pushANDROID && $customer->device_type === 2 ) {
                    $deviceTokensANDROID[] = $customer->device_token;
                }
            }
            // Push notification for IOS
            if ( $deviceTokensIOS ) {
                $response = ['data' => ['key1' => 'value1', 'key2' => 'value2', 'key3' => 'value3']];
                pushNotificationForIOS($message, $response, $deviceTokensIOS);
            }

            // Push notification for ANDROID
            if ( $deviceTokensANDROID ) {
                $data = array(
                    'notification' => array(
                        "id" => 1,
                        "content" => $message,
                        "name" => 'CGV',
                        "logo" => 'images/logo/cgv.png',
                        "created_at"=> '2016-01-22 11:08:50',
                        "type" => 1,
                        "change_points" => 2,
                        "dealsID" => 3
                    ),
                    "parent" => "gcm"
                );
                pushNotificationForAndroid($data, $deviceTokensANDROID);
            }

            return 'ĐÃ BẮN THÀNH CÔNG!';
            
        }


        return 'Nếu là một người dùng bình thường, sẽ không bao giờ truy cập vào đường link này!';
    }

    /**
     * Đăng nhập bằng facebook, trả về thông tin: Tên, Ảnh Đại diện, Email, SDT, Location, Giới tính, Tuổi, và nơi đang làm việc.
     */
    function postLoginWithFacebook(Request $request)
    {
        $param = $request->header('param');
        $param = json_decode($param);
        $accessToken = isset($param->accessToken) ? $param->accessToken : "";

        if ( !$accessToken ) {
            return response()->json(['error' => true, 'message' => 'Tham số accessToken không được phép trống'], 404);
        }

        $deviceToken = $request->input('deviceToken');
        $deviceType = $request->input('deviceType');
        if ( $deviceType && $deviceType == 'APPLE' ) {
            $deviceType = 1;
        } elseif ( $deviceType && $deviceType == 'ANDROID' ) {
            $deviceType = 2;
        } else {
            $deviceType = 0;
        }

        $fb = new Facebook\Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => env('FACEBOOK_GRAPH_VERSION'),
        ]);

        try {
            $response = $fb->get('me?fields=birthday,email,location,work,gender,name,picture.type(large)', $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            return response()->json(['error' => true, 'message' => 'Graph returned an error: ' . $e->getMessage()], 401);
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            return response()->json(['error' => true, 'message' => 'Facebook SDK returned an error: ' . $e->getMessage()], 401);
        }

        $user = json_decode($response->getGraphUser());

        $data['facebook_id'] = isset($user->id) ? $user->id : '';
        $data['name'] = isset($user->name) ? $user->name : '';
        $data['avatar'] = isset($user->picture) ? $user->picture->url : '';
        $data['email'] = isset($user->email) ? $user->email : '';
        // $data['phone'] = '0966235100';
        $data['location'] = isset($user->location) ? $user->location->name : '';
        $data['gender'] = isset($user->gender) ? $user->gender : '';
        $data['birthday'] = isset($user->birthday) ? $user->birthday : '';
        $data['work'] = isset($user->work) ? $user->work[0]->employer->name : '';

        $data['token'] = sha1(microtime()) . sha1(rand());

        if ( !Customer::checkExist($data['facebook_id']) ) {

            $data['userStatus'] = 'new';
            
            Customer::where('device_token', $deviceToken)->update(['device_token' => '']);

            $customerNew = new Customer();
            $customerNew->facebook_id = $data['facebook_id'];
            $customerNew->name = $data['name'];
            $customerNew->avatar = $data['avatar'];
            $customerNew->email = $data['email'];
            $customerNew->location = $data['location'];
            $customerNew->gender = $data['gender'];
            $customerNew->birthday = $data['birthday'];
            $customerNew->work = $data['work'];
            $customerNew->token = $data['token'];
            $customerNew->customers_code = self::generationCustomerCode();

            if ( $deviceType && $deviceToken ) {
                $customerNew->device_type = $deviceType;
                $customerNew->device_token = $deviceToken;
            }

            if ( $customerNew->save() ) {
                return response()->json(['error' => false, 'response' => $data]);
            }

            return response()->json(['error' => true, 'message' => 'Tạo tài khoản không thành công'], 500);
        } else {

            $data['userStatus'] = 'exist';

            Customer::where('device_token', $deviceToken)->update(['device_token' => '']);

            $customerExist = Customer::where('facebook_id', $data['facebook_id'])->first();
            $customerExist->facebook_id = $data['facebook_id'];
            $customerExist->name = $data['name'];
            $customerExist->avatar = $data['avatar'];
            $customerExist->email = $data['email'];
            $customerExist->location = $data['location'];
            $customerExist->gender = $data['gender'];
            $customerExist->birthday = $data['birthday'];
            $customerExist->work = $data['work'];
            $customerExist->token = $data['token'];

            if ( $deviceType && $deviceToken ) {
                $customerExist->device_type = $deviceType;
                $customerExist->device_token = $deviceToken;
            }

            if ( $customerExist->save() ) {
                return response()->json(['error' => false, 'response' => $data]);
            }

            return response()->json(['error' => true, 'message' => 'Cập nhật thông tin tài khoản không thành công'], 500);
            
        }

    }

    function generationCustomerCode($runAgain=false)
    {
        $code = time();

        if ( $runAgain ) {
            $code += 1;
        }

        if ( !Customer::getCustomerByCode($code) ) {
            return $code;
        }

        return self::generationCustomerCode(true);
    }

    /**
     * Trả về danh sách merchant theo customer
     */
    function postListMerchantByCustomer(Request $request)
    {
        $param = $request->header('param');
        $param = json_decode($param);
        $token = isset($param->token) ? $param->token : "";

        if ( !$token ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền tham số token'], 404);
        }

        $customer = Customer::getCustomerByToken($token);

        if ( $customer ) {

            return response()->json(['error' => false, 'listMerchant' => Merchant::listByCustomer($customer->id, $request->input('lastID'))]);
        }

        return response()->json(['error' => true, 'message' => 'Token không hợp lệ'], 401);
    }

    /**
     * Trả về danh sách merchant
     */
    function postListMerchant(Request $request)
    {

        $param = $request->header('param');
        $param = json_decode($param);
        $token = isset($param->token) ? $param->token : "";

        $customerID = 0;

        if ( $token ) {
            $customer = Customer::getCustomerByToken($token);

            if ( !$customer ) {
                return response()->json(['error' => true, 'message' => 'Token không hợp lệ'], 401);
            }

            $customerID = $customer->id;
        }

        $topMerchant = Merchant::listTopMerchant($customerID);

        return response()->json([
                'error' => false, 
                'topMerchant' => $topMerchant, 
                'listMerchant' => Merchant::listMerchantByIds(array_pluck($topMerchant, 'id'), $customerID)
            ]);

    }

    /**
     * Remove merchant từ list merchant mà customer đã chọn
     */
    function postRemoveMerchantOfCustomer(Request $request)
    {
        $param = $request->header('param');
        $param = json_decode($param);
        $token = isset($param->token) ? $param->token : "";
        $merchantID = isset($param->merchantID) ? $param->merchantID : "";

        if ( !$token ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền tham số token'], 404);
        }

        if ( !$merchantID ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền tham số merchantID'], 404);
        }

        $customer = Customer::getCustomerByToken($token);

        if ( $customer ) {

            if ( CustomerMerchant::removeMerchantOfCustomer($customer->id, $merchantID) ) {
                return response()->json([
                        'error' => false, 
                        'message' => 'Xoá merchant thành công!'
                    ]);
            }

            return response()->json([
                    'error' => true, 
                    'message' => 'Xoá merchant không thành công'
                ], 500);
        }

        return response()->json(['error' => true, 'message' => 'Token không hợp lệ'], 401);
    }

    /**
     * Upload mặt trước và mặt sau của thẻ của customer ứng với mỗi merchant
     */
    function postCustomerUploadCardOfMerchant(Request $request)
    {
        $param = $request->header('param');
        $param = json_decode($param);
        $token = isset($param->token) ? $param->token : "";
        $merchantID = isset($param->merchantID) ? $param->merchantID : "";
        $image = $request->input('image');
        $imageType = $request->input('imageType');

        if ( !$token ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền tham số token'], 404);
        }

        if ( !$merchantID ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền tham số merchantID'], 404);
        }

        if ( !$image ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền tham số image'], 404);
        }

        if ( !$imageType ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền tham số imageType'], 404);
        }

        if ( !( $imageType == 'front' || $imageType == 'back' ) ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền đúng giá trị của tham số imageType'], 404);
        }

        $customer = Customer::getCustomerByToken($token);

        if ( $customer ) {

            $customerID = $customer->id;

            $customerMerchant = CustomerMerchant::getByCustomerIdMerchantId($customerID, $merchantID);

            if ( $customerMerchant && $customerMerchant->status === 1 ) {
                $fileName = uploadBase64($image, 'upload/image/customer_cards/', $imageType . '_' . $customerID . $merchantID);

                if ( !$fileName ) {
                    return response()->json([
                        'error' => true, 
                        'message' => 'Upload thẻ ' . ( $imageType == 'front' ? 'mặt trước' : 'mặt sau' ) . ' không thành công'
                    ], 500);
                }

                if ( $imageType == 'front' ) {
                    $customerMerchant->front_card_image = $fileName . '?' . time();
                } else {
                    $customerMerchant->back_card_image = $fileName . '?' . time();
                }

                if ( !$customerMerchant->save() ) {
                    return response()->json([
                        'error' => true, 
                        'message' => 'Chỉnh sửa thẻ ' . ( $imageType == 'front' ? 'mặt trước' : 'mặt sau' ) . ' không thành công'
                    ], 500);
                }

                return response()->json([
                    'error' => false, 
                    'message' => 'Thêm ảnh thẻ ' . ( $imageType == 'front' ? 'mặt trước' : 'mặt sau' ) . ' thành công!', 
                    'urlImage' => $fileName . '?' . time()
                ]);

            }

            return response()->json(['error' => true, 'message' => 'Customer chưa đăng ký hoặc không tồn tại merchant này'], 404);
        }

        return response()->json(['error' => true, 'message' => 'Token không hợp lệ'], 401);
    }


    /*
     * Trả về danh sách các deal dành cho Customer. S9 - Ưu đãi
     */
    function postListDeal(Request $request) 
    {
        $param = $request->header('param');
        $param = json_decode($param);
        $accessToken = isset($param->token) ? $param->token : "";
        $lastEndDay = $request->input('lastEndDay');

        if( !$accessToken ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền tham số token'], 404);
        }
        
        $customer = Customer::getCustomerByToken($accessToken);

        if ( $customer ) {

            $customerID = $customer->id;
            $checkExist = CustomerMerchant::checkExistsById($customerID);

            if( $checkExist ) {

                $listDealActive = Deal::getDealActiveById($customerID, $lastEndDay);
                
                return response()->json(['error' => false, 'listDeal' => $listDealActive, 'register' => 1]);
            }
            
            return response()->json(['error' => false, 'listDeal' => Deal::getAllDeal($lastEndDay), 'register' => 0]);
        }

        return response()->json(['error' => true, 'message' => 'Token không hợp lệ'], 401);
    }

    /*
     * Trả về nội dung chi tiết của deal cho Customer. S10 - Chi tiết ưu đãi
     */
    function postDealDetail(Request $request) {
        $param = $request->header('param');
        $param = json_decode($param);
        $dealID = isset($param->dealID) ? $param->dealID : "";

        if( !$dealID ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền id của Deal']);
        }

        $dealDetail = Deal::getDealDetailById($dealID);

        $dataImage = [];
        foreach (json_decode($dealDetail->image_content) as $key => $value) {
            array_push($dataImage, $value);
        }

        $dealDetail->image_content = $dataImage;

        $cardType = OptionValue::getIdNameOptionValue();

        $search = array_pluck($cardType, 'name', 'id');

        $cardMerchant = Merchant::getMerchantById($dealDetail->merchants_id);

        $cardType = $cardMerchant->card_type;
        $cardInfo = json_decode($cardMerchant->card_info)->value;

        if ( $cardType == 3 ) {
            $data = [];
            $codeColor = [];
            foreach (json_decode($dealDetail->apply_objects)->id as $key => $value) {

                array_push($data, $search[$value]);

                foreach ($cardInfo as $cards) {

                        if ( intval($value) === $cards->id ) {
                            $codeColor[] = $cards->value[0]->background_color;
                        }
                }
            }

            $dealDetail->apply_objects = $data;
            $dealDetail->codeColor = $codeColor;
        } else {
            $dealDetail->apply_objects = null;
        }

        $dealDetail->start_day = date("d/m/Y", strtotime($dealDetail->start_day));
        $dealDetail->end_day = date("d/m/Y", strtotime($dealDetail->end_day));
        
        return response()->json(['error' => false, 'dealDetail' => $dealDetail]);
    }

    /*
     * Trả về toàn bộ Notification mà Customer đã quan tâm
     */
    function postListNotification(Request $request)
    {
        $param = $request->header('param');
        $param = json_decode($param);
        $accessToken = isset($param->token) ? $param->token : "";
        $lastID = $request->input('lastID');

        if ( !$accessToken ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền tham số token của Customer'], 404);
        }

        $customer = Customer::getCustomerByToken($accessToken);

        if( $customer ) {

            $data = Notification::getNotificationByIdOfCustomer($customer->id, $lastID);
            $tagOpen = '<font color="#f94876">';
            $tagClose = '</font>';
            foreach ($data as $key => $value) {
                $value->created_at = calculateTimeAgo(strtotime($value->created_at));

                if ( !( $value->dealsID == null && $value->type == null ) ) {
                    if ( $value->dealsID == null  ) {
                        $value->sendmsg = 'show';
                        if ( $value->type === 2 ) {
                            $value->content = "{$tagOpen}{$value->name}{$tagClose} tặng bạn {$tagOpen}{$value->change_points} điểm thưởng{$tagClose}";
                        } elseif ( $value->type === 3 ) {
                            $value->content = "{$tagOpen}{$value->name}{$tagClose} tặng bạn {$tagOpen}{$value->change_points} chops{$tagClose}";
                        } elseif ( $value->type === 4 ) {
                            $value->content = "Đổi {$tagOpen}{$value->change_points}{$tagClose} chops thành công tại {$tagOpen}{$value->name}{$tagClose}";
                        } else {
                            $value->content = 'Chưa có giao dịch loại này.';
                        }
                    }
                }
            }

            return response()->json(['error' => false, 'notification' => $data]);
        }

        return response()->json(['error' => true, 'message' => 'Token không hợp lệ'], 401);
    }

    /*
     * Lưu phản hồi của Customer
     */
    function postFeedbackCustomer(Request $request)
    {
        $param = $request->header('param');
        $param = json_decode($param);
        $accessToken = isset($param->token) ? $param->token : "";
        $notifyID = isset($param->notifyID) ? $param->notifyID : "";
        $rateService = $request->input('rate');
        $feedback = $request->input('feedback');

        if ( !$accessToken ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền tham số token của Customer'], 404);
        }

        if ( !$notifyID || !$rateService || !$feedback) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền đủ tham số'], 404);
        }

        $customer = Customer::getCustomerByToken($accessToken);

        if( $customer ) {

            $check = Feedback::checkExistFeedback($customer->id, $notifyID);
            if ( $check ) {
                return response()->json(['error' => true, 'message' => 'Đã tồn tại feedback cho dịch vụ này']);
            }

            $nameOfStore = Store::getNameStoreByNotifyID($notifyID);

            $feedbackMD = new Feedback();
            $feedbackMD->customers_id = $customer->id;
            $feedbackMD->notifications_id = $notifyID;
            $feedbackMD->customers_name = $customer->name;
            $feedbackMD->stores_name = $nameOfStore;
            $feedbackMD->rate = $rateService;
            $feedbackMD->content = $feedback;
            if ( !$feedbackMD->save() ) {
                return response()->json(['error' => true, 'message' => 'Thêm feedback không thành công'], 500);
            }

            return response()->json(['error' => false, 'message' => 'Thêm mới feedback thành công!']);
        }

        return response()->json(['error' => true, 'message' => 'Token không hợp lệ'], 401);
    }

    /*
     * Trả về điểm và các giao dịch của customer với từng merchant
     */
    function postPointAndTransaction(Request $request)
    {
        $param = $request->header('param');
        $param = json_decode($param);
        $accessToken = isset($param->token) ? $param->token : "";
        $merchantID = isset($param->merchantID) ? $param->merchantID : "";

        if( !$accessToken ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền tham số token của Customer'], 404);
        }

        if ( !$merchantID ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền id của merchant tương ứng'], 404);
        }

        $customer = Customer::getCustomerByToken($accessToken);
        $merchant = Merchant::find($merchantID);

        if ( !$merchant ) {
            return response()->json(['error' => true, 'message' => 'Tham số merchantID không hợp lệ'], 401);
        }

        if( $customer ) {

            if ( $merchant->kind === 3 ) {
                // $phoneNumber = isset($param->phoneNumber) ? $param->phoneNumber : "";

                // if ( !$phoneNumber ) {
                //     return response()->json(['error' => true, 'message' => 'Chưa truyền phoneNumber'], 404);
                // }

                $dataMerchants = Merchant::getMerchantIdByNames(['BOO FASHION']);
                $dataMerchants = array_pluck($dataMerchants, 'id', 'name');
                $booID = 0;

                if ( isset($dataMerchants['BOO FASHION']) ) {
                    $booID = $dataMerchants['BOO FASHION'];
                }

                if ( $booID == $merchantID ) {
                    $dataBOO = CrawlingData::getBooCustomerInfo($customer->mobile);
                    $dataBillBOO = CrawlingData::getBooCustomerBill($customer->mobile);
                    $transactions = [];
                    if ( $dataBillBOO->code ) {
                        foreach ($dataBillBOO->data->bill as $bill) {
                            if ( $bill->points || $bill->usedPoints ) {
                                if ( $bill->points ) {
                                    $transactions[] = [
                                        'id' => $bill->id,
                                        'type' => 2,
                                        'change_points' => $bill->points,
                                        'created_at' => date('d.m.Y H:i', strtotime($bill->createdDateTime)),
                                    ];
                                }
                                if ( $bill->usedPoints ) {
                                    $transactions[] = [
                                        'id' => $bill->id,
                                        'type' => 1,
                                        'change_points' => $bill->usedPoints,
                                        'created_at' => date('d.m.Y H:i', strtotime($bill->createdDateTime)),
                                    ];
                                }
                            } else {
                                $transactions[] = [
                                    'id' => $bill->id,
                                    'type' => 2,
                                    'change_points' => 0,
                                    'created_at' => date('d.m.Y H:i', strtotime($bill->createdDateTime)),
                                ];
                            }
                        }
                    }
                    if ( $dataBOO->code ) {
                        $response['error'] = false;
                        $response['pagination'] = false;
                        $response['isBOO'] = true;
                        $response['accumulate_points'] = reset($dataBOO->data->customers)->totalMoney ? number_format(reset($dataBOO->data->customers)->totalMoney) : 0;
                        $response['current_point'] = reset($dataBOO->data->customers)->points ? number_format(reset($dataBOO->data->customers)->points) : 0;
                        $response['transactions'] = $transactions;
                        $response['merchantColor'] = $merchant->color;
                        return response()->json($response);
                    }
                    return response()->json(['error' => true, 'message' => 'Sai thông tin đăng nhập'], 401);
                    
                }
                return response()->json(['error' => true, 'message' => 'Chưa có API cho merchant này'], 401);
            }
            
            if ($merchant->name == 'CGV - Megastar') {
                $datas = CrawlingData::where('user_id',$customer->id)->where('partner',1)->first();
                $datas = $datas != null ? $datas->data : [];
                $datass = unserialize ($datas);
                // dd($datass);
                $data['error'] = false;
                $data['pagination'] = false;
                $data['accumulate_points'] = $datass['profile']['pointTotal'];
                $data['current_point'] = $datass['profile']['pointAvailable'];
                $data['transactions']=array();
                
                if ($datass['historyEarn']) {
                    foreach ($datass['historyEarn'] as $key => $tran) {
                        $temp= array();
                        $temp['type']=2;
                        $temp['change_points']=$tran['pointEarned'];
                        $date = date_create_from_format('d.M.Y H:i', $tran['dateTime']);
                        $tran['dateTime'] = str_replace('/', '.',$tran['dateTime']);
                        $temp['created_at']=date('d.m.Y H:i', strtotime($tran['dateTime']));
                        array_push($data['transactions'], $temp);
                    }
                }
                if ($datass['historyConvert']) {
                     foreach ($datass['historyConvert'] as $key => $tran) {
                        $temp= array();
                        $temp['type']=1;
                        $temp['change_points']=$tran['pointConvert'];
                        $temp['created_at']=date('d.m.Y H:i', strtotime($tran['dateTime']));
                        array_push($data['transactions'], $temp);
                    }
                }
                $data['merchantColor'] = '#f94876';
                return response()->json($data);
            }
            elseif ($merchant->name == 'The Alfresco Group') {
                $datas = CrawlingData::where('user_id',$customer->id)->where('partner',2)->first() != null ? CrawlingData::where('user_id',$customer->id)->where('partner',2)->first()->data : [];
                $datass = unserialize ($datas);
                $data['error'] = false;
                $data['pagination'] = false;
                $data['accumulate_points'] = $datass['profile']['pointTotal'];
                $data['current_point'] = $datass['profile']['pointAvailable'];
                $data['transactions']=array();
                
                if ($datass['historyEarn']) {
                    foreach ($datass['historyEarn'] as $key => $tran) {
                        $temp= array();
                        $temp['type']=2;
                        $temp['change_points']=$tran['pointEarned'];
                        $temp['created_at']=date('d.m.Y H:i', strtotime($tran['dateTime']));
                        array_push($data['transactions'], $temp);
                    }
                }
                if ($datass['historyConvert']) {
                     foreach ($datass['historyConvert'] as $key => $tran) {
                        $temp= array();
                        $temp['type']=1;
                        $temp['change_points']=$tran['pointConvert'];
                        $temp['created_at']=date('d.m.Y H:i', strtotime($tran['dateTime']));
                        array_push($data['transactions'], $temp);
                    }
                }
                $data['merchantColor'] = '#f94876';
                return response()->json($data);
            }
            else {
                $lastID = $request->input('lastID');

                $customerPoint = CustomerMerchant::getByCustomerIdMerchantId($customer->id, $merchantID);
                $customerTransaction = HistoryAchievement::getTransactionById($customer->id, $merchantID, $lastID);
                $transactions = [];
                foreach ($customerTransaction as $value) {
                    $transactions[] = [
                        'id' => $value->id, 
                        'type' => $value->type, 
                        'change_points' => $value->change_points, 
                        'created_at' => date('d.m.Y H:i', strtotime($value->created_at))
                    ];
                }

                $data['error'] = false;
                $data['pagination'] = true;
                $data['accumulate_points'] = $customerPoint->point;
                $data['current_point'] = $customerPoint->current_point;
                $data['transactions'] = $transactions;
                $data['merchantColor'] = $merchant->color;
                return response()->json($data);
            }
        }

        return response()->json(['error' => true, 'message' => 'Tham số token không hợp lệ'], 401);
    }
    
    /*
     * API trả về hạng thẻ, số thẻ của customer
     */
    /*public function postLevelCustomer(Request $request) {
        $param = $request->header('param');
        $param = json_decode($param);
        $accessToken = isset($param->token) ? $param->token : "";
        $merchantID = isset($param->merchantID) ? $param->merchantID : "";


        if ( !$accessToken ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền tham số token của Customer'], 404);
        }

        $customer = Customer::getCustomerByToken($accessToken);

        if ( !$merchantID ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền id của merchant'], 404);
        }

        if (Merchant::find($merchantID)->name == 'CGV - Megastar') {
            $data = CrawlingData::where('user_id',$customer->id)->where('partner',1)->first() != null ? CrawlingData::where('user_id',$customer->id)->where('partner',1)->first()->data : [];
            if ($data) {

                $result = unserialize($data);
                $datas['error'] = false;
                $totalPoint = 0;
                $datas['merchantLogo'] = Merchant::find($merchantID)->logo;

                foreach ($result['historyEarn'] as $key => $value) {
                    $totalPoint += intval($result['historyEarn'][$key]['spend']);
                }

                $ageCustomer = date_diff(date_create($customer->birthday), date_create('today'))->y;

                if ( $ageCustomer > 12 && $ageCustomer < 18 ) {
                    $datas['levelCard'] = "Young";
                } else {
                    if ( $totalPoint < 3500 ) {
                        $datas['levelCard'] = "Member";
                    } else if ( $totalPoint > 3500 && $totalPoint < 8000 ) {
                        $datas['levelCard'] = "VIP";
                    } else {
                        $datas['levelCard'] = "VVIP";
                    }
                }

                $cardType = OptionValue::getIdNameOptionValue();

                $search = array_pluck($cardType, 'id', 'name');

                $idLevelCard = $search[$datas['levelCard']];

                CustomerMerchant::where('customers_id',$customer->id)
                                ->where('merchants_id',$merchantID)
                                ->update(array(
                                    'level' => $idLevelCard
                                ));

                $datas['numberCard'] = "";

                return response()->json($datas);
            }
        }

        if (Merchant::find($merchantID)->name == 'The Alfresco Group') {
            $data = CrawlingData::where('user_id',$customer->id)->where('partner',2)->first() != null ? CrawlingData::where('user_id',$customer->id)->where('partner',2)->first()->data : [];

            if ($data) {

                $result = unserialize($data);
                $datas['error'] = false;
                $totalPoint = 0;
                $datas['merchantLogo'] = Merchant::find($merchantID)->logo;

                foreach ($result['historyEarn'] as $key => $value) {
                    $totalPoint += (int)str_replace(array(' ', ','), '', $result['historyEarn'][$key]['spend']);
                }

                $totalPoint /= 1000;

                if ( $totalPoint < 3500 ) {
                    $datas['levelCard'] = "Member";
                } else if ( $totalPoint > 3500 && $totalPoint < 8000 ) {
                    $datas['levelCard'] = "VIP";
                } else {
                    $datas['levelCard'] = "VVIP";
                }

                $datas['numberCard'] = "";

                return response()->json($datas);
            }
        }

        $merchantData = Merchant::getMerchantById($merchantID);
        $customerMerchantData = CustomerMerchant::getByCustomerIdMerchantId($customer->id, $merchantID);

        $levelCard = null;

        if($merchantData->card_type == 3) {
            if( $customerMerchantData && $customerMerchantData->status === 1 && $customerMerchantData->level != null ) {
                 $levelCard = OptionValue::find($customerMerchantData->level)->name;
            }
           
        }

        return response()->json(['error' => false, 'cardInfo' => ['merchantLogo' => $merchantData->logo, 'levelCard' => $levelCard, 'numberCard' => $customerMerchantData->barcode]]);
    }*/

    /* 
    *   API client truyen len logo, name, so the cua merchant khong thuoc doi tac, serve xu ly update data (No-Partnership Merchants / Add new merchants ) 
    */
    public function postUpdateCustomerOfMerchantNoPartnership(Request $request) {
        $param = $request->header('param');
        $param = json_decode($param);
        $accessToken = isset($param->token) ? $param->token : "";
        $image = $request->input('logo');
        $name = $request->input('name');
        $barcode = $request->input('barcode');
        

        if ( !$accessToken ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền tham số token của Customer'], 404);
        } else {

            if ( !$name ) {
                return response()->json(['error' => true, 'message' => 'Chưa truyền tham số tên thương hiệu'], 404);
            }
            if ( !$barcode ) {
                return response()->json(['error' => true, 'message' => 'Chưa truyền tham số barcode'], 404);
            }

            $customer = Customer::getCustomerByToken($accessToken);


            $fileName = uploadBase64($image, 'upload/image/customer_cards/', $customer->id .'_'. $barcode);

            if ( !$fileName ) {
                return response()->json(['error' => true, 'message' => 'Upload ảnh không thành công'], 500);
            }

            $customer_card = CustomerCard::create(array(
                'customers_id' =>$customer->id,
                'barcode'      =>$barcode,
                'name'         =>$name,
                'logo'         =>$fileName
            ));

            return response()->json(['error' => false, 'message' => 'Thêm thành công!', 'customer_card' => $customer_card]);
        }
    }

    /*
     * Trả về thông tin chương trình thẻ thành viên và url trang chi tiết chương trình
     */
    function postInfoCardMemberForCustomer(Request $request)
    {
        $param = $request->header('param');
        $param = json_decode($param);
        $merchantID = isset($param->merchantID) ? $param->merchantID : "";

        if ( !$merchantID ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền id của merchant'], 404);
        }

        $infoCardMember = Merchant::getMerchantById($merchantID);
        if ( !$infoCardMember ) {
            return response()->json(['error' => true, 'message' => 'Merchant này không tồn tại'], 404);
        }
        
        $response['error'] =  false;
        $response['logo'] =  $infoCardMember->logo;
        $response['slug'] =  $infoCardMember->slug;
        $response['type_card'] =  "";
        $response['data'] =  [];
        $response['note'] = "";
        $response['discount'] = "";

        if ( $infoCardMember->kind === 1 || $infoCardMember->kind === 2 ) {

            if ( !$infoCardMember->card_info ) {
                return response()->json(['error' => true, 'message' => 'Merchant này không có thông tin thẻ'], 404);
            }

            $cardInfo = json_decode($infoCardMember->card_info)->value;
            $cardLevel = OptionValue::getOptionValue($infoCardMember->card_type);

            $search = array_pluck($cardLevel, 'name', 'id');
            $cardType = $infoCardMember->card_type;

            $response['type_card'] = $cardType;

            $data = [];
            $note = '';
            $discount = 0;
            $tagOpen = '<font color="#f94876">';
            $tagClose = '</font>';
            foreach ($cardInfo as $cards) {
                if ( $cardType === 3 ) { //Levels
                    if ( $cards->id === 26 ) { //Newbe
                        $data[] = "Hạng thẻ thành viên mới là {$tagOpen}{$search[$cards->id]}{$tagClose}";
                    } else {
                        $data[] = "Tích đủ {$tagOpen}" . number_format($cards->value[0]->point) . " điểm{$tagClose} tương ứng {$tagOpen}" . number_format($cards->value[0]->point*$cards->value[0]->unit) . " VND{$tagClose} chi tiêu sẽ đạt hạng thẻ {$tagOpen}{$search[$cards->id]}{$tagClose} và được giảm giá {$tagOpen}{$cards->value[0]->bouns}%{$tagClose} cho tất cả các lần mua tiếp theo";

                        if ( $cards->value[0]->bouns < $discount || $discount === 0 ) {
                            $discount = $cards->value[0]->bouns;
                        }
                    }
                } elseif ( $cardType === 4 ) { //Chops
                    $cardId = $cards->id;
                    $name = $search[$cardId];
                    foreach ($cards->value as $chop) {
                        if ( $cardId === 15 ) {
                            $data[] = "Tích đủ {$tagOpen}{$chop->point} Chops{$tagClose}. Tặng sản phẩm trị giá tối đa {$tagOpen}" . number_format($chop->bouns) . " VND{$tagClose}";
                        } elseif ( $cardId === 16 ) {
                            $data[] = "Tích đủ {$tagOpen}{$chop->point} Chops{$tagClose}. Giảm giá {$tagOpen}{$chop->bouns}%{$tagClose} cho lần mua tiếp theo";
                        }
                        $note = $chop->unit;
                    }
                }
            }
            $response['data'] =  $data;
            $response['note'] =  $note ? "1 Chop tương ứng ".number_format($note)." VND chi tiêu" : "";
            $response['discount'] =  $discount ? $discount : "";
        }

        $response['merchantBgColor'] = $infoCardMember->color;
        $response['merchantTextColor'] = $infoCardMember->text_color;

        return response()->json($response);
    }

    /*
     * API kiểm tra sự tồn tại của customer trong merchant
     */
    function postRegisterCustomerIntoMerchant(Request $request)
    {
        $param = $request->header('param');
        $param = json_decode($param);
        $accessToken = isset($param->token) ? $param->token : "";
        $merchantID = isset($param->merchantID) ? $param->merchantID : "";

        if ( !$accessToken ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền token của Customer'], 404);
        }


        if ( !$merchantID ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền id của Merchant'], 404);
        }

        $merchant = Merchant::getMerchantById($merchantID);
        $customer = Customer::getCustomerByToken($accessToken);

        if ( $customer ) {
            $exist = CustomerMerchant::getByCustomerIdMerchantId($customer->id, $merchantID);

            if ( $exist && $exist->status === 0 ) {
                $exist->status = 1;

                $level = self::returnLevelFromMerchantByPoint($merchantID, $exist->point);
                if ( $level ) {
                    $exist->level = $level;
                }

                if ( $merchant->kind === 5 ) {
                    $barcode = isset($param->barcode) ? $param->barcode : "";

                    if ( !$barcode ) {
                        return response()->json(['error' => true, 'message' => 'chưa truyền tham số barcode'], 404);
                    }

                    $exist->barcode = $barcode;
                }

                if ( $exist->save() ) {
                    return response()->json(['error' => false, 'message' => 'Đăng ký lại thành công']);
                }
                
                return response()->json(['error' => true, 'message' => 'Đăng ký lại không thành công']);
            } elseif ( $exist && $exist->status === 1 ) {
                return response()->json(['error' => true, 'message' => 'Customer này đã đăng ký merchant này.']);
            } else {
                if ( $merchant->kind === 1 || $merchant->kind === 2 ) {
                    $customerMerchant = new CustomerMerchant();
                    $customerMerchant->customers_id = $customer->id;
                    $customerMerchant->merchants_id = $merchantID;

                    $level = self::returnLevelFromMerchantByPoint($merchantID, 0);
                    if ( $level ) {
                        $customerMerchant->level = $level;
                    }

                    if ( $customerMerchant->save() ) {
                        return response()->json(['error' => false, 'message' => 'Đăng ký thành công']);
                    }
                    
                    return response()->json(['error' => true, 'message' => 'Đăng ký không thành công']);
                }

                if ( $merchant->kind === 5 ) {
                    $barcode = isset($param->barcode) ? $param->barcode : "";

                    if ( !$barcode ) {
                        return response()->json(['error' => true, 'message' => 'chưa truyền tham số barcode'], 404);
                    }

                    $customerInfo = new CustomerMerchant();
                    $customerInfo->customers_id = $customer->id;
                    $customerInfo->merchants_id = $merchantID;
                    $customerInfo->barcode = $barcode;

                    if ( !$customerInfo->save() ) {
                        return response()->json(['error' => true, 'message' => 'Thêm merchant không thành công'], 500);
                    }

                    return response()->json(['error' => false, 'message' => 'Thêm mới merchant thành công!']);
                }

                return response()->json(['error' => true, 'message' => 'Merchant này API không hỗ trợ'], 404);
            }
        }

        return response()->json(['error' => true, 'message' => 'token không hợp lệ'], 401);
    }

    function returnLevelFromMerchantByPoint($merchantID, $point)
    {
        $merchant = Merchant::getMerchantById($merchantID);
        if ( $merchant->card_type === 3 ) {
            $cardsInfo = json_decode($merchant->card_info)->value;
            
            $levelId = 26;
            foreach ($cardsInfo as $card) {
                if ( $point >= $card->value[0]->point ) {
                    $levelId = $card->id;
                    break;
                }
            }

            return $levelId;
        }

        return 0;
    }

    function postSyncAccount(Request $request) {
        $param = $request->header('param');
        $param = json_decode($param);
        $accessToken = isset($param->token) ? $param->token : "";
        $username = isset($param->username) ? $param->username : "";
        $password = isset($param->password) ? $param->password : "";
        $merchantID = isset($param->merchantID) ? $param->merchantID : "";

        if ( !$password ) {
            return response()->json(['error' => true, 'message' => 'Vui lòng nhập password'], 200);
        }

        if ( !$username ) {
            return response()->json(['error' => true, 'message' => 'Vui lòng nhập username'], 200);
        }

        if ( !$merchantID ) {
            return response()->json(['error' => true, 'message' => 'Vui lòng nhập merchantID'], 200);
        }

        if ( !$accessToken ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền token của Customer'], 404);
        } 

        $customer = Customer::getCustomerByToken($accessToken);

        if ($customer) {

            $dataMerchants = Merchant::getMerchantIdByNames(['CGV - Megastar', 'The Alfresco Group']);
            $dataMerchants = array_pluck($dataMerchants, 'id', 'name');
            $cgv_id = 0;
            $alfresco_id = 0;

            if ( isset($dataMerchants['CGV - Megastar']) ) {
                $cgv_id = $dataMerchants['CGV - Megastar'];
            }

            if ( isset($dataMerchants['The Alfresco Group']) ) {
                $alfresco_id = $dataMerchants['The Alfresco Group'];
            }

            //neu la cgv
            if ($merchantID == $cgv_id) {

                $result = CrawlingData::getCgv($customer->id, $username, $password);

                if ($result['status'] =='fail') {
                    return response()->json(['error' => true, 'message' => 'Sai thông tin đăng nhập'], 200);
                }

                $customer->update(array('mobile'=>$result['data']['profile']['phone']));

                $customerMerchantDataCGVExist = CustomerMerchant::getByCustomerIdMerchantId($customer->id, $cgv_id);

                if( !$customerMerchantDataCGVExist ) {
                    CustomerMerchant::create(array(
                    'customers_id'=>$customer->id,
                    'merchants_id'=>$cgv_id,
                    'point' => $result['data']['profile']['pointTotal'],
                    'current_point'=>$result['data']['profile']['pointAvailable'],
                    'status'=>1,
                    ));
                }
                else {
                    $customerMerchantDataCGVExist->update(array(
                    'point' => $result['data']['profile']['pointTotal'],
                    'current_point'=>$result['data']['profile']['pointAvailable'],
                    'status'=>1,
                    ));
                }
                
                return response()->json(['error' => false, 'message' => 'Đăng nhập thành công']);
            } else if ($merchantID == $alfresco_id) { //neu la alfresco
                
                $result = CrawlingData::getAlfresco($customer->id,$username,$password);
               
                if ($result['status'] =='fail') {
                    return response()->json(['error' => true, 'message' => 'Sai thông tin đăng nhập'], 401);
                }

                $customerMerchantDataAlfrescoExist = CustomerMerchant::getByCustomerIdMerchantId($customer->id, $alfresco_id);
                //dai sau update cai nay vao nhe, anh chi them 2 truong vao thoi
                if( !$customerMerchantDataAlfrescoExist ) {
                    CustomerMerchant::create(array(
                    'customers_id'=>$customer->id,
                    'merchants_id'=>$alfresco_id,
                    'point' => $result['data']['profile']['pointTotal'],
                    'current_point'=>$result['data']['profile']['pointAvailable'],
                    ));
                }
                else {
                    CustomerMerchant::where('customers_id',$customer->id)->where('merchants_id',$alfresco_id)->update(array(
                    'point' => $result['data']['profile']['pointTotal'],
                    'current_point'=>$result['data']['profile']['pointAvailable'],
                    ));
                }
                

                return response()->json(['error' => false, 'message' => 'Đăng nhập thành công']);
            } else { //chang thuoc thang deo nao ca
                return response()->json(['error' => true, 'message' => 'Sai thông tin đăng nhập'], 401);
            }

        } else {
             return response()->json(['error' => true, 'message' => 'Tham số token không hợp lệ'], 401);
        }
        
    }

    /*
     * API trả về số notifications và deals chưa đọc
     */
    function postNumberNotificationAndDealNotRead(Request $request)
    {
        $param = $request->header('param');
        $param = json_decode($param);
        $token = isset($param->token) ? $param->token : "";

        if ( !$token ) {
            return response()->json(['error' => true, 'message' => 'chưa truyền tham số token của customer'], 404);
        }

        $customer = Customer::getCustomerByToken($token);

        if ( $customer ) {
            $countNotification = $customer->count_notifications;

            $countDeal = $customer->count_deals;

            return response()->json(['error' => false, 'countNotifications' => $countNotification, 'countDeals' => $countDeal]);
        }

        return response()->json(['error' => true, 'message' => 'token không hợp lệ'], 401);
    }

    /*
     * API cập nhật lại số thông báo mới và deals mới hiển thị trên màn hình
     */
    function postUpdateNumberNotificationAndDealNotRead(Request $request)
    {
        $param = $request->header('param');
        $param = json_decode($param);
        $token = isset($param->token) ? $param->token : "";
        $flag = $request->input('flag');

        if ( !$token ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền token của Customer'], 404);
        }

        if ( !$flag ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền flag'], 404);
        }

        $customer = Customer::getCustomerByToken($token);

        if ( $customer ) {
            if ( $flag === 'notification' ) {
                $customer->count_notifications = 0;
            } else {
                $customer->count_deals = 0;
            }

            if ( $customer->save() ) {
                return response()->json(['error' => false, 'message' => 'Cập nhật '. ( $flag === 'notification' ? 'notification' : 'deals' ) .' thành công']);
            }

            return response()->json(['error' => true, 'message' => 'Cập nhật không thành công']);
        }

        return response()->json(['error' => true, 'message' => 'token không hợp lệ'], 401);
    }

    /*
     * Trả về hạng thẻ, mã barcode đối với Level hoặc số chops đối với Chops và chi tiết chương trình thẻ
     */
    function postInfoCustomerMerchant(Request $request)
    {
        $param = $request->header('param');
        $param = json_decode($param);
        $token = isset($param->token) ? $param->token : "";
        $merchantID = isset($param->merchantID) ? $param->merchantID : "";

        if ( !$merchantID ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền id của merchant'], 404);
        }

        if ( !$token ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền token'], 404);
        }

        $customer = Customer::getCustomerByToken($token);

        if ( !$customer ) {
            return response()->json(['error' => true, 'message' => 'token không hợp lệ'], 401);
        }

        $infoCardMember = Merchant::getMerchantById($merchantID);
        if ( !$infoCardMember ) {
            return response()->json(['error' => true, 'message' => 'Merchant này không tồn tại'], 404);
        }

        if ( $infoCardMember->kind === 5 ) {

            $infoCardCustomer = CustomerMerchant::getByCustomerIdMerchantId($customer->id, $merchantID);
            $response['error'] = false;
            $response['customerCode'] = $infoCardCustomer->barcode;
            $response['logo'] = $infoCardMember->logo;
            $response['merchantBgColor'] = $infoCardMember->color;
            $response['merchantTextColor'] = $infoCardMember->text_color;
            $response['frontImage'] = $infoCardCustomer->front_card_image;
            $response['backImage'] = $infoCardCustomer->back_card_image;

            return response()->json($response);
        }

        if ( !$infoCardMember->card_info ) {
            return response()->json(['error' => true, 'message' => 'Merchant này không có thông tin thẻ'], 404);
        }

        if ( $infoCardMember->kind === 1 || $infoCardMember->kind === 2 ) {
            
            $cardInfo = json_decode($infoCardMember->card_info)->value;
            $cardLevel = OptionValue::getOptionValue($infoCardMember->card_type);

            $search = array_pluck($cardLevel, 'name', 'id');
            $cardType = $infoCardMember->card_type;

            $customerMerchant = CustomerMerchant::getByCustomerIdMerchantId($customer->id, $merchantID);
            $customerMerchantLevel = $customerMerchant->level;

            if ( $cardType === 3 ) {
                $response['chops'] = "";
                $response['level'] = $search[$customerMerchantLevel];
                $response['customerCode'] = $customer->customers_code;
            } elseif ( $cardType === 4 ) {
                $response['chops'] = $customerMerchant->current_point;
                $response['level'] = "";
                $response['customerCode'] = $customer->customers_code;
            }

            $response['error'] =  false;
            $response['logo'] =  $infoCardMember->logo;
            $response['type_card'] = $cardType;
            $response['slug'] =  $infoCardMember->slug;
            $response['bgcolor'] = "";
            $response['txtcolor'] = "";


            $data = [];
            $note = '';
            $discount = 0;
            $tagOpen = '<font color="#f94876">';
            $tagClose = '</font>';
            foreach ($cardInfo as $cards) {
                if ( $cardType === 3 ) { //Levels
                    if ( $cards->id === 26 ) { //Newbe
                        $data[] = "Hạng thẻ thành viên mới là {$tagOpen}{$search[$cards->id]}{$tagClose}";
                    } else {
                        $data[] = "Tích đủ {$tagOpen}" . number_format($cards->value[0]->point) . " điểm{$tagClose} tương ứng {$tagOpen}" . number_format($cards->value[0]->point*$cards->value[0]->unit) . " VND{$tagClose} chi tiêu sẽ đạt hạng thẻ {$tagOpen}{$search[$cards->id]}{$tagClose} và được giảm giá {$tagOpen}{$cards->value[0]->bouns}%{$tagClose} cho tất cả các lần mua tiếp theo";

                        if ( $cards->value[0]->bouns < $discount || $discount === 0 ) {
                            $discount = $cards->value[0]->bouns;
                        }
                    }

                    if ( $customerMerchantLevel === $cards->id ) {
                        $response['bgcolor'] = $cards->value[0]->background_color;
                        $response['txtcolor'] = $cards->value[0]->text_color;
                    }

                } elseif ( $cardType === 4 ) { //Chops
                    $cardId = $cards->id;
                    $name = $search[$cardId];
                    foreach ($cards->value as $chop) {
                        if ( $cardId === 15 ) {
                            $data[] = "Tích đủ {$tagOpen}{$chop->point} Chops{$tagClose}. Tặng sản phẩm trị giá tối đa {$tagOpen}" . number_format($chop->bouns) . " VND{$tagClose}";
                        } elseif ( $cardId === 16 ) {
                            $data[] = "Tích đủ {$tagOpen}{$chop->point} Chops{$tagClose}. Giảm giá {$tagOpen}{$chop->bouns}%{$tagClose} cho lần mua tiếp theo";
                        }
                        $note = $chop->unit;
                    }
                }
            }
            $response['data'] =  $data;
            $response['note'] =  $note ? "1 Chop tương ứng ".number_format($note)." VND chi tiêu" : "";
            $response['discount'] =  $discount ? $discount : "";
            $response['merchantBgColor']= $infoCardMember->color;
            $response['merchantTextColor'] = $infoCardMember->text_color;
            return response()->json($response);

        } elseif ( $infoCardMember->kind === 3 ) {

            // $phoneNumber = isset($param->phoneNumber) ? $param->phoneNumber : "";

            // if ( !$phoneNumber ) {
            //     return response()->json(['error' => true, 'message' => 'Chưa truyền phoneNumber'], 404);
            // }

            $dataMerchants = Merchant::getMerchantIdByNames(['BOO FASHION']);
            $dataMerchants = array_pluck($dataMerchants, 'id', 'name');
            $booID = 0;

            if ( isset($dataMerchants['BOO FASHION']) ) {
                $booID = $dataMerchants['BOO FASHION'];
            }

            if ( $booID == $merchantID ) {
                $dataBOO = CrawlingData::getBooCustomerInfo($customer->mobile);
                if ( $dataBOO->code ) {
                    $response['error'] = false;
                    $response['customerCode'] = "";
                    $response['data'] = [];
                    $response['data'][] = $infoCardMember->card_info;
                    $response['level'] = reset($dataBOO->data->customers)->level ? reset($dataBOO->data->customers)->level : 'Thành viên mới';
                    $response['logo'] = $infoCardMember->logo;
                    $response['merchantBgColor']= $infoCardMember->color;
                    $response['merchantTextColor'] = $infoCardMember->text_color;
                    return response()->json($response);
                }
                return response()->json(['error' => true, 'message' => 'Sai thông tin đăng nhập'], 401);
            }

            return response()->json(['error' => true, 'message' => 'Chưa có API cho merchant này'], 401);

        } elseif ( $infoCardMember->kind === 4 ) {

            $tagOpen = '<font color="#f94876">';
            $tagClose = '</font>';
            if ($infoCardMember->name == 'CGV - Megastar') {
                $data = CrawlingData::where('user_id',$customer->id)->where('partner',1)->first();
                $data = $data != null ? $data->data : [];
                if ($data) {
                    $result = unserialize($data);
                    $datas['error'] = false;
                    $totalPoint = 0;
                    $datas['logo'] = $infoCardMember->logo;

                    foreach ($result['historyEarn'] as $key => $value) {
                        $totalPoint += intval($result['historyEarn'][$key]['spend']);
                    }

                    $date = new \DateTime();
                    $birthday = $date::createFromFormat('d/m/Y', $result['profile']['birthday'])->format('m/d/Y');
                    $ageCustomer = date_diff(date_create($birthday), date_create('today'))->y;

                    $cardInfo = json_decode($infoCardMember->card_info);

                    $ageMax = 0;
                    $data = [];
                    $foundAge = false;
                    if ( isset($cardInfo->age) ) {
                        foreach ($cardInfo->age as $age) {
                            if ( $age[1] ) {
                                $data[] = "Điều kiện đạt {$tagOpen}" . $age[0]. "{$tagClose}: Từ {$tagOpen}" . $age[1] . " tuổi{$tagClose} - {$tagOpen}" . $age[2] . " tuổi{$tagClose}";
                            }
                            if ( $ageMax === 0 || $age[2] > $ageMax ) {
                                $ageMax = $age[2];
                            }
                            if ( $ageCustomer && !$foundAge ) {
                                if ( $ageCustomer >= $age[1] && $ageCustomer <= $age[2] ) {
                                    $datas['level'] = $age[0];
                                    $datas['bgcolor'] = $age[4];
                                    $datas['txtcolor'] =  $age[5];
                                    $foundAge = true;
                                }
                            }
                        }
                    }

                    $foundPoint = false;
                    if ( isset($cardInfo->level) ) {
                        foreach ($cardInfo->level as $level) {
                            if( !$level[2] ) {
                                $data[] = "Điều kiện đạt {$tagOpen}" . $level[0] . "{$tagClose}: Trên {$tagOpen}" . ($level[1]-1)/1000 . " triệu VND{$tagClose}";
                            } elseif ( !$level[1] ) {
                                $data[] = "Điều kiện đạt {$tagOpen}" . $level[0] . "{$tagClose}: Dưới {$tagOpen}" . ($level[2]+1)/1000 . " triệu VND{$tagClose}";
                            } else {
                                $data[] = "Điều kiện đạt {$tagOpen}" . $level[0] . "{$tagClose}: Từ {$tagOpen}" . $level[1]/1000 . " triệu VND{$tagClose} - {$tagOpen}" . $level[2]/1000 . " triệu VND{$tagClose}";
                            }
                            if ( $ageCustomer > $ageMax && !$foundPoint ) {
                                if ( $totalPoint >= $level[1] && (!$level[2] || $totalPoint <= $level[2] ) ) {
                                    $datas['level'] = $level[0];
                                    $datas['bgcolor'] = $level[4];
                                    $datas['txtcolor'] =  $level[5];
                                    $foundPoint = true;
                                }
                            }
                        }
                    }

                    $datas['data'] = $data;
                    $datas['merchantBgColor'] = $infoCardMember->color;
                    $datas['merchantTextColor'] = $infoCardMember->text_color;

                    return response()->json($datas);
                }
            }

            if ($infoCardMember->name == 'The Alfresco Group') {
                $data = CrawlingData::where('user_id',$customer->id)->where('partner',1)->first();
                $data = $data != null ? $data->data : [];

                if ($data) {

                    $result = unserialize($data);
                    $datas['error'] = false;
                    $totalPoint = 0;
                    $datas['logo'] = $infoCardMember->logo;

                    foreach ($result['historyEarn'] as $key => $value) {
                        $totalPoint += (int)str_replace(array(' ', ','), '', $result['historyEarn'][$key]['spend']);
                    }

                    $totalPoint /= 1000;

                    if ( $totalPoint < 3500 ) {
                        $datas['level'] = "Member";
                    } else if ( $totalPoint > 3500 && $totalPoint < 8000 ) {
                        $datas['level'] = "VIP";
                    } else {
                        $datas['level'] = "VVIP";
                    }

                    $datas['customerCode'] = "";

                    return response()->json($datas);
                }
            }
        }

        return response()->json(['error' => true, 'message' => 'Merchant này không có thông tin'], 404);
        
    }

    /*
     * API verify bằng số điện thoại
     */
    function postSyncAccountByPhone(Request $request)
    {
        $param = $request->header('param');
        $param = json_decode($param);
        $token = isset($param->token) ? $param->token : "";
        $merchantID = isset($param->merchantID) ? $param->merchantID : "";
        $phoneNumber = isset($param->phoneNumber) ? $param->phoneNumber : "";

        if ( !$merchantID ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền id của merchant'], 404);
        }

        if ( !$token ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền token'], 404);
        }

        if ( !$phoneNumber ) {
            return response()->json(['error' => true, 'message' => 'Chưa truyền phoneNumber'], 404);
        }

        $customer = Customer::getCustomerByToken($token);

        if ( !$customer ) {
            return response()->json(['error' => true, 'message' => 'token không hợp lệ'], 401);
        }

        $dataMerchants = Merchant::getMerchantIdByNames(['BOO FASHION']);
        $dataMerchants = array_pluck($dataMerchants, 'id', 'name');
        $booID = 0;

        if ( isset($dataMerchants['BOO FASHION']) ) {
            $booID = $dataMerchants['BOO FASHION'];
        }

        if ( $booID == $merchantID ) {
            $dataBOO = CrawlingData::getBooCustomerInfo($phoneNumber);
            if ( !$dataBOO->code ) {
                $dataBooNewUser = [
                    'mobile' => $phoneNumber,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'gender' => ($customer->gender == 'male' ? 1 : 2),
                    // 'birthday' => strtotime($customer->birthday) ? $customer->birthday : '',
                    'address' => $customer->location
                ];
                $result = CrawlingData::addBooCustomer($dataBooNewUser);
            }

            $exist = CustomerMerchant::getByCustomerIdMerchantId($customer->id, $merchantID);

            if ( $exist ) {
                if ( $exist->status === 0 ) {
                    $exist->status = 1;
                    $customer->mobile = $phoneNumber;

                    if ( $exist->save() && $customer->save() ) {
                        return response()->json(['error' => false, 'message' => 'Đăng ký lại thành công']);
                    }
                    return response()->json(['error' => true, 'message' => 'Đăng ký lại không thành công'], 401);
                }
                return response()->json(['error' => true, 'message' => 'Đã đăng ký merchant này rồi'], 401);
            } else {
                $customer->mobile = $phoneNumber;
                $customerInfo = new CustomerMerchant();
                $customerInfo->customers_id = $customer->id;
                $customerInfo->merchants_id = $merchantID;
                $customerInfo->status = 1;
                if ( $customerInfo->save() && $customer->save() ) {
                    return response()->json(['error' => false, 'message' => 'Đăng nhập thành công']);
                }
                return response()->json(['error' => true, 'message' => 'Đăng ký không thành công'], 401);
            }

            return response()->json(['error' => true, 'message' => 'Sai thông tin đăng nhập'], 401);
        }

        return response()->json(['error' => true, 'message' => 'Chưa có API cho merchant này'], 401);

    }
}