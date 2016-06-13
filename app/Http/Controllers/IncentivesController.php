<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Route;
use App\Deal;
use App\Merchant;
use App\OptionValue;
use DB;
use Auth;
use View;
use Response;
use App\Customer;
use App\CustomerMerchant;
use App\Helpers\ImageService;
use Redirect;



class IncentivesController extends Controller
{
    public function __construct(){
        
        $this->middleware('merchant');
        
        $titlePage = 'Chương trình ưu đãi - AbbyCard';
        $className = substr(__CLASS__,21);
        $actionName = substr(strrchr(Route::currentRouteAction(),"@"),1);
        View::share(array(
            'titlePage' => $titlePage,
            'className' => $className,
            'actionName' => $actionName,
        ));
        $this->afterFilter(function() {

        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function checkDateString($str){
        if(preg_match("$[0-9]{4}/[0-1][0-9]/[0-3][0-9] [0-1][0-9]:[0-3][0-9]$", $str) === 0) {
            return false;
        } else {
            return true;
        }
    }
    
    public function index()
    {

        // dd($this->checkDateString("2016/02/03 17:25"));  
        if ( Auth::merchant()->get()->package == 0 ) {
            return redirect()->action('InitializeCardController@index')->withMessages([
                'title'     => 'Bạn chưa thể thực hiện chức năng này',
                'messages'  => 'Vui lòng hoàn thành bước Khởi tạo thẻ trước khi Gửi tin nhắn cho thành viên',
            ]);
        } else if ( Auth::merchant()->get()->active != 1 ) {
            return redirect()->action('InitializeCardController@index')->withMessages([
                'title'     => 'Bạn chưa thể thực hiện chức năng này',
                'messages'  => 'Tài khoản của bạn chưa được xác nhận. Vui lòng liên hệ admin',
            ]);
        } else {

            if(Auth::merchant()->get()) {
                $merchant_id = Auth::merchant()->get()->id;
                
                // dd(getKindOfMerchant($merchant_id));

                
                // dd($card_info);

                // $temp = getListObjectApply($merchant_id);
                // dd($temp);
                $deals = Deal::where('merchants_id' , $merchant_id)->orderby("created_at","desc")->paginate(env('PAGE'));
                // dd($deals);
                $card_type = getCardTypeOfMerchant();
                // dd($card_type);
                
                if ($card_type == 3) {
                    $temp = getListObjectApply($merchant_id);
                    $object_apply = $temp;
                    $check_level = 1;
                } elseif ($card_type == 4) {
                    $temp = getListObjectApply($merchant_id);
                    $object_apply = $temp;
                    $check_level = 0;
                }
                // dd($object_apply[0]['name']);
                foreach ($deals as $key => $deal) {
                    # code...
                    
                    //Cap nhat trang thai chuong trinh uu dai
                    //Time 0 la check xem deal da ket thuc chua
                    $time_0 = intval(floor((strtotime($deal->end_day) - time())/86400));
                    $rem_start = strtotime($deal->start_day) - time();
                    $rem = strtotime($deal->end_day) - time();
                    // $time_to_start = strtotime($deal->start_day) - time();
                    if($rem < 0) {
                        $this->updateStatus($deal['id'], 2);
                        $deal->status = 2;
                    }
                    //Time 1 check thoi gian hien tai den thoi gian bat dau
                    $time_1 = intval(floor((strtotime($deal->start_day) - time())/86400));
                    // var_dump($deal->id." time 1 = ".$time_1." time 0 = ".$time_0."<br>");
                    if($rem_start <= 0 && $rem >= 0) {
                        // var_dump($deal->id);
                        $this->updateStatus($deal['id'], 1);
                        $deal->status = 1;
                    }
                    //Time 2 la check khoang thoi gian cua chuong trinh
                    $time_2 = $this->countdown($deal->start_day, $deal->end_day);
                    // dd($time_1);
                    // dd($deal->start_day);
                    // dd(date('d.m.Y',$deal->start_day));
                    $time_string = date('d.m.Y',strtotime($deal->start_day))." - ".date('d.m.Y',strtotime($deal->end_day))."<br>";

                    //Hien thi trang thai chuong trinh uu dai
                    // dd($deal->status);
                    if ($deal->status == 0) {
                        $deal->deal_status = "Đã hủy";
                        $deal->time_message = $time_string."Chương trình ưu đãi đã hủy";
                    } elseif ($deal->status == 1) {
                        $deal->deal_status = "Đang diễn ra";
                        if ($rem < 0) {

                            $this->updateStatus($deal->id , 2);
                            $deal->status = 2;
                        }
                        if ($time_1 > 0) {
                            // $deal->time_message = "Sẽ diễn ra vào ".$time_1." ngày nữa";
                            $deal->deal_status = "Đã kích hoạt";
                            $deal->time_message = $time_string;
                        }
                        elseif ($time_0 > 0) {
                            $deal->time_message = $time_string."Còn ".$time_0." ngày";
                        }
                    } elseif($deal->status == 2) {
                        $deal->deal_status = "Đã kết thúc";
                        $deal->time_message = $time_string."Chương trình ưu đãi đã hết hạn";
                    } elseif($deal->status == 3) {
                        $deal->deal_status = "Có thể chỉnh sửa";
                        $deal->time_message =  $time_string;
                    }

                };

                // dd();

                // dd($object_apply);
                // dd($deals);
                // dd($check_level);
                return view('merchant.create-incentives' , array(
                    'deals' => $deals,
                    'object_apply' => $object_apply,
                    'check_level' => $check_level
                ));
            } else {
                return redirect('/login');
            }
        }
        
        
    }

    /**
     * Show the form for creating a new resource.
     *es
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function getObjectApplyName(Request $request) {
        $id = $request->id;
        // dd($id);
        return OptionValue::find($id)->name;
    }




    public function getMerchantPackage() {
        $id = Auth::merchant()->get()->id;
        $merchant_data = Merchant::where('id','=',Auth::merchant()->get()->id)->get();
        // $merchant_id = Merchant::where('id','=',1)->get();
        $package = $merchant_data[0]['package']; // getPackage of Merchant
        return $package;
        // return $option_info;
        
    }

    public function getDealsMax($package) {
        $option_data = OptionValue::where('id','=',$package)->get();
        $option_info = $option_data[0]['info'];
        $option = json_decode($option_info, true);
        return $option["deals_max"]; 
    }

    public function getCountDealOfMonth() {
        return Deal::whereRaw('MONTH(CAST(created_at as date)) = MONTH(NOW()) AND YEAR(CAST(created_at as date)) = YEAR(NOW()) and merchants_id = '.Auth::merchant()->get()->id)->count();
    }

    public function countdown($start, $end){
        $rem = strtotime($end) - strtotime($start);
        $day = floor($rem / 86400);
        return intval($day);
    }


    public function upload($filename, $ignore_array = [])
    {
        $image_path = array();
        
        // var_dump("Da nhan duoc ham upload");
            if ($_FILES[$filename]) {
                if ($filename == "image_avatar"){
                    $tmpFilePath = $_FILES[$filename]['tmp_name'];
                    if(filesize ($tmpFilePath) <= env('MAX_IMAGE_SIZE_UPLOAD')) {
                        if ($tmpFilePath != ""){
                            //Setup our new file path
                            // dd($tmpFilePath);
                            $newFilePath = public_path()."/upload/".$filename."/" . time()."_".str_slug(explode('.',$_FILES[$filename]['name'])[0]) .'.'. explode('.',$_FILES[$filename]['name'])[1];
                            $size = getimagesize($tmpFilePath);
                            $width = $size[0];
                            $height = $size[1];
                            resize(750, intval( 750 * $height / $width), $tmpFilePath, $newFilePath);
                            
                            //Resize image
                            
                            //Upload file da resize
                            $fileUrl = str_replace(public_path(), '', $newFilePath);
                            // dd($fileUrl);
                            $image_path[1]=substr($fileUrl,1);      
                        }
                    } else {
                        return false;
                    }
                    // dd (str_slug(explode('.',$_FILES[$filename]['name'])[0]) .'.'. explode('.',$_FILES[$filename]['name'])[1]);
                    //Make sure we have a filepath
                    
                } elseif ($filename == "image_content") {
                    # code...

                    for($i=0; $i<count($_FILES[$filename]['name']); $i++) {
                    //Get the temp file path
                    $tmpFilePath = $_FILES[$filename]['tmp_name'][$i];
                    if(filesize ($tmpFilePath) <= env('MAX_IMAGE_SIZE_UPLOAD')){
                        //Make sure we have a filepath
                        if (in_array(strval($i), $ignore_array) == false) {
                            if ($tmpFilePath != ""){
                                //Setup our new file path
                                $newFilePath = public_path()."/upload/".$filename."/" . time()."_". str_slug(explode('.',$_FILES[$filename]['name'][$i])[0]) .'.'. explode('.',$_FILES[$filename]['name'][$i])[1];

                                $size = getimagesize($tmpFilePath);
                                // dd($size);
                                $width = $size[0];
                                $height = $size[1];
                                if($width > $height){
                                    resize(750, intval( 750 * $height / $width), $tmpFilePath, $newFilePath);
                                    
                                    
                                } else {
                              
                                    resize(intval(750 * $width / $height),750, $tmpFilePath, $newFilePath);
                                }
                                //Upload the file into the temp dir
                                // resize(750, 750, $tmpFilePath, $newFilePath);
                                //Luu anh vao co so du lieu
                                $fileUrl = str_replace(public_path(), "", $newFilePath);
                                // dd($fileUrl);
                                $image_path[$i+1]=substr($fileUrl,1);
                            }
                        }
                    } else {
                        return false;
                    }
                }
            }            
        }
        return $image_path;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd("da vao day");
        try{
            $images = [];

            //Lay user id cua merchant hien tai
            $user_id = Auth::merchant()->get()->id;
            //Lay goi uu dai cua merchant hien tai
            $package = $this->getMerchantPackage();
            //Lay thong tin deal max cua user hien tai
            $deal_max = $this->getDealsMax($package);
            //Lay so luong deal da tao trong thang hien tai
            $deal_of_month = $this->getCountDealOfMonth();
            // dd($deal_of_month);
            //Lay du lieu tu form gui len qua ajax
            $dataDeal = $request->all();

            // $dataDeal['start-date'] = "2015/12/29 10:57";

            // dd($dataDeal);
            $card_type = getCardTypeOfMerchant();
            // dd($card_type);

           
            

            if ($card_type == 3) {
                $validator = Validator::make(
                    [
                        'titleIncentives'         => $dataDeal['titleIncentives'],
                        'object-apply'          => $dataDeal["object-apply"],
                        'contentIncentives'          => $dataDeal["contentIncentives"],
                        'start-date'          => $dataDeal["start-date"],
                        'end-date'          => $dataDeal["end-date"],
                    ],
                    [
                        'titleIncentives'         => 'required|max:60',
                        'contentIncentives'         => 'required|max:500',
                        'object-apply'          => 'required',
                        'start-date'            => 'required|checkCurrentTime',
                        'end-date'            => 'required|checkCurrentTime',
                    ],
                    [
                        'titleIncentives.required'     => "Tên chương trình ưu đãi không được để trống",
                        'titleIncentives.max'          => "Tên chương trình ưu đãi không vượt quá 60 ký tự",
                        
                        'contentIncentives.required'     => "Nội dung chương trình ưu đãi không được để trống",
                        'contentIncentives.max'          => "Nội chương trình ưu đãi không vượt quá 500 ký tự",
                        
                        'object-apply.required'               => "Đối tượng áp dụng không được để trống",
                        
                        'start-date.required'                 => "Ngày bắt đầu không được để trống",
                        'start-date.check_current_time'         => "Ngày bắt đầu không được ở trong quá khứ",
                        
                        'end-date.required'               => "Ngày kết thúc không được để trống",
                        'end-date.check_current_time'               => "Ngày kết thúc không được ở trong quá khứ",
                    ]
                );
            } elseif ($card_type == 4) {
                $validator = Validator::make(
                    [
                        'titleIncentives'         => $dataDeal['titleIncentives'],
                        
                        'contentIncentives'          => $dataDeal["contentIncentives"],
                        'start-date'          => $dataDeal["start-date"],
                        'end-date'          => $dataDeal["end-date"],
                    ],
                    [
                        'titleIncentives'         => 'required|max:60',
                        'contentIncentives'         => 'required|max:500',
                        
                        'start-date'            => 'required',
                        'end-date'            => 'required',
                    ],
                    [
                        'titleIncentives.required'     => "Tên chương trình ưu đãi không được để trống",
                        'titleIncentives.max'          => "Tên chương trình ưu đãi không vượt quá 60 ký tự",
                        
                        'contentIncentives.required'     => "Nội dung chương trình ưu đãi không được để trống",
                        'contentIncentives.max'          => "Nội chương trình ưu đãi không vượt quá 500 ký tự",
                        
                        
                        'start-date.required'                 => "Ngày bắt đầu không được để trống",
                        'end-date.required'               => "Ngày kết thúc không được để trống",
                    ]
                );
            }


            
        
            if ($validator->fails()) {
                $htmlMessages = '<ul>';            
                foreach ($validator->messages()->all('<li>:message</li>') as $message) {
                    $htmlMessages .= $message;
                }
                $htmlMessages .= '</ul>';

                return response()->json([
                    'title'     => 'Lỗi nhập liệu',
                    'success'   => 'validator',
                    'messages'  => $htmlMessages,     
                ]); 
            } else {
                $ignore_array = explode(",", $dataDeal['ignore_image']);


                if ($deal_of_month < $deal_max ) {
                    $dataDeal["merchant_id"] = $user_id;
                    $checkCreateDealByUser = Merchant::where('id', $user_id)->first();
                    if ($checkCreateDealByUser != null) {
                        //upload
                        $avatar_path = $this->upload("image_avatar",[]);
                        $content_path = $this->upload("image_content",$ignore_array);
                        if($avatar_path == false || $content_path == false){
                            return Response::json([
                                'success'   => false,
                                'priority'  => 'warning',
                                'messages'  => 'Size ảnh vượt quá kích thước hoặc sai định dạng cho phép'
                            ]);
                        } else {
                            // dd($avatar_path);
                            $tmpFilePath = public_path()."/".$avatar_path[1];
                            $size = getimagesize($tmpFilePath);
                            
                            $width = $size[0];
                            $height = $size[1];
                            // dd($avatar_path);
                            $dataDeal['radio_image'] = round($height / $width, 2);
                            // dd($dataDeal['radio_image']);
                            $dataDeal['image_avatar'] = $avatar_path[1];

                            // dd($avatar_path[1]);
                            
                            // dd ($content_path); 
                            $dataDeal['image_content'] = json_encode($content_path);
                            $object_apply_json = array();
                            $object_apply_json['id'] = explode(",",$dataDeal['object-apply']);
                            if(isset($dataDeal['object-apply'])){
                                $dataDeal['object-apply'] = json_encode($object_apply_json);
                            }
                            // dd($dataDeal);
                            // $result = Deal::storeDeal($dataDeal);
                            // dd(strtotime($dataDeal['start-date']) <= time() &&  time()<= strtotime($dataDeal['end-date']));
                            $deal = new Deal;
                            $deal->merchants_id = $dataDeal['merchant_id'];
                            $deal->name = $dataDeal['titleIncentives'];
                            $deal->image_content = $dataDeal['image_content'];
                            $deal->apply_objects = $dataDeal["object-apply"];
                            $deal->radio_image = $dataDeal['radio_image'];
                            $deal->description = $dataDeal['contentIncentives'];
                            $deal->image_avatar = $dataDeal['image_avatar'];
                            $deal->start_day = str_replace("/", "-", $dataDeal['start-date']);
                            $deal->end_day = str_replace("/", "-", $dataDeal['end-date']);
                            $deal->slug = str_slug($dataDeal['titleIncentives'], "-".time());
                            
                            // $deal->save();

                            // dd($result);
                            if ($deal->save() == true ) {

                                
                                if( $deal->status == 1 || ( (strtotime($dataDeal['start-date']) <= time() && time() <= strtotime($dataDeal['end-date'])) && $deal->status != 2 )){
                                  //lay tat ca cac customer cua merchant va update lai
                                    $list = CustomerMerchant::where('merchants_id',$user_id)->get();
                                    
                                    $arrID = array();
                                    if ($list != null) {
                                        foreach ($list as $key => $xx) {
                                            array_push($arrID, $xx->customers_id);
                                        }
                                    }
                                    if (count($arrID)) {
                                        foreach ($arrID as $key => $id) {
                                            $u = Customer::find($id);
                                            if($u){
                                                $count = $u->count_deals;
                                                    Customer::where('id',$id)->update(array(
                                                        'count_deals' => $count+1
                                                    ));
                                                }
                                            }
                                            
                                        
                                    }
                                }

                                return Response::json([
                                    'success'   => true,
                                    'priority'  => 'success',
                                    'messages'  => 'Tạo chương trình ưu đãi thành công'
                                ]);
                            } else {
                                return Response::json([
                                    'success'   => false,
                                    'priority'  => 'warning',
                                    'messages'  => 'Tạo chương trình ưu đãi thất bại'
                                ]);
                            }
                        }
                        
                    } 
                } else {
                    return Response::json([
                        'success'   => false,
                        'priority'  => 'danger',
                        'messages'  => 'Bạn đã vượt quá số lượng chương trình ưu đãi được tạo trong tháng này'
                    ]);
                }
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            return redirect(URL('/500'));
            // return Response::json( ['error' => $error ], 500 );

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
    public function update(Request $request)
    {   
        try {
            $images = [];

            $user_id = Auth::merchant()->get()->id;
            $dataDeal = $request->all();
            // dd($dataDeal);


            // $dataDeal["merchant_id"] = $user_id;
            $checkCreateDealByUser = Merchant::where('id', $user_id)->first();
            // dd($checkCreateDealByUser);
            if ($checkCreateDealByUser != null) {
                //upload
                if (isset($dataDeal["image_avatar"])) {
                    $avatar_path = $this->upload("image_avatar");
                    // dd($avatar_path);
                    $dataDeal['image_avatar'] = $avatar_path[1];
                } else {
                    $avatar_path = "null";
                }
                if (isset($dataDeal["image_content"])) {
                    $content_path = $this->upload("image_content");
              
                    $dataDeal['image_content'] = json_encode($content_path);
                } else {
                    $content_path = "null";
                }
                // dd($avatar_path." - ".$content_path);

                if($avatar_path == false || $content_path == false){
                    return Response::json([
                        'success'   => false,
                        'priority'  => 'warning',
                        'messages'  => 'Size ảnh vượt quá kích thước hoặc sai định dạng cho phép'
                    ]);
                } else {
                    $object_apply_json = array();
                    $object_apply_json['id'] = explode(",",$dataDeal['apply_objects']);
                    $dataDeal['apply_objects'] = json_encode($object_apply_json);

                    // dd($dataDeal);
                    $result = Deal::where("id",$dataDeal["id"])->update($dataDeal);
                    if ($result == true ) {
                        return Response::json([
                            'success'   => true,
                            'priority'  => 'success',
                            'messages'  => 'Chỉnh sửa chương trình ưu đãi thành công'
                        ]);
                    } else {
                        return Response::json([
                            'success'   => false,
                            'priority'  => 'warning',
                            'messages'  => 'Chỉnh sửa chương trình ưu đãi thất bại'
                        ]);
                    }
                }
        
                
            } 
        } catch (Exception $e) {
            $error = $e->getMessage();
            return redirect(URL('/500'));
            // return Response::json( ['error' => $error ], 500 );

        }
        
    }

    //update status
    public function updateStatus( $id, $status )
    {
        $merchant_id = Auth::merchant()->get()->id;
        // dd($merchant_id);
        DB::table('deals')
            ->where('id', $id)
            ->where('merchants_id', $merchant_id)
            ->update(['status' => $status]);
    }
    public function delete(Request $request) {
        $merchant_id = Auth::merchant()->get()->id;
        $data = $request->all();
        // dd($data["id"]);
        $id = $data["id"];
        
        $result = DB::table('deals')
            ->where('id', $id)
            ->where('merchants_id', $merchant_id)
            ->update(['status' => 0]);
        if ($result == true ) {
            return Response::json([
                'success'   => true,
                'priority'  => 'success',
                'messages'  => 'Hủy chương trình ưu đãi thành công'
            ]);
        } else {
            return Response::json([
                'success'   => false,
                'priority'  => 'warning',
                'messages'  => 'Hủy chương trình ưu đãi thất bại'
            ]);
        }
    }

    public function active(Request $request) {
        $merchant_id = Auth::merchant()->get()->id;
        $data = $request->all();
        // dd($data["id"]);
        $id = $data["id"];
        
        $result = Deal::where('id', $id)
            ->where('merchants_id', $merchant_id)
            ->update(['status' => 1]);
        if ($result == true ) {

            // if( $result->status == 1 || ( (strtotime($result->start_day) <= time() && time() <= strtotime($result->end_day)) && $result->status != 2 )){
              //lay tat ca cac customer cua merchant va update lai
                $list = CustomerMerchant::where('merchants_id',$merchant_id)->get();
                
                $arrID = array();
                if ($list != null) {
                    foreach ($list as $key => $xx) {
                        array_push($arrID, $xx->customers_id);
                    }
                }
                if (count($arrID)) {
                    foreach ($arrID as $key => $id) {
                        $u = Customer::find($id);
                        if($u) {
                            $count = $u->count_deals;
                            Customer::where('id',$id)->update(array(
                                'count_deals' => $count+1
                            ));
                        }
                    }
                    
                }
            // }


            return Response::json([
                'success'   => true,
                'priority'  => 'success',
                'messages'  => 'Kích hoạt chương trình ưu đãi thành công'
            ]);
        } else {
            return Response::json([
                'success'   => false,
                'priority'  => 'warning',
                'messages'  => 'Kích hoạt chương trình ưu đãi thất bại'
            ]);
        }
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
