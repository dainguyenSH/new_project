<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use App\Http\Controllers\Controller;
use Route;
use Auth;
use View;
use File;
use Response;
use App\Merchant;
use App\Province;
use App\OptionValue;
use App\Statistical;
use App\Store;
use Redirect;

class AdminController extends Controller
{
    public function __construct(){
        
        if (Auth::admin()->check() == false) {
            return Redirect::to('login/admin?session=expired')->send();
            exit();
        }

        $titlePage = 'Quản trị SuperAdmin - AbbyCard';
        $className = substr(__CLASS__,21);
        $actionName = substr(strrchr(Route::currentRouteAction(),"@"),1);

        //Get All InfoMerchant
        
        // dd($getAllMerchant);
        $getPackage = OptionValue::where('options_id' ,2)->get();
        $month = OptionValue::where('options_id' ,5)->get();
        View::share(array(
            'titlePage'     => $titlePage,
            'className'     => $className,
            'actionName'    => $actionName,
            'package'       => $getPackage,
            'month'         => $month,
        ));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $getAllMerchant = Merchant::whereIn('kind',[1,2])->orderBy('id' , 'desc')->paginate(env('PAGE'));
        return view('admin.index', array(
            'merchant' => $getAllMerchant
        ));
    }
    public function postFilterMerchant(Request $request) {
        $data = $request->all();
        // dd($data);
        $page = intval($data['page']);


        $search_box = $data['search_box'];
        $count = Merchant::countMerchantByFilter($search_box);
        if ($count % env('SEARCH_PAGE') == 0) {
            $count_page = intval($count / env('SEARCH_PAGE'));
        } else {
            $count_page = intval($count / env('SEARCH_PAGE')) + 1;
        }
        $result = Merchant::getMerchantByFilter($search_box, $page);
        $htmlBoxInfo = View::make('html.list-merchant-detail' , array(
            'merchant'      => $result,
            "count_page" => $count_page,
            "current_page" => $page,
            

        ))->render();
        // dd($htmlBoxInfo);
        return Response::json([
            'success'   => true,
            'result' => $htmlBoxInfo,
            'count' => $count
        ]);
        
    }

    public function postActiveStatus(Request $request){
        $data = $request->all();
        $merchant = Merchant::find($data['id']);
        $getPackage = OptionValue::where('options_id' ,2)->get();

        $result = Merchant::where("id",$data['id'])->update(array(
            "active" => $data['active'],
            

        ));
        if ($result == true ) {


            $data = array();
            $data['name']   = json_decode($merchant->information,true)['fullname'];
            $data['trademark'] = $merchant->name;
            $data['package']   = checkPackageEmail($merchant->package);
            $data['email']  = $merchant->email;

            $html = 'emails.4';
            $sendMail = sendEmailFromMandrill('Kích hoạt thành công', $merchant->email, $request->name, $html, ['data'=>$data]);
            
            return Response::json([
                'success'   => true,
                'priority'  => 'success',
                'messages'  => 'Xác nhận thành viên thành công'
            ]);
        } else {
            return Response::json([
                'success'   => false,
                'priority'  => 'warning',
                'messages'  => 'Xác nhận gói thành viên thất bại'
            ]);
        }
    }

    public function postUpdateInfo(Request $request) {
        $data = $request->all();
        // dd($data['active']);
        // dd($data);
        // dd($data['mounth']);
        
        
        
        $info = Merchant::where('id',$data['id'])->first();
        if(($info->package == $data['package']) || ($info->package_status != $data['package'])){
            $package_status = $info->package_status;
        } else {
            $package_status = 0;
        }
        // $end_time = time() + $data['mounth']*30*24*3600;
        // dd($data['mounth']);
        if($data['mounth'] != 0 ){
            $start_time = date('Y-m-d H:i:s', time());
            $end_time = strtotime('+ '.$data['mounth'].' month', strtotime($start_time));
            $end_time = date('Y-m-d H:i:s', $end_time);
        } else {
            $start_time = $info->start_day;
            
            $end_time = $info->end_day;
        }

        // Check Store Active
        $infoStoreChange = OptionValue::find($data['package'])->info;
        $maxStorePackageChange = json_decode($infoStoreChange)->stores_max;

        $nowStoreActive = Store::where('merchants_id' , $data['id'])->where('active' , 1)->count();

        if ( $nowStoreActive <= $maxStorePackageChange ) {
            $result = Merchant::where("id",$data['id'])->update(array(
                'package' => $data['package'],
                'package_status' => $package_status,
                "active" => $data['active'],
                "start_day" => $start_time,
                "end_day" => $end_time,

            ));
            if ($result == true ) {

                if($data['active'] == 1) {
                    if(Statistical::where('merchants_id',$data['id'])->count() == 0){
                        Statistical::create(['merchants_id' => $data['id'], 'status' => 1,'year' => date("Y")]);
                    } else {
                        Statistical::where('merchants_id',$data['id'])->first()->update(['status' => 1]);
                    }
                } else {
                    if(Statistical::where('merchants_id',$data['id'])->count() != 0){
                       Statistical::where('merchants_id',$data['id'])->first()->update(['status' => 0]);
                    } 
                }

                return Response::json([
                    'success'   => true,
                    'priority'  => 'success',
                    'messages'  => 'Cập nhật thông tin thành viên thành công'
                ]);
            } else {
                return Response::json([
                    'success'   => false,
                    'priority'  => 'warning',
                    'messages'  => 'Cập nhật thông tin thành viên thất bại'
                ]);
            }
        } else {
            return response()->json([
                'success'   => false,
                'title'     => 'Vượt mức giới hạn',
                'messages'  => 'Liên hệ với Merchant ngưng hoạt động một số cửa hàng.',
            ]);
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

    public function postUpdatePackage(Request $request) {
        // dd("kdfjaldkfa");   
        $data = $request->all();
        // dd($data);
        $user_id = $data['user_id'];
        $package_status = $data['package_status'];

        
        $infoStoreChange = OptionValue::find($package_status)->info;
        $maxStorePackageChange = json_decode($infoStoreChange)->stores_max;

        $nowStoreActive = Store::where('merchants_id' , $user_id)->where('active' , 1)->count();

        if ( $nowStoreActive <= $maxStorePackageChange ) {
            $result = Merchant::where("id",$user_id)->update(array(
                'package' => $package_status,
                'package_status' => 0,
            ));
            if ($result == true ) {
                $merchant = Merchant::find($user_id);

                $data = array();
                $data['name']       = json_decode($merchant->information,true)['fullname'];
                $data['package']    = checkPackageEmail($package_status);
                $data['trademark']  = $merchant->name;
                $data['email']      = $merchant->email;

                $html = 'emails.6';
                $sendMail = sendEmailFromMandrill('Kích hoạt thành công gói dịch vụ '. $data['package'], $data['email'], $data['name'], $html, ['data'=>$data]);

                return Response::json([
                    'success'   => true,
                    'priority'  => 'success',
                    'messages'  => 'Cập nhật gói thành viên thành công'
                ]);
            } else {
                return Response::json([
                    'success'   => false,
                    'priority'  => 'warning',
                    'messages'  => 'Cập nhật gói thành viên thất bại'
                ]);
            }
        } else {
            return response()->json([
                'success'   => false,
                'title'     => 'Vượt mức giới hạn',
                'messages'  => 'Liên hệ với Merchant ngưng hoạt động một số cửa hàng.',
            ]);
        }

        
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function getMerchant($id)
    {
        $merchantId = $id;
        $merchant = Merchant::find($id);

        // dd($merchant);

        if ( $merchant == null ) {
            exit('404 Page not found');
        } else {

            $fields = OptionValue::select('id', 'name')->where('options_id',1)->get();
            $provinces = Province::select('provinces_id', 'name')->get();

            // $getInfoMerchant = Merchant::find($id);
            $getAllStore = Store::where('merchants_id' , $merchantId)->orderby('active','desc')->get();
            $info = json_decode($merchant->information, true);
            $infoCard = json_decode($merchant->card_info, true);

            //Get Account shop
            $accountShopActive = Store::where('merchants_id', $id)->where('active' , 1)->get();

            //Get Current Packages
            $currentPackage = OptionValue::select('info')->where('id', $merchant->package)->first();

            $infoUpgradePackages = OptionValue::where('id', $merchant->package_status )->first();

            // $getInfoMerchant = Merchant::where('id' , Auth::merchant()->get()->id)->first();


            return view('admin.detail-merchant' , array(
                'fields'        => $fields,
                'provinces'     => $provinces,
                'info'          => $info,
                'infoCard'      => $infoCard,
                'store'         => $getAllStore,
                'infoUpdrage'   => $infoUpgradePackages,
                'account'       => $accountShopActive,
                'merchant'      => $merchant,
                'merchantId'    => $merchantId,
            ));
        }
    }

    /**
     * List All Parner
     */

    public function getPartner() {
        $partners = Merchant::where('kind',4)->paginate(env('PAGE'));
        return view('admin.partner')->with('partners' , $partners);
    }


    /**
     * Edit Partner
     */

    public function getEditPartner($id) {
        $data = Merchant::find($id);
        if ($data) {
            if ($data->kind == 4) {
                return view('admin.edit-partner', array(
                        'merchant'  => $data,
                    ));
            } else {
                return 404;
            }
        } else {
            return 404;
        }
    }


    /**
     * Edit Partner
     */

    public function getEditNewMerchant($id) {
        $data = Merchant::find($id);
        if ($data) {
            if ($data->kind == 5) {
                return view('admin.edit-new-merchant', array(
                        'data'  => $data,
                    ));
            } else {
                return 404;
            }
        } else {
            return 404;
        }
    }

    public function getEditBooMerchant($id) {
        $data = Merchant::find($id);
        if ($data) {
            if ($data->kind == 3) {
                return view('admin.edit-boo-merchant', array(
                        'data'  => $data,
                    ));
            } else {
                return 404;
            }
        } else {
            return 404;
        }
    }

    

    /**
     * layout Add new partner
     */

    public function getAddNewPartner() {
        return view('admin.add-new-partner');
    }


    /**
     * store Partner
     */

    public function postStorePartner(Request $request) {

        $cardInfo = json_encode($request->only(['level', 'age', 'unit']));
        

        $validator = Validator::make(
                [
                    'name'         => $request->merchantName,
                    'logo'          => $request->merchantLogo,
                    'unit'          => $request->unit,

                ],
                [
                    'name'          => 'required',
                    'logo'          => 'required',
                    'unit'          => 'required|numeric'
                ],
                [
                    'name.required'     => "Tên thương hiệu không được trống",
                    'logo.required'     => "Vui lòng chọn Logo",
                    'unit.required'     => "Giá trị điểm nhập vào không được để trống",
                    'unit.numeric'      => "Giá trị điểm nhập vào phải là số",
                ]
        );
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
            if ($request->level != null) {

                    if ($request->merchantLogo != "") {
                        $upload_logo_url = uploadBase64WithResize($request->merchantLogo,'upload/merchant-logo/',"merchant_logo_".time(), 300, 300);
                    } else {
                        $upload_logo_url = 'default.png';
                    }

                    if($request->merchantName && $request->merchantLogo != "") {
                    $addNewPartner = new Merchant;
                    $addNewPartner->user_name   = NULL;
                    $addNewPartner->email       = NULL;
                    $addNewPartner->name        = $request->merchantName;
                    $addNewPartner->logo        = $upload_logo_url;
                    $addNewPartner->text_color  = $request->textColor;
                    $addNewPartner->color       = $request->backgroundColor;
                    $addNewPartner->card_info   = $cardInfo;
                    $addNewPartner->kind        = 4;
                    $addNewPartner->active      = 1;
                    $addNewPartner->save();

                    if ($addNewPartner != null) {
                        return response()->json([
                                'success'   => true,
                                'title'     => 'Thêm mới thành công',
                                'messages'  => 'Thêm mới đối tác thành công',
                            ]);  
                    } else {
                        return response()->json([
                                'success'   => false,
                                'title'     => 'Thêm mới thất bại',
                                'messages'  => 'Thêm mới đối tác thất bại',
                            ]);  
                    }

                }
            } else {
                return response()->json([
                    'success'   => false,
                    'title'     => 'Chưa tạo thông tin hạng thẻ',
                    'messages'  => 'Vui lòng thêm mới hạng thẻ',
                ]);  
            }
        }
    }


    /**
     * update Partner
     */

    public function postUpdatePartner(Request $request) {
        $cardInfo = json_encode($request->only(['level', 'age', 'unit']));


        $validator = Validator::make(
                [
                    'name'         => $request->merchantName,
                    'logo'          => $request->merchantLogo,
                    'unit'          => $request->unit,

                ],
                [
                    'name'          => 'required',
                    'logo'          => 'required',
                    'unit'          => 'required|numeric'
                ],
                [
                    'name.required'     => "Tên thương hiệu không được trống",
                    'logo.required'     => "Vui lòng chọn Logo",
                    'unit.required'     => "Giá trị điểm nhập vào không được để trống",
                    'unit.numeric'      => "Giá trị điểm nhập vào phải là số",
                ]
        );
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

            if ($request->level != null) {

                    //Check update Logo
                    if ($request->currentLogo == "") {
                        $logo = uploadBase64WithResize($request->merchantLogo,'upload/merchant-logo/',"merchant_logo_".time(), 300, 300);
                        File::delete($request->nowLogo);
                    } else {
                        $logo = $request->currentLogo;
                    }
                    $updatePartner = Merchant::where('id', $request->id)->update([
                            'user_name'   => NULL,
                            'email'       => NULL,
                            'name'        => $request->merchantName,
                            'logo'        => $logo,
                            'text_color'  => $request->textColor,
                            'color'       => $request->backgroundColor,
                            'card_info'   => $cardInfo,
                            'kind'        => 4,
                            'active'      => $request->active,
                        ]);

                    if ($updatePartner != null) {
                        return response()->json([
                                'success'   => true,
                                'title'     => 'Cập nhât thành công',
                                'messages'  => 'Cập nhật đối tác thành công',
                            ]);  
                    } else {
                        return response()->json([
                                'success'   => false,
                                'title'     => 'Cập nhât thất bại',
                                'messages'  => 'Cập nhât đối tác thất bại',
                            ]);  
                    }

            } else {
                return response()->json([
                    'success'   => false,
                    'title'     => 'Chưa tạo thông tin hạng thẻ',
                    'messages'  => 'Vui lòng thêm mới hạng thẻ',
                ]);  
            }
        }
    }



    /**
     * Show all new partner
     *
     */
    public function getNewMerchant()
    {
        $data = Merchant::where('kind',5)->paginate(env('PAGE'));
        return view('admin.new-merchant' , array(
                'newMerchant' => $data
            ));
    }


    /**
     * Add new Merchant
     *
     */
    public function getAddNewMerchant()
    {
        return view('admin.add-new-merchant');
    }

    
    /**
     * Add new Merchant
     */
    public function postAddNewMerchant(Request $request)
    {
        $validator = Validator::make(
                [
                    'name'          => $request->merchantName,
                    'background'    => $request->backgroundColor,
                    'text'          => $request->textColor,
                    'logo'          => $request->merchantLogo,

                ],
                [
                    'name'          => 'required',
                    'background'    => 'required',
                    'text'          => 'required',
                    'logo'          => 'required'
                ],
                [
                    'name.required'         => "Tên thương hiệu không được trống",
                    'background.required'   => "Vui lòng chọn màu nền",
                    'text.required'         => "Vui lòng chọn màu chữ",
                    'logo.required'          => "Vui lòng chọn Logo",
                ]
        );
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
            if ($request->merchantLogo != null) {
                $logo = uploadBase64WithResize($request->merchantLogo,'upload/merchant-logo/',"merchant_logo_".time(), 300, 300);
            } else {
                $logo = 'default.png';
            }
            $result = Merchant::create([
                    'user_name'     => NULL,
                    'email'         => NULL,
                    'name'          => $request->merchantName,
                    'color'         => $request->backgroundColor,
                    'text_color'    => $request->textColor,
                    'logo'          => $logo,
                    'kind'          => 5,
                    'active'        => 1,
                ]);

            if ($result != null) {
                return response()->json([
                    'success'   => true,
                    'title'     => 'Thêm mới thành công',
                    'messages'  => 'Thêm mới đối tác thành công',
                ]);  
            } else {
                return response()->json([
                    'success'   => false,
                    'title'     => 'Thêm mới thất bại',
                    'messages'  => 'Thêm mới merchant thất bại. Vui lòng thử lại',
                ]);  
            }
        }
    }


    /**
     * Add Boo Merchant
     */
    public function postAddBooMerchant(Request $request)
    {
        $validator = Validator::make(
                [
                    'name'          => $request->merchantName,
                    'background'    => $request->backgroundColor,
                    'text'          => $request->textColor,
                    'logo'          => $request->merchantLogo,
                    'desc'          => $request->desc,

                ],
                [
                    'name'          => 'required',
                    'background'    => 'required',
                    'text'          => 'required',
                    'logo'          => 'required',
                    'desc'          => 'required',
                ],
                [
                    'name.required'         => "Tên thương hiệu không được trống",
                    'background.required'   => "Vui lòng chọn màu nền",
                    'text.required'         => "Vui lòng chọn màu chữ",
                    'logo.required'         => "Vui lòng chọn Logo",
                    'desc.required'         => "Vui lòng nhập mô tả",
                ]
        );
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
            if ($request->merchantLogo != null) {
                $logo = uploadBase64WithResize($request->merchantLogo,'upload/merchant-logo/',"merchant_logo_".time(), 300, 300);
            } else {
                $logo = 'default.png';
            }
            $result = Merchant::create([
                    'user_name'     => NULL,
                    'email'         => NULL,
                    'name'          => $request->merchantName,
                    'color'         => $request->backgroundColor,
                    'text_color'    => $request->textColor,
                    'card_info'     => $request->desc,
                    'logo'          => $logo,
                    'kind'          => 3,
                    'active'        => 1,
                ]);

            if ($result != null) {
                return response()->json([
                    'success'   => true,
                    'title'     => 'Thêm mới thành công',
                    'messages'  => 'Thêm mới đối tác thành công',
                ]);  
            } else {
                return response()->json([
                    'success'   => false,
                    'title'     => 'Thêm mới thất bại',
                    'messages'  => 'Thêm mới merchant thất bại. Vui lòng thử lại',
                ]);  
            }
        }
    }



    /**
     * Update new Merchant
     */
    public function postUpdateNewMerchant(Request $request)
    {
        // var_dump($request->all());
        // die('da vao');
        $validator = Validator::make(
                [
                    'name'          => $request->merchantName,
                    'background'    => $request->backgroundColor,
                    'text'          => $request->textColor,
                    'logo'          => $request->merchantLogo,

                ],
                [
                    'name'          => 'required',
                    'background'    => 'required',
                    'text'          => 'required',
                    'logo'          => 'required'
                ],
                [
                    'name.required'         => "Tên thương hiệu không được trống",
                    'background.required'   => "Vui lòng chọn màu nền",
                    'text.required'         => "Vui lòng chọn màu chữ",
                    'logo.required'          => "Vui lòng chọn Logo",
                ]
        );
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


            if ($request->currentLogo == "") {
                $logo = uploadBase64WithResize($request->merchantLogo,'upload/merchant-logo/',"merchant_logo_".time(), 300, 300);
                File::delete($request->nowLogo);
            } else {
                $logo = $request->currentLogo;
            }
            $result = Merchant::where('id' , $request->id)->update([
                    'user_name'     => NULL,
                    'email'         => NULL,
                    'name'          => $request->merchantName,
                    'color'         => $request->backgroundColor,
                    'text_color'    => $request->textColor,
                    'logo'          => $logo,
                    'kind'          => 5,
                    'active'        => $request->active,
                ]);

            if ($result != null) {
                return response()->json([
                    'success'   => true,
                    'title'     => 'Cập nhật thành công',
                    'messages'  => 'Cập nhật thành công',
                ]);  
            } else {
                return response()->json([
                    'success'   => false,
                    'title'     => 'Cập nhật thất bại',
                    'messages'  => 'Cập nhật thất bại. Vui lòng thử lại',
                ]);  
            }
        }
    }


    /**
     * Update Boo Merchant
     */
    public function postUpdateBooMerchant(Request $request)
    {
        // var_dump($request->all());
        // die('da vao');
        $validator = Validator::make(
                [
                    'name'          => $request->merchantName,
                    'background'    => $request->backgroundColor,
                    'text'          => $request->textColor,
                    'logo'          => $request->merchantLogo,
                    'desc'          => $request->desc,

                ],
                [
                    'name'          => 'required',
                    'background'    => 'required',
                    'text'          => 'required',
                    'logo'          => 'required',
                    'desc'          => 'required',
                ],
                [
                    'name.required'         => "Tên thương hiệu không được trống",
                    'background.required'   => "Vui lòng chọn màu nền",
                    'text.required'         => "Vui lòng chọn màu chữ",
                    'logo.required'         => "Vui lòng chọn Logo",
                    'desc.required'         => "Vui lòng nhập mô tả thẻ",
                ]
        );
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


            if ($request->currentLogo == "") {
                $logo = uploadBase64WithResize($request->merchantLogo,'upload/merchant-logo/',"merchant_logo_".time(), 300, 300);
                File::delete($request->nowLogo);
            } else {
                $logo = $request->currentLogo;
            }
            $result = Merchant::where('id' , $request->id)->update([
                    'user_name'     => NULL,
                    'email'         => NULL,
                    'name'          => $request->merchantName,
                    'color'         => $request->backgroundColor,
                    'text_color'    => $request->textColor,
                    'card_info'          => $request->desc,
                    'logo'          => $logo,
                    'kind'          => 3,
                    'active'        => $request->active,
                ]);

            if ($result != null) {

                return response()->json([
                    'success'   => true,
                    'title'     => 'Cập nhật thành công',
                    'messages'  => 'Cập nhật thành công',
                ]);  

            } else {
                return response()->json([
                    'success'   => false,
                    'title'     => 'Cập nhật thất bại',
                    'messages'  => 'Cập nhật thất bại. Vui lòng thử lại',
                ]);  
            }
        }
    }

    /**
     * Boo
     */

    public function getBoo() {
        $data = Merchant::where('kind',3)->paginate(env('PAGE'));
        return view('admin.boo-merchant' , array(
                'booMerchant' => $data
            ));
    }

    /**
     * Add Boo Merchant
     */

    public function getAddBoo() {
        return view('admin.add-boo');
    }


    /**
     * Update Infomation Merchant
     */

    public function postSaveEditInfomationMerchant(Request $request)
    {
        
        if ($request->check_logo != null){
            $upload_logo_url = $request->check_logo;
        } else {
            $upload_logo_url = uploadBase64WithResize($request->logo,'upload/merchant-logo/',"merchant_logo_".time(), 750, 750);
        }

        if ($request->color == '#ffffff') {
            $color = '#f94876';
        } elseif ($request->color == '000000') {
            $color = '#f94876';
        } else {
            $color = $request->color;
        }
        
        // dd($upload_logo_url);
        $infoMerchants_Json = array();
            $infoMerchants_Json['fullname']  = $request->fullname;
            $infoMerchants_Json['role']      = $request->role;
            $infoMerchants_Json['day']       = $request->day;
            $infoMerchants_Json['month']     = $request->month;
            $infoMerchants_Json['logo']      = $upload_logo_url;
            $infoMerchants_Json['year']      = $request->year;
            $infoMerchants_Json['address']   = $request->address;
            $infoMerchants_Json['province']  = $request->province;
            $infoMerchants_Json['district']  = $request->district;
            $infoMerchants_Json['phone']     = $request->phone;
            $infoMerchants_Json['email']     = $request->email;
        $updateMerchant = Merchant::where('id' , $request->id)->update(array(
                'name'          => $request->trademark,
                'logo'          => $upload_logo_url,
                'color'         => $color,
                'field'         => $request->field,
                'information'   =>json_encode($infoMerchants_Json)
        ));

        if ($updateMerchant != null) {
            return Response::json([
                'success'   => true,
                'priority'  => 'success',
                'messages'  => 'Cập nhật Merchants thành công'
            ]);
        } else {
            return Response::json([
                'success'   => false,
                'priority'  => 'warning',
                'messages'  => 'Cập nhật Merchants thất bại'
            ]);
        }        
    }

    public function postChangeCardInfo(Request $request) {


        $data = [];

        $data['id'] = intval($request->cardtype);

        $data['value'] =[];

        if ($request->cardtype == "1") { //vang bac dong
            
            for ($i=10; $i <= 12  ; $i++) { 

                $info_i = OptionValue::find($i);
                
                $temp = [];
                $temp['id'] = $i; //
                $temp['value'] = [];
                $t = [];
                
                $t['point'] = intval($request->settings[$i-10][0]);
                $t['bouns'] = intval($request->settings[$i-10][1]);
                $t['unit'] = intval($request->unit);
                $t['background_color'] = json_decode($info_i->info,true)['background_color'];
                $t['text_color'] = json_decode($info_i->info,true)['text_color'];

                array_push($temp['value'], $t);
                array_push($data['value'], $temp);
            }

        }
        else if ($request->cardtype == "2") { //vip, thanh vien
            for ($i=13; $i <=14 ; $i++) { 
               $info_i = OptionValue::find($i);
                
                $temp = [];
                $temp['id'] = $i; //
                $temp['value'] = [];
                $t = [];
                
                $t['point'] = intval($request->settings[$i-13][0]);
                $t['bouns'] = intval($request->settings[$i-13][1]);
                $t['unit'] = intval($request->unit);
                $t['background_color'] = json_decode($info_i->info,true)['background_color'];
                $t['text_color'] = json_decode($info_i->info,true)['text_color'];

                array_push($temp['value'], $t);
                array_push($data['value'], $temp);
            }
        }
        else if ($request->cardtype == "3") {  //vvip, vip, thanh vien
            for ($i=23; $i <=25 ; $i++) { 
               $info_i = OptionValue::find($i);
                
                $temp = [];
                $temp['id'] = $i; //
                $temp['value'] = [];
                $t = [];
                
                $t['point'] = intval($request->settings[$i-23][0]);
                $t['bouns'] = intval($request->settings[$i-23][1]);
                $t['unit'] = intval($request->unit);
                $t['background_color'] = json_decode($info_i->info,true)['background_color'];
                $t['text_color'] = json_decode($info_i->info,true)['text_color'];

                array_push($temp['value'], $t);
                array_push($data['value'], $temp);
            }
        }

        //thang newbie
        $info_nb = OptionValue::find(26);
        $temp = [];
        $temp['id'] = $i; //newbie
        $temp['value'] = [];
        $t = [];
        
        $t['point'] = 0;
        $t['bouns'] = 0;
        $t['unit'] = intval($request->unit);
        $t['background_color'] = json_decode($info_nb->info,true)['background_color'];
        $t['text_color'] = json_decode($info_nb->info,true)['text_color'];

        array_push($temp['value'], $t);
        array_push($data['value'], $temp);


        //update merchant
        $updateMerchant = Merchant::where('id', $request->merchantId)->update(['info' => $data]);
        
        if ($updateMerchant != null) {
            return Response::json([
                'success'   => true,
                'priority'  => 'success',
                'messages'  => 'Cập nhật Merchants thành công'
            ]);
        } else {
            return Response::json([
                'success'   => false,
                'priority'  => 'warning',
                'messages'  => 'Cập nhật Merchants thất bại'
            ]);
        }    
        
    }

    public function getKpis() {
        return view('admin.kpis');
    }



}
