<?php
use App\Merchant;
use App\OptionValue;
use App\CustomerMerchant;
use App\Message;
use App\Notification;
use App\Deal;
use App\Store;
use App\RegisterPackage;
use App\HistoryAchievement;
use App\Statistical;
use App\Feedback;

function getCountMessageInMonthOfMerchant($merchant_id, $number_of_mouth) {

    return Notification::whereRaw('created_at > (LAST_DAY(NOW()) - INTERVAL '.$number_of_mouth.' MONTH - INTERVAL 1 MONTH + INTERVAL 1 DAY) AND (created_at < LAST_DAY(NOW()) - INTERVAL '.$number_of_mouth.' MONTH ) AND history_achievements_id IS NULL AND merchants_id = '.$merchant_id)->count();
    
}

function getCountFeedbackInMonthOfMerchant($merchant_id, $number_of_mouth) {

    $count =  Feedback::select("feedbacks.id","feedbacks.created_at","feedbacks.customers_name","feedbacks.stores_name","feedbacks.rate","feedbacks.content")
    ->join("notifications","feedbacks.notifications_id","=","notifications.id")
        ->where("notifications.merchants_id","=",$merchant_id)
        ->where("feedbacks.status","=",1)
        ->whereRaw('feedbacks.created_at > (LAST_DAY(NOW()) - INTERVAL '.$number_of_mouth.' MONTH - INTERVAL 1 MONTH + INTERVAL 1 DAY) AND (feedbacks.created_at < LAST_DAY(NOW()) - INTERVAL '.$number_of_mouth.' MONTH )')
        // ->whereRaw("feedbacks.created_at >= (LAST_DAY(NOW()) - INTERVAL 2 MONTH + INTERVAL ".$number_of_day." DAY) AND feedbacks.created_at <= (LAST_DAY(NOW()) - INTERVAL 2 MONTH )")
        ->count();

    return $count;
    
}

function updateStatistical() {
    $merchant_info = Merchant::where('active', 1)->get();
    
    if($merchant_info) {
        foreach ($merchant_info as $key => $value) {
            #code...
            $messages = [];
            $feedbacks = [];
            for($i = 1; $i <= 6; $i++){
                $messages['Tháng '.$i] = getCountMessageInMonthOfMerchant($value['id'], $i);
                Statistical::where('merchants_id',$value['id'])->update(['messages' => json_encode($messages)]);
                $feedbacks['Tháng '.$i] = getCountFeedbackInMonthOfMerchant($value['id'], $i);
                Statistical::where('merchants_id',$value['id'])->update(['feedbacks' => json_encode($feedbacks)]);
            }
            // die();
        }
    }
    
}

function price_formate($price) {
    return number_format($price,0,",",".");
}

function getTypeCardName($jsonString) {
    $data = json_decode($jsonString,true);
    $string = "";
    foreach ($data as $key => $value) {
        # code...

        foreach ($data[$key] as $key1 => $value1) {
            # code...
            $string = $string.",".OptionValue::find($value1)->name;
        }
        
    }
    $string = substr($string, 1);
    return $string;
}

function getKindOfMerchant($merchant_id) {
    $data = Merchant::find($merchant_id)->get();
    return $data[0]['kind'];
}

function pushNotificationForIOS($alert, $response, $deviceTokens, $dev=false, $badge=1)
{
    // Construct the notification payload
    $body = $response;
    $body['aps'] = ['alert' => $alert];
    $body['aps']['badge'] = $badge;
    $body['aps']['sound'] = 'default';
    /* End of Configurable Items */
    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', $dev ? '../app/Helpers/pushdevcert.pem' : '../app/Helpers/pushprodcert.pem');
    stream_context_set_option($ctx, 'ssl', 'passphrase', '');

    // Open a connection to the APNS server
    if ( $dev ) {
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err, 
            $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
    } else {
        $fp = stream_socket_client(
            'ssl://gateway.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
    }

    if (!$fp)
    exit("Failed to connect: $err $errstr" . PHP_EOL);

    // echo date("Y-m-d H:i:s").' Connected to APNS' . PHP_EOL;

    $payload = json_encode($body);

    foreach ($deviceTokens as $index => $deviceToken) {
        // request one 
        $msg = chr(0) . pack('n', 32) . pack('H*', trim($deviceToken)) . pack('n', strlen($payload)) . $payload;
        // echo "<br>".date("Y-m-d H:i:s")." Sending message: " . $payload . "\n";
        // echo "<br>".date("Y-m-d H:i:s")." With device token is $deviceToken";

        $result = fwrite($fp, $msg, strlen($msg));

        // echo "<br>".date("Y-m-d H:i:s")." Result: $result";

        // if (!$result) {
        //     echo '<br>'.date("Y-m-d H:i:s").' Message not delivered' . PHP_EOL;  
        // } else {
        //     echo '<br>'.date("Y-m-d H:i:s").' Message successfully delivered' . PHP_EOL;
        // }
    }

    fclose($fp);
    // echo '<br>'.date("Y-m-d H:i:s").' Connection closed to APNS' . PHP_EOL;
}

function pushNotificationForAndroid($data, $deviceTokens)
{
    //Google cloud messaging GCM-API url
    $url = 'https://android.googleapis.com/gcm/send';
    $fields = array(
        'registration_ids' => $deviceTokens,
        'data' => $data,
    );
    // dd($fields);
    // Google Cloud Messaging GCM API Key
    
    $headers = array(
        'Authorization: key=' . env('GOOGLE_API_KEY'),
        'Content-Type: application/json'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    curl_close($ch);
}

function sendMessage($devie_token, $notify_id, $message_content, $merchant_name,  $merchant_logo, $start_time, $type_transaction, $change_point, $deal_id) {

        ///Anh vui support choem phannay. Em Toan
        $message = array(
            'notification' => array(
                "id" => $notify_id,
                "content" => $message_content,
                "name" => $merchant_name,
                "logo" => $merchant_logo,
                "created_at"=> $start_time,
                "type" => $type_transaction,
                "change_points" => $change_point,
                // nếu tồn tại dealsID => đây là notification thuộc loại non-transaction
                "dealsID" => $deal_id
            ),
            "parent" => "gcm"
        );


        // $message = json_encode($message);
    
            // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        // $mesage = response()->json(['notification' => $noti, 'parent' => 'gcm']);
        // d response()->json(['error' => true, 'message' => 'Tham số accessToken không được phép trống'], 404);d($message);
        $registrationIDs = $devie_token;
        // dd($mesage);
        //Google cloud messaging GCM-API url
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registrationIDs,
            'data' => $message,
        );
        // dd($fields);
        // Google Cloud Messaging GCM API Key
        
        $headers = array(
            'Authorization: key=' . env('GOOGLE_API_KEY'), 
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        
        // var_dump($result); 
    
}
function resize($newWidth, $newHeight, $originalFile, $targetFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
                $image_create_func = 'imagecreatefromjpeg';
                $image_save_func = 'imagejpeg';
                $new_image_ext = 'jpg';
                break;

        case 'image/png':
                $image_create_func = 'imagecreatefrompng';
                $image_save_func = 'imagepng';
                $new_image_ext = 'png';
                break;

        case 'image/gif':
                $image_create_func = 'imagecreatefromgif';
                $image_save_func = 'imagegif';
                $new_image_ext = 'gif';
                break;

        default: 
                throw Exception('Unknown image type.');
    }

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);

    // $newHeight = ($height / $width) * $newWidth;
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if (file_exists($targetFile)) {
        unlink($targetFile);
    }
    $image_save_func($tmp, "$targetFile");
    // return $targetFile;
}
    


function price($price) {
    return number_format($price,0,",",",");
}

/**
 * Upload ảnh base64
 * @param  [type] $base64String chuỗi base64
 * @param  [type] $path         đường dẫn sẽ dùng để lưu ảnh
 * @param  [type] $name         tên file không chứa đuôi mở rộng
 * @return đường dẫn file nếu upload thành công - trả về rỗng nếu không upload thành công
 */
function uploadBase64($base64String, $path, $name)
{
    if (strpos($base64String, 'data:image/png;base64,') != false) {
        $extendtion = '.png';
        $img = str_replace('data:image/png;base64,', '', $base64String);
    } else {
        $extendtion = '.jpg';
        $img = str_replace('data:image/jpeg;base64,', '', $base64String);
    }
    $img = str_replace(' ', '+', $img);
    $file = $path . $name . $extendtion;
    return file_put_contents($file, base64_decode($img)) ? $file : '';
}

 function getListObjectApply($merchant_id) {

    $infoCardMember = Merchant::getMerchantById($merchant_id);       
    $temp = json_decode($infoCardMember->card_info);
    $temp = json_decode($infoCardMember->card_info);
    $temp_id = $temp->id;
    // dd($level_id);
    $temp_value = $temp->value;
    $cardLevel = OptionValue::getOptionValue($infoCardMember->card_type);
    // dd($cardLevel);
    $data = [];
    $search = array_pluck($cardLevel, 'name' , 'id');
    foreach ($temp_value as $value) {
        array_push($data, array(
            "id" =>  $value->id,
            "name" => $search[$value->id]
        ));
        // var_dump($search[$value->id]);
        
        // var_dump($data);
    }
    return $data;

}


function getCardTypeOfMerchant() {
    $id = Auth::merchant()->get()->id;
    $merchant_data = Merchant::where('id','=',Auth::merchant()->get()->id)->get();
    $card_type = $merchant_data[0]['card_type']; // getPackage of Merchant
    return $card_type;
}

function uploadBase64WithResize($base64String, $path, $name, $newWidth, $newHeight) {

    if (strpos($base64String, 'data:image/png;base64,') !== false) {
        $extendtion = '.png';
        $img = str_replace('data:image/png;base64,', '', $base64String);
        $image_save_func = 'imagepng';
    } else {
        $extendtion = '.jpg';
        $img = str_replace('data:image/jpeg;base64,', '', $base64String);
        $image_save_func = 'imagejpeg';
    }

    $img = str_replace(' ', '+', $img);
    $file = $path . $name . $extendtion;
    // dd($file);
    $tagImage = imagecreatefromstring(base64_decode($img));
    $width = imagesx($tagImage);
    $height = imagesy($tagImage);
    // dd($width);
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $tagImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    return $image_save_func($tmp, "$file") ? $file : '';

}
function deleteImage($url) {
    unlink($url);
}
function checkActive( $active ) {
    if ( $active == 1 ) {
        echo "<span class='label label-success'>Active</span>"; 
    } else {
        echo "<span class='label label-danger'>Inactive</span>";
    }
}

function getPackageName($package_id) {
    $result = OptionValue::where("id",$package_id)->first();
    if ($result != null) {
        return $result->name;
    }
}

function getUserStatus($active_id) {
    if ($active_id == 1) {
        return "Active";
    } else {
        return "Block";
    }
}

// Page Admin

function checkPackage($package,$arrPackage) {
    foreach ( $arrPackage as $row ) {
        if ($package == $row->id) {
            echo "<button class='btn btn-sm btn-info'>" . $row->name ."</button>";
        } elseif ( $package == 0 ) {
            echo "<button class='btn btn-sm btn-danger'>Chưa có thẻ</button>";
            break;
        }
    }
}

function checkPackageAdmin($package,$arrPackage) {
    foreach ( $arrPackage as $row ) {
        if ($package == $row->id) {
            // echo "<button class='btn btn-sm btn-info'>" . $row->name ."</button>";
            echo "<span class='label label-success' style='font-size:12px;'>".$row->name ."</span>";
        } elseif ( $package == 0 ) {
            // echo "<span class='label label-danger'>Chưa tạo thẻ</span>";
            echo "";
            break;
        } else {
            echo "";
        }
    }
}

function checkPackageEmail($package) {
    $arrPackage = OptionValue::where('options_id',2)->get();
    foreach ( $arrPackage as $row ) {
        if ($package == $row->id) {
            $package = $row->name;
        }
    }
    return $package;
}

function checkActiveAdmin( $active, $id ) {
    if ( $active == -1 ) {
        echo "<span  style='font-size:11px;'>Not active email</span>";
    } elseif ( $active == 0 ) {
        echo "<span style='font-size:11px;'>Actived email</span>";
    } elseif ( $active == 1 ) {
        echo "<span style='font-size:11px;'>Actived</span>";
    } elseif ($active == 2) {
        echo "<button type='button' class='btn btn-info btn-xs btn-verify' data-merchant-id=".$id.">Verify brand</button>";
        // echo "<span  style='font-size:11px;'>Verify brand</span>";
    } elseif ($active == 3) {
        echo "<span  style='font-size:11px;'>Block</span>";
    }
}


function checkDayRemaining($endDay, $package) {
    if ( $package == 7 || $package == 8 || $package == 9) {
        $time_0 = intval(floor((strtotime($endDay) - time())/86400));
        return $time_0." ngày";
    }
    else 
        echo "";
    // intval(floor((strtotime($row->end_day) - time())/86400))
}
// function getMothPackageOfMerchant($merchants_id, $package) {
//     $yeucau = RegisterPackage::where('merchants_id',$merchants_id)->where('status',0)->where('package',$package)->first();
//     if ($yeucau == nul) {
//         return '';
//     } else $yeucau->month;

// }

function checkPackageStatusAdmin($nowPackage, $package,$arrPackage ,$user_id) {
    foreach ( $arrPackage as $row ) {
        if ($package == $row->id) {
            echo "<span style='font-size:12px; cursor: pointer;' class='label label-warning admin-package-status' data-package=".$nowPackage." data-user-id =\"".$user_id."\" data-package-name =\"".$row->name."\"data-package-id=\"".$package."\">" . $row->name ."</span>";
        }
    }
}

function getOptionValueById($id) {
    if ( $id == '10,11,12' ) {
        $data = "Tích điểm theo Level Vàng, Bạc, Đồng";
    } elseif ( $id == '13,14' ) {
        $data = "Tích điểm theo Level VIP, Thành viên";
    } else if ( $id == '23,24,25' ) {
        $data = "Tích điểm theo Level VVIP, VIP, Thành viên";
    } else {
        $value = OptionValue::where('id', $id)->first();
        if ( $value != null ) {
            $data = "Chưa cấu hình";
        } else {
            $data = $value->name;
        }
    }

    return $data;
}

function checkLevelCard($level) {
    $data = OptionValue::find($level);
    if ($data == null) {
        $level = 'Chưa có';
    } else {
        $level = $data->name;
    }
    return $level;
}

function checkTypeChangePoint($type) {
    if ($type == 1) {
        echo "Đổi điểm";
    } elseif ($type == 2) {
        echo "Tích điểm";
    } elseif ($type == 3) {
        echo "Tích Chop";
    } elseif ($type == 4) {
        echo "Đổi Chop";
    }
}


function checkTypeChangePoint2($type) {
    if ($type == 1) {
        echo "-";
    } elseif ($type == 2) {
        echo "+";
    } elseif ($type == 3) {
        echo "+";
    } elseif ($type == 4) {
        echo "-";
    }
}

function checkTypeCard($card_type) {
    if ($card_type == 4) {
        echo "chops";
    } elseif ($card_type == 3) {
        echo "điểm";
    }
}

//getAllPackages
function getAllPackages() {
    $data = OptionValue::where('options_id' , 2)->get();
    return $data;
}

//getAllPackages Not in current package
function getAllPackagesNotIn($id) {
    $data = OptionValue::where('options_id' , 2)->whereNotIn('id',[$id])->get();
    return $data;
}

function calculateTimeAgo($time) {
    $cur_time = time();
    $time_elapsed = $cur_time - $time;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return "$seconds giây trước";
    }
    //Minutes
    else if ($minutes <= 60) {
        if ($minutes == 1) {
            return "Một phút trước";
        } else {
            return "$minutes phút trước";
        }
    }
    //Hours
    else if ($hours <= 24) {
        if ($hours == 1) {
            return "Một giờ trước";
        } else {
            return "$hours giờ trước";
        }
    }
    //Days
    else if ($days <= 7) {
        if ($days == 1) {
            return "Ngày hôm qua";
        } else {
            return "$days ngày trước";
        }
    }
    //Weeks
    else if ($weeks <= 4.3) {
        if ($weeks == 1) {
            return "Một tuần trước";
        } else {
            return "$weeks tuần trước";
        }
    }
    //Months
    else if ($months <= 12) {
        if ($months == 1) {
            return "Một tháng trước";
        } else {
            return "$months tháng trước";
        }
    }
    //Years
    else {
        if ($years == 1) {
            return "Một năm trước";
        } else {
            return "$years năm trước";
        }
    }
}

function getLevel($point, $card_info) {

    $result = 0;
    foreach ($card_info['value'] as $key => $value) {

        if( intval($value['value'][0]['point']) <= $point ) {
            $result = OptionValue::find($value['id'])->id;
            break;
        }
    }
    
    return $result;
}

/*
*   Check now packages
*/
function checkNowPackage($package) {
    $data = OptionValue::find($package);
    if ( $data == null ) {
        $packageName = 'Miễn phí';
    } else {
        $packageName = $data->name;
    }
    return $packageName;
}

/*
*   Check packages pendding
*/
function checkPackagePending($packagePending) {

    if ($packagePending != 0) {
        $data = OptionValue::find($packagePending);
        if ( $data == null ) {
            $packageName = '';
        } else {
            $packageName = $data->name;
        }
        return $packageName;
    }
    
}

/*
*   Check count Customer merchant
*/
function countCustomerMerchant($merchantId) {
    $data = CustomerMerchant::where('merchants_id' , $merchantId)->count();
    if ( $data == null ) {
        $countCustomer = 0;
    } else {
        $countCustomer = $data;
    }
    return $countCustomer;
}

/*
 *   Check count Customer merchant
 */
function countMessageMerchant($merchantId) {
    $data = Notification::where('merchants_id',$merchantId)->where('type' , '0')->count();
    if ( $data == null ) {
        $countMessage = 0;
    } else {
        $countMessage = $data;
    }
    return $countMessage;
}

/*
*   Check count Deals merchant
*/
function countDealMerchant($merchantId) {
    $data = Deal::where('merchants_id' , $merchantId)->count();
    if ( $data == null ) {
        $countDeal = 0;
    } else {
        $countDeal = $data;
    }
    return $countDeal;
}

/*
 *   Check Name Field by ID
 */
function getField($fieldId) {
    if ($fieldId !=null || $fieldId !="") {
        return OptionValue::find($fieldId)->name;
    }
    else return "";
}

/*
 *   Check Name Chop By ID
 */
function getChops($id) {
    if ($id !=null || $id !="") {
        return OptionValue::find($id)->name;
    }
    else return "unknown";
}

/*
 * Validate StoreMax
 */

function validateStoreMax($max) {
    if ($max == 999) {
        echo "Unlimited";
    } else {
        echo $max . ' cửa hàng';
    }
}

/*
 * Validate Account Max
 */

function validateStoreMaxAccount($max) {
    if ($max == 999) {
        echo "Unlimited";
    } else {
        echo $max . ' tk thu ngân';
    }
}

/*
 * Validate Budget
 */

function validateBudget($budget) {
    if ($budget == 9999) {
        echo "+++";
    } else {
        echo $budget . "K/m";
    }
}

/*
 * Validate Budget
 */

function checkFeedback($feedback) {
    if ($feedback == 0) {
        echo "KHÔNG";
    } else {
        echo "CÓ";
    }
}

/*
 * Validate Value Sale
 */

function valueSale($sale) {
    if ($sale == 0) {
        echo "";
    } else {
        echo "(giảm giá " . $sale . "%)";
    }
}

/**
 * Get all month
 */

function getAllMonth() {
    $data = OptionValue::where('options_id',5)->get();
    return $data;
}
/**
 * Check Type Card Store
 */

function checkTypeCardByStore($merchantId) {
    $data = Merchant::find($merchantId);
    if ($data != null) {
        if ($data->kind == 1) {
            echo "Chops";
        } elseif ($data->kind == 2) {
            echo "Điểm";
        }
    } else {
        echo "";
    }
}

/**
 * Plus Change Chops
 */

function plusChopManage($storeId) {
    $data = HistoryAchievement::where('stores_id', $storeId)->where( 'type', 3 )->count();
    if ($data == null) {
        return 0;
    } else {
        return $data;
    }
}

/**
 * Plus Change Point
 */

function plusPointManage($storeId) {
    $data = HistoryAchievement::where('stores_id', $storeId)->where( 'type', 2 )->count();
    if ($data == null) {
        return 0;
    } else {
        return $data;
    }
}

/**
 * Except Change Chops
 */

function exceptChopManage($storeId) {
    $data = HistoryAchievement::where('stores_id', $storeId)->where( 'type', 4 )->count();
    if ($data == null) {
        return 0;
    } else {
        return $data;
    }
}

/**
 * @param $store ID
 * @return Kind
 * Check Kind by Store
 */

function getKindByStore($storeId) {
    $getMerchantByIdByStoreId = Store::select('merchants_id')->where('id', $storeId)->first()->merchants_id;
    if ( $getMerchantByIdByStoreId != null ) {
        $kind = Merchant::select('kind')->where('id' , $getMerchantByIdByStoreId)->first()->kind;
        if ( $kind != null ) {
            return $kind;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}


function getCountMember($merchants_id) {
    return CustomerMerchant::where('merchants_id',$merchants_id)->where('status',1)->count();
}

function getCountMessageRemain($merchants_id) {
        return Merchant::find($merchants_id)->message_count - Deal::whereExists(function ($query) {
            $query->from('deals')
                ->whereRaw('created_at > (LAST_DAY(NOW()) - INTERVAL 1 MONTH + INTERVAL 1 DAY)) AND (created_at < LAST_DAY(NOW()) AND merchants_id = '.$merchants_id);
        })->count();
        
    }
function getCountDealRemain($merchants_id) {
        return Merchant::find($merchants_id)->message_count - Deal::whereExists(function ($query) {
            $query->from('deals')
                ->whereRaw('created_at > (LAST_DAY(NOW()) - INTERVAL 1 MONTH + INTERVAL 1 DAY)) AND (created_at < LAST_DAY(NOW()) AND merchants_id = '.$merchants_id);
        })->count();
        
}
/**
 * cap nhat so luong notification va so deal nhan duoc cua merchant
 */
function UpdateCountDealOrNoti($merchants_id, $type) {

}
/**
 * Formart BirthDay
 */

function formatBirthDay($d, $m, $y) {
    $day    = $d ? $d : '00';
    $month  = $m ? $m : '00';
    $year   = $y ? $y : '0000';
    
    $format = $day.'/'.$month.'/'.$year;
    return $format;
}

?>
