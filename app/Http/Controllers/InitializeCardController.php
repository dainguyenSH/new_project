<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomerMerchant;
use App\Http\Requests;
use Validator;
use App\Http\Controllers\Controller;
use App\RegisterPackage;
use Route;
use View;
use Hash;
use App\Merchant;
use App\CardOption;
use App\OptionValue;
use App\Store;
use App\Province;
use App\District;
use Auth;
use Response;
use File;
use Redirect;


class InitializeCardController extends Controller
{

    public function __construct(){

        $this->middleware('merchant');

        $titlePage = 'Thẻ thành viên - AbbyCard';
        $className = substr(__CLASS__,21);
        $actionName = substr(strrchr(Route::currentRouteAction(),"@"),1);
        
        // $getInfoMerchant = Merchant::where('id' , Auth::merchant()->get()->id)->first();
        // $infoPackages = OptionValue::where('id', $getInfoMerchant->package )->first();

        // $count_member = CustomerMerchant::where('merchants_id',Auth::merchant()->get()->id)->where('status',1)->count();

        View::share(array(
            'titlePage'         => $titlePage,
            'className'         => $className,
            'actionName'        => $actionName,
            // 'merchant'          => $getInfoMerchant,
            // 'package'           => $infoPackages,
            // 'count_member'      => $count_member
        ));
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

        $merchantId = Auth::merchant()->get()->id;

        $fields = OptionValue::select('id', 'name')->where('options_id',1)->get();
        $provinces = Province::select('provinces_id', 'name')->get();

        $getInfoMerchant = Merchant::where('id' , $merchantId)->first();
        $getAllStore = Store::where('merchants_id' , $merchantId)->orderby('active','desc')->get();
        $info = json_decode($getInfoMerchant->information, true);

        $infoCard = json_decode($getInfoMerchant->card_info, true);

        //Get Account shop
        $accountShopActive = Store::where('merchants_id', Auth::merchant()->get()->id)->get();

        //Get Current Packages
        $currentPackage = OptionValue::select('info')->where('id', Auth::merchant()->get()->package)->first();

        return view('merchant.initialize-card' , array(
                'fields'        => $fields,
                'provinces'     => $provinces,
                'info'          => $info,
                'infoCard'      => $infoCard,
                'store'         => $getAllStore,
                'account'       => $accountShopActive,
                'merchant'      => $getInfoMerchant
            ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        echo "string";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unlinkImage(Request $request) {
        $data = $request->all();
        // dd($data['url']);
        $url = $data['url'];
        $result = unlink($url);
        if ($result == true ) {
            return Response::json([
                'success'   => true,
                'priority'  => 'success',
                'messages'  => 'Xóa ảnh đại diện cũ thành công'
            ]);
        } else {
            return Response::json([
                'success'   => false,
                'priority'  => 'warning',
                'messages'  => 'Xóa ảnh đại diện cũ thất bại'
            ]);
        }

    }
    public function postInfomerchant(Request $request)
    {   
        if ($request->check_logo != null){
            $upload_logo_url = $request->check_logo;
        } else {
            $upload_logo_url = uploadBase64WithResize($request->logo,'upload/merchant-logo/',"merchant_logo_".time(), 750, 750);
            File::delete($request->current_i_m_g_s);
        }

        if ($request->color == '#ffffff') {
            $color = '#f94876';
        } elseif ($request->color == '000000') {
            $color = '#f94876';
        } else {
            $color = $request->color;
        }

        $nameMerchant = preg_replace("/ {2,}/", " ", $request->trademark);

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
        $updateMerchant = Merchant::where('id' , Auth::merchant()->get()->id)->update(array(
                'name'          => $nameMerchant,
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCreatetypecard(Request $request)
    {
        // var_dump($request->all());die();
        // dd($request->settings[0][0]);


        // === 1 ~ ~ LV
        // === 0 ~ ~ Chop

        // 1 Free
        // 2 Item

        // 11 Vang Bac Dong
        // 22 VVIP, VIP, Thanh Vien
        // 33 VIP , Thanh Vien

        // switch ( $request->choiced ) {
        //     case 1:
        //         $card_type = 4;
        //         break;

        //     case 0:
        //         $card_type = 3;
        //         break;
            
        //     default:
        //         $card_type = "";
        //         break;
        // }

        // $cardOption =  array();
        // switch ( $request->selectOption ) {
        //     case '1':
        //         //Đang chọn Free 1 sản phẩm ~ id optionvalue = 15
        //         $cardOption['id'] = 15;
        //         break;

        //     case '2':
        //         //Đang chọn discount % ~ id optionvalue = 16
        //         $cardOption['id'] = 16;
        //         break;

        //     case '11':
        //         //Đang chọn Vàng bạc đồng ~ id optionvalue = 10,11,12
        //         $cardOption['id'] = '10,11,12';
        //         break;

        //     case '33':
        //         //Đang chọn VIP , thành viên ~ id optionvalue = 13,14
        //         $cardOption['id'] = '13,14';
        //         break;
            
        //     default:
        //         $cardOption['id'] = "";
        //         break;
        // }
        // // var_dump($cardOption);die();

        // //Save Type card
        // if ( $card_type != "" && $cardOption != null ) {
        //     $result = Merchant::where( 'id', Auth::merchant()->get()->id )->update(array(
        //         'card_type'     => $card_type,
        //         'card_info'     => json_encode($cardOption),
        //     ));

        //     if ($result != null) {
        //         return Response::json([
        //             'success'   => true,
        //             'priority'  => 'success',
        //             'messages'  => 'Thêm tùy chọn Card thành công'
        //         ]);
        //     } else {
        //         return Response::json([
        //             'success'   => false,
        //             'priority'  => 'danger',
        //             'messages'  => 'Thêm tùy chọn Card thất bại'
        //         ]);
        //     }
        // }

        // var_dump($card_type);die();

        // $value = array();
        // $value["id"] = 3;
        // $value["value"] = "15%";

        // $value1 = array();
        // $value1["id"] = 5;
        // $value1["value"] = "20%";

        // $level3 = array();

        if (intval($request->settings[0][0]) == 0 ) {
            return Response::json([
                'success'   => false,
                'priority'  => 'danger',
                'messages'  => 'Thiết lập thông tin thẻ thất bại'
            ]);
        } else {
            $selectOptions = array();
            switch ( $request->selectOption ) {
                case '1':
                    //Đang chọn Free 1 sản phẩm ~ id optionvalue = 15
                    $selectOptions['id'] = 15;
                    $selectOptions['value'] = array();

                    $temp = array();
                    $temp['id']= 15;
                    $temp['value']= array();
                    if (count($request->settings) > 0) {
                        foreach ($request->settings as $key => $st) {
                            $x = array();
                            $x['point'] = intval($st[0]);
                            $x['bouns'] = intval($st[1]);
                            $x['unit'] = intval($st[2]);
                            $x['background_color'] = '';
                            $x['text_color'] = '';
                            array_push($temp['value'], $x);
                        }
                    }
                    array_push($selectOptions['value'], $temp);
                    $kind = 1;

                    break;

                case '2':
                    //Đang chọn discount % ~ id optionvalue = 16

                    $selectOptions['id'] = 16;
                    $selectOptions['value'] = array();

                    $temp = array();
                    $temp['id']= 16;
                    $temp['value']= array();
                    if (count($request->settings) > 0) {
                        foreach ($request->settings as $key => $st) {
                            $x = array();
                            $x['point'] = intval($st[0]);
                            $x['bouns'] = intval($st[1]);
                            $x['unit'] = $st[2];
                            $x['background_color'] = '';
                            $x['text_color'] = '';
                            array_push($temp['value'], $x);
                        }
                    }
                    array_push($selectOptions['value'], $temp);
                    $kind = 1;
                    break;

                case '11':
                    //Đang chọn Vàng bạc đồng ~ id optionvalue = 10,11,12
                    $selectOptions['id'] = 1; // vang bac dong
                    $selectOptions['value'] = array();

                    $temp =array();
                    $temp['id'] = 10;
                    $temp['value'] = array();

                    $vang = array();
                    $vang['point'] = intval($request->settings[0][0]) or 10000;
                    $vang['bouns'] = intval($request->settings[0][1]) or 10000;
                    $vang['unit'] = intval($request->settings[0][2]) or 10000;
                    $vang['background_color'] = json_decode(OptionValue::find(10)->info,true)['background_color'];
                    $vang['text_color'] = json_decode(OptionValue::find(10)->info,true)['text_color'];
                    array_push($temp['value'], $vang);

                    $temp1 =array();
                    $temp1['id'] = 11;
                    $temp1['value'] = array();

                    $bac = array();
                    $bac['point'] = intval($request->settings[1][0]) or 10000;
                    $bac['bouns'] = intval($request->settings[1][1]) or 10000;
                    $bac['unit'] = intval($request->settings[1][2]) or 10000;
                    $bac['background_color'] = json_decode(OptionValue::find(11)->info,true)['background_color'];
                    $bac['text_color'] = json_decode(OptionValue::find(11)->info,true)['text_color'];
                    array_push($temp1['value'], $bac);


                    $temp2 =array();
                    $temp2['id'] = 12;
                    $temp2['value'] = array();

                    $dong = array();
                    $dong['point'] = intval($request->settings[2][0]) or 10000;
                    $dong['bouns'] = intval($request->settings[2][1]) or 10000;
                    $dong['unit'] = intval($request->settings[2][2]) or 10000;
                    $dong['background_color'] = json_decode(OptionValue::find(12)->info,true)['background_color'];
                    $dong['text_color'] = json_decode(OptionValue::find(12)->info,true)['text_color'];
                    array_push($temp2['value'], $dong);


                    $temp3 =array();
                    $temp3['id'] = 26;
                    $temp3['value'] = array();

                    $newbe = array();
                    $newbe['point'] = 0;
                    $newbe['bouns'] = 0;
                    $newbe['unit'] = intval($request->settings[0][2]) or 10000;
                    $newbe['background_color'] = json_decode(OptionValue::find(26)->info,true)['background_color'];
                    $newbe['text_color'] = json_decode(OptionValue::find(26)->info,true)['text_color'];
                    array_push($temp3['value'], $newbe);

                    array_push($selectOptions['value'], $temp);
                    array_push($selectOptions['value'], $temp1);
                    array_push($selectOptions['value'], $temp2);
                    array_push($selectOptions['value'], $temp3);
                    $kind = 2;
                    break;

                case '22':
                    //Đang chọn VVIP, VIP, thành viên ~ id optionvalue = 23,24,25
                    $selectOptions['id'] = 3; // vvip, vip, thanh vien
                    $selectOptions['value'] = array();

                    $selectOptions['value'] = array();

                    $temp =array();
                    $temp['id'] = 23;
                    $temp['value'] = array();

                    $vvip = array();
                    $vvip['point'] = intval($request->settings[0][0]) or 10000;
                    $vvip['bouns'] = intval($request->settings[0][1]) or 10000;
                    $vvip['unit'] = intval($request->settings[0][2]) or 10000;
                    $vvip['background_color'] = json_decode(OptionValue::find(23)->info,true)['background_color'];
                    $vvip['text_color'] = json_decode(OptionValue::find(23)->info,true)['text_color'];
                    array_push($temp['value'], $vvip);

                    $temp1 =array();
                    $temp1['id'] = 24;
                    $temp1['value'] = array();

                    $vip = array();
                    $vip['point'] = intval($request->settings[1][0]) or 10000;
                    $vip['bouns'] = intval($request->settings[1][1]) or 10000;
                    $vip['unit'] = intval($request->settings[1][2]) or 10000;
                    $vip['background_color'] = json_decode(OptionValue::find(24)->info,true)['background_color'];
                    $vip['text_color'] = json_decode(OptionValue::find(24)->info,true)['text_color'];
                    array_push($temp1['value'], $vip);


                    $temp2 =array();
                    $temp2['id'] = 25;
                    $temp2['value'] = array();

                    $thanhvien = array();
                    $thanhvien['point'] = intval($request->settings[2][0]) or 10000;
                    $thanhvien['bouns'] = intval($request->settings[2][1]) or 10000;
                    $thanhvien['unit'] = intval($request->settings[2][2]) or 10000;
                    $thanhvien['background_color'] = json_decode(OptionValue::find(25)->info,true)['background_color'];
                    $thanhvien['text_color'] = json_decode(OptionValue::find(25)->info,true)['text_color'];
                    array_push($temp2['value'], $thanhvien);

                    $temp3 =array();
                    $temp3['id'] = 26;
                    $temp3['value'] = array();

                    $newbe = array();
                    $newbe['point'] = 0;
                    $newbe['bouns'] = 0;
                    $newbe['unit'] = intval($request->settings[0][2]) or 10000;
                    $newbe['background_color'] = json_decode(OptionValue::find(26)->info,true)['background_color'];
                    $newbe['text_color'] = json_decode(OptionValue::find(26)->info,true)['text_color'];
                    array_push($temp3['value'], $newbe);

                    array_push($selectOptions['value'], $temp);
                    array_push($selectOptions['value'], $temp1);
                    array_push($selectOptions['value'], $temp2);
                    array_push($selectOptions['value'], $temp3);
                    $kind = 2;
                    break;

                case '33':
                    //Đang chọn VIP , thành viên ~ id optionvalue = 13,14
                    $selectOptions['id'] = 2; // vip, thanh vien
                    $selectOptions['value'] = array();

                    $temp =array();
                    $temp['id'] = 13;
                    $temp['value'] = array();


                    $vip = array();
                    $vip['point'] = intval($request->settings[0][0]) or 10000;
                    $vip['bouns'] = intval($request->settings[0][1]) or 10000;
                    $vip['unit'] = intval($request->settings[0][2]) or 10000;
                    $vip['background_color'] = json_decode(OptionValue::find(13)->info,true)['background_color'];
                    $vip['text_color'] = json_decode(OptionValue::find(13)->info,true)['text_color'];
                    array_push($temp['value'], $vip);


                    $temp1 =array();
                    $temp1['id'] = 14;
                    $temp1['value'] = array();

                    $member = array();
                    $member['point'] = intval($request->settings[1][0]) or 10000;
                    $member['bouns'] = intval($request->settings[1][1]) or 10000;
                    $member['unit'] = intval($request->settings[1][2]) or 10000;
                    $member['background_color'] = json_decode(OptionValue::find(14)->info,true)['background_color'];
                    $member['text_color'] = json_decode(OptionValue::find(14)->info,true)['text_color'];
                    array_push($temp1['value'], $member);

                    $temp3 =array();
                    $temp3['id'] = 26;
                    $temp3['value'] = array();

                    $newbe = array();
                    $newbe['point'] = 0;
                    $newbe['bouns'] = 0;
                    $newbe['unit'] = intval($request->settings[0][2]) or 10000;
                    $newbe['background_color'] = json_decode(OptionValue::find(26)->info,true)['background_color'];
                    $newbe['text_color'] = json_decode(OptionValue::find(26)->info,true)['text_color'];
                    array_push($temp3['value'], $newbe);

                    array_push($selectOptions['value'], $temp);
                    array_push($selectOptions['value'], $temp1);
                    array_push($selectOptions['value'], $temp3);
                    $kind = 2;
                    break;
                
                default:
                    # code...
                    break;
            }

            $choiced = array();

            switch ( $request->choiced ) {
                case 1:
                    $card_type = 4;
                    break;

                case 0:
                    $card_type = 3;
                    break;
                
                default:
                    $card_type = "";
                    break;
            }


            //Save Type card
            if ( $card_type != "" ) {
                $result = Merchant::where( 'id', Auth::merchant()->get()->id )->update(array(
                    'card_type' => $card_type,
                    'card_info' => json_encode($selectOptions),
                    'kind'      => $kind
                ));

                if ($result != null) {
                    return Response::json([
                            'success'   => true,
                            'priority'  => 'success',
                            'messages'  => 'Thiết lập thông tin thẻ thành công'
                        ]);
                } else {
                    return Response::json([
                            'success'   => false,
                            'priority'  => 'danger',
                            'messages'  => 'Thiết lập thông tin thẻ thất bại'
                        ]);
                }
            } else {
                return Response::json([
                        'success'   => false,
                        'priority'  => 'danger',
                        'messages'  => 'Bạn chưa cấu hình thẻ của hình. Vui lòng quay lại bước 2 tạo thông tin thẻ trước khi hoàn thành'
                    ]);
            }
            
        }

        

    }

    public function postStoreshop(Request $request) {

        $dataRequestShop = $request->all();
        $checkSpace = substr($request->name,0,1);
        if ( $request->name == null ) {
            return Response::json([
                        'success'   => false,
                        'priority'  => 'danger',
                        'messages'  => 'Tên cửa hàng và địa chỉ không được để trống'
                    ]);
        } elseif ( $checkSpace === " " ) {
            return Response::json([
                    'success'   => false,
                    'priority'  => 'danger',
                    'messages'  => 'Không được có ký tự trắng ở đầu tiên'
                ]);
        } else {

            $checkStoreActive = Store::where('merchants_id' , Auth::merchant()->get()->id)->where('active', 1)->count();
            if ( $checkStoreActive < 1 ) {
                $result = Store::create(array(
                        'merchants_id'  => Auth::merchant()->get()->id,
                        'store_name'    => $request->name,
                        'address'       => $request->address,
                        'active'        => 1
                    ));
                if ( $result != null ) {
                    return Response::json([
                        'success'   => true,
                        'priority'  => 'success',
                        'messages'  => "Thêm cửa hàng ". $request->name ." thành công",
                        'allstore'  => $dataRequestShop,
                        'id'        => $result->id,
                    ]);
                } else {
                    return Response::json([
                        'success'   => false,
                        'priority'  => 'danger',
                        'messages'  => 'Thêm cửa hàng thất bại. Vui lòng thử lại'
                    ]);
                }
            } else {
                $getAllStore = Store::where('merchants_id' , Auth::merchant()->get()->id)->get()->count();
                $getAllPackages = OptionValue::where('options_id',2)->get();


                foreach ($getAllPackages as $row) {
                    if( $getAllStore <= json_decode($row->info,true)['stores_max'] ) {
                        $packages = $row->name;
                        $storeMax = json_decode($row->info,true)['stores_max'];
                        $packagesId = $row->id;
                        break;
                    }
                }

                if ( $getAllStore == $storeMax && Auth::merchant()->get()->package == 0 ) {
                    $result = Store::create(array(
                        'merchants_id'  => Auth::merchant()->get()->id,
                        'store_name'    => $request->name,
                        'address'       => $request->address,
                        'active'        => 0
                    ));

                    $nextPackageId = $packagesId + 1;
                    $nextPackages = OptionValue::find($nextPackageId);
                    $nameNextPackage = $nextPackages->name;

                    $nextStore = $getAllStore + 1;
                    $message = "Quý khách đang đăng ký gói dịch vụ <span class='pink'>" . $packages . "</span> và chỉ áp dụng cho <span class='pink'>" . $storeMax . "</span> cửa hàng. Khởi tạo cửa hàng thứ <span class='pink'>" . $nextStore . "</span> yêu cầu nâng cấp lên gói <span class='pink'>". $nameNextPackage ."</span> thì mới có thể kích hoạt cửa hàng. Quý khách có chắc muốn tiếp tục?";

                    if ( $result != null ) {
                        return Response::json([
                            'success'           => 'dialog',
                            'priority'          => 'info',
                            'messages'          => 'Tạo cửa hàng thành công',
                            'messageDialog'     => $message,
                            'allstore'          => $dataRequestShop,
                            'id'                => $result->id,
                        ]);
                        
                    } else {
                        return Response::json([
                            'success'   => false,
                            'priority'  => 'danger',
                            'messages'  => 'Thêm cửa hàng thất bại. Vui lòng thử lại'
                        ]);
                    }
                } else {

                    $result = Store::create(array(
                        'merchants_id'  => Auth::merchant()->get()->id,
                        'store_name'    => $request->name,
                        'address'       => $request->address,
                        'active'        => 0
                    ));

                    if ( $result != null ) {
                        return Response::json([
                            'success'   => true,
                            'priority'  => 'success',
                            'messages'  => "Thêm cửa hàng ". $request->name ." thành công",
                            'allstore'  => $dataRequestShop,
                            'id'        => $result->id,
                        ]);
                    } else {
                        return Response::json([
                            'success'   => false,
                            'priority'  => 'danger',
                            'messages'  => 'Thêm cửa hàng thất bại. Vui lòng thử lại'
                        ]);
                    }
                }



                // // die($storeMax);

                // $nextStore = $getAllStore+1;
                // $maxStore = $storeMax;

                // $nextPackages = OptionValue::find($packagesId);
                // $namePackageNext = $nextPackages->name;

                

                //Check now Store and sendmessage to user

                //Count all store
                

                // $message = "Quý khách đang đăng ký gói dịch vụ ". $packages ." và chỉ áp dụng cho ". $maxStore ." cửa hàng. Khởi tạo cửa hàng thứ " . $nextStore . " yêu cầu nâng cấp lên gói ". $namePackageNext .". Quý khách có chắc muốn tiếp tục?";
                
            }
        }
    }


//Store Shop and check Auth
    public function postInitializeaddshop(Request $request) {
        $dataRequestShop = $request->all();

        $checkSpace = substr($request->name,0,1);
        if ( $request->name == null ) {
            return Response::json([
                        'success'   => false,
                        'priority'  => 'danger',
                        'messages'  => 'Tên cửa hàng và địa chỉ không được để trống'
                    ]);
        } elseif ( $checkSpace === " " ) {
            return Response::json([
                    'success'   => false,
                    'priority'  => 'danger',
                    'messages'  => 'Không được có ký tự trắng ở đầu tiên'
                ]);
        } else {

            $checkMaxShop = OptionValue::where('id', Auth::merchant()->get()->package)->first();
            $data = json_decode($checkMaxShop->info,true);
            $countMaxShop = $data['stores_max'];

            $checkNowShop = Store::where('merchants_id', Auth::merchant()->get()->id)->where('active',1)->count();
            if ($checkNowShop < $countMaxShop) {
                $result = Store::create(array(
                        'merchants_id'  => Auth::merchant()->get()->id,
                        'store_name'    => $request->name,
                        'address'       => $request->address,
                        'active'        => '1'
                    ));
                if ($result != null) {
                    return Response::json([
                        'success'   => true,
                        'priority'  => 'success',
                        'messages'  => "Thêm cửa hàng ". $request->name ." thành công",
                        'allstore'  => $dataRequestShop,
                        'id'        => Auth::merchant()->get()->id,
                    ]);
                } else {
                    return Response::json([
                        'success'   => false,
                        'priority'  => 'danger',
                        'messages'  => 'Thêm cửa hàng thất bại. Vui lòng thử lại'
                    ]);
                }
            } else {
                $result = Store::create(array(
                        'merchants_id'  => Auth::merchant()->get()->id,
                        'store_name'    => $request->name,
                        'address'       => $request->address,
                        'active'        => '0'
                    ));
                if ($result != null) {
                    return Response::json([
                        'success'   => true,
                        'priority'  => 'success',
                        'messages'  => "Thêm cửa hàng ". $request->name ." thành công",
                        'allstore'  => $dataRequestShop,
                        'id'        => Auth::merchant()->get()->id,
                    ]);
                } else {
                    return Response::json([
                        'success'   => false,
                        'priority'  => 'danger',
                        'messages'  => 'Thêm cửa hàng thất bại. Vui lòng thử lại'
                    ]);
                }
            }

        }
    }

    public function getInfoPackages() {

        $getAllStore = Store::where('merchants_id' , Auth::merchant()->get()->id)->get()->count();
        $getAllPackages = OptionValue::where('options_id',2)->get();

        $data = Merchant::select('information')->where('id',Auth::merchant()->get()->id)->first();

        $getInfoMerchant = json_decode($data->information,true);


        foreach ($getAllPackages as $row) {
            if( $getAllStore <= json_decode($row->info,true)['stores_max'] ) {
                $packages = $row->name;
                $price = json_decode($row->info,true)['budget'];
                break;
            }
        }
        return Response::json([
            'success'   => true,
            'priority'  => 'success',
            'messages'  => "Success",
            'count'     => $getAllStore,
            'info'      => $getInfoMerchant,
            'packages'  => "[ " . $packages . " - " . $price . "k/tháng ]" 
        ]);
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
    public function destroy(Request $request)
    {
        $id = $request->id;
        $checkMerchantId = Store::where('id',$id)->first();
        if ($checkMerchantId->merchants_id == Auth::merchant()->get()->id ) {
            Store::find($id)->delete();
            return Response::json([
                        'success'   => true,
                        'priority'  => 'success',
                        'messages'  => 'Xóa thành công'
                    ]);
        } else {
            return Response::json([
                        'success'   => false,
                        'priority'  => 'danger',
                        'messages'  => 'Bạn không đủ quyền để xóa shop này'
                    ]);
        }
        
    }



    //Destroy store check merchant ID
    public function postUpdateUserStore(Request $request) {

        $validator = Validator::make(
                [
                    'user_name'         => $request->username,
                    'password'          => $request->password,
                ],
                [
                    'user_name'         => 'required|min:6',
                    'password'          => 'required|min:6',
                ],
                [
                    'user_name.required'     => "Tên đăng nhập thu ngân không được để trống",
                    'user_name.min'          => "Tên đăng nhập phải lớn hơn 6 ký tự",
                    // 'user_name.unique'       => "Tên tài khoản đã được sử dụng cho cửa hàng khác",

                    'password.required'     => "Mật khẩu đăng nhập thu ngân không được để trống",
                    'password.min'           => "Mật khẩu đăng nhập phải lớn hơn 6 ký tự",
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

            $id = $request->id;
            $checkMerchantId = Store::where('id',$id)->first();

            if ( $checkMerchantId == NULL ) {
                return Response::json([
                        'success'   => false,
                        'title'     => 'Có lỗi. Vui lòng thử lại',
                        'priority'  => 'danger',
                        'messages'  => 'Có lỗi xảy ra. Vui lòng thử lại sau'
                    ]);
            } else if ($checkMerchantId->merchants_id == Auth::merchant()->get()->id ) {
                $checkExistStore = Store::where('user_name' , $request->username)->whereNotIn('id',[$id])->count();
                if ( $checkExistStore === 0 ) {
                    $data = Store::where('id' , $id)->update([
                        'user_name'     => $request->username,
                        'password'      => Hash::make($request->password),
                    ]);
                
                    if ( $data != null ) {
                        return Response::json([
                                'success'   => true,
                                'priority'  => 'success',
                                'title'     => 'Cập nhật thành công',
                                'messages'  => '<center>Cập nhật tài khoản thu ngân thành công</center>'
                            ]);
                    } else {
                        return Response::json([
                                'success'   => false,
                                'priority'  => 'danger',
                                'title'     => 'Thất bại',
                                'messages'  => 'Cập nhật tài khoản thu ngân thất bại. Vui lòng thử lại'
                            ]);
                    }
                } else {
                    return Response::json([
                        'success'   => false,
                        'priority'  => 'danger',
                        'title'     => 'Tên cửa hàng đã được sử dụng',
                        'messages'  => 'Tên tài khoản đăng nhập đã được sử dụng hoặc đã được sử dụng cho cửa hàng khác. Vui lòng thử lại'
                    ]);
                }
                
            } else {
                return Response::json([
                        'success'   => false,
                        'title'     => 'Access Denied',
                        'priority'  => 'danger',
                        'messages'  => 'Bạn không đủ quyền để thay đổi cửa hàng này'
                    ]);
            }
        }
    }

    //ham lay quan huyen tu tinh thanh pho
    public function getDistrict(Request $request)
    {
        $provinces_id = $request->provinces_id;
        $district=District::select('districts_id','name')->where('provinces_id',$provinces_id)->get();
        return Response::json($district);
    }

    //Register store
    public function registerMerchants(Request $request) {

        // $getMerchantId = Merchant::where('users_id',Auth::merchant()->get()->id)->first();
        $getAllStore = Store::where('merchants_id' , Auth::merchant()->get()->id)->count();
        $getPackages = OptionValue::select('id','info')->where('options_id' , 2)->get();


        foreach ($getPackages as $r) {
            $valuePackages = json_decode($r->info,true);
            if ($getAllStore <= $valuePackages['stores_max']) {
                $idPackagesUpgrade = $r->id;
                break;
            }
        }

        foreach ($getPackages as $r) {
            $valuePackages = json_decode($r->info,true);
            if ( $valuePackages['stores_max'] == 1) {
                $idPackagesFree = $r->id;
                break;
            }
        }

        if ($idPackagesUpgrade == $idPackagesFree) {
            Merchant::where('id', Auth::merchant()->get()->id )->update(array(
                    'package'           => $idPackagesFree,
                    'active'            => 2,
                ));

                $data = array();

                $data['name']   = json_decode(Auth::merchant()->get()->information,true)['fullname'];
                $data['email']  = Auth::merchant()->get()->email;

                $html = 'emails.3';
                $sendMail = sendEmailFromMandrill('Hoàn thành khởi tạo chương trình', $request->email, $request->name, $html, ['data'=>$data]);


            $getInfoCurrentPackages = OptionValue::select('id','name')->where('id' , $idPackagesFree)->first();
            return Response::json([
                        'success'   => true,
                        'priority'  => 'success',
                        'messages'  => "Đăng ký thành công gói " . $getInfoCurrentPackages->name,
                    ]);

        } else {
            $result = Merchant::where('id', Auth::merchant()->get()->id )->update(array(
                    'package'           => $idPackagesFree,
                    'package_status'    => $idPackagesUpgrade,
                    'active'            => 2,
                ));

            if ($result != null) {

                $upgrade = RegisterPackage::create([
                    'merchants_id' =>   Auth::merchant()->get()->id,
                    'package'   =>      $idPackagesUpgrade,
                    'name'      =>      $request->fullname,
                    'mobile'    =>      $request->phone,
                    'email'     =>      $request->email,
                    'content'   =>      $request->content,
                ]);


            }
            $getInfoCurrentPackages = OptionValue::select('id','name')->where('id' , $idPackagesUpgrade)->first();
            return Response::json([
                        'success'   => true,
                        'priority'  => 'success',
                        'messages'  => "Đăng ký thành công gói " . $getInfoCurrentPackages->name . " Hiện tại bạn vẫn đang sử dụng gói Free. Vui lòng đợi Admin duyệt thì mới có hiệu lực",
                    ]);
        }
        // var_dump($idPackagesStatus . "-" . $idPackagesFree);die();



        // if ($getAllStore <= 1) {
        //     //
        //     Merchant::where('users_id' , Auth::merchant()->get()->id)->update(array(
        //             'packages_id' => '1',
        //         ));
        //     return Response::json([
        //                 'success'   => true,
        //                 'priority'  => 'success',
        //                 'messages'  => 'Đăng ký thành công gói Free'
        //             ]);

        // } elseif ($getAllStore <= 3) {
        //     Merchant::where('users_id' , Auth::merchant()->get()->id)->update(array(
        //             'packages_id' => '2',
        //         ));
        //     return Response::json([
        //                 'success'   => true,
        //                 'priority'  => 'success',
        //                 'messages'  => 'Đăng ký thành công gói PREMIUM 1. Vui lòng đợi Admin xác nhận'
        //             ]);
        // } elseif ($getAllStore <= 5) {
        //     Merchant::where('users_id' , Auth::merchant()->get()->id)->update(array(
        //             'packages_id' => '3',
        //         ));
        //     return Response::json([
        //                 'success'   => true,
        //                 'priority'  => 'success',
        //                 'messages'  => 'Đăng ký thành công gói PREMIUM 2. Vui lòng đợi Admin xác nhận'
        //             ]);
        // } else {
        //     Merchant::where('users_id' , Auth::merchant()->get()->id)->update(array(
        //             'packages_id' => '4',
        //         ));
        //     return Response::json([
        //                 'success'   => true,
        //                 'priority'  => 'success',
        //                 'messages'  => 'Đăng ký thành công gói PREMIUM 2. Vui lòng đợi Admin xác nhận'
        //             ]);
        // }
    }

    //Get All Store Check Active

    //Store new account
    public function postStorenewaccount(Request $request) {
        $getInfoStoreById = Store::find($request->id);
        if ($getInfoStoreById->merchants_id == Auth::merchant()->get()->id) {
            $result = Store::where('id' , $request->id)->update(array(
                    'user_name'     => $request->username,
                    'password'         => hash::make($request->password),
                ));
            if ($result != null) {
                return Response::json([
                        'success'   => true,
                        'priority'  => 'success',
                        'messages'  => 'Lưu tài khoản đăng nhập thành công'
                    ]);
            } else {
                return Response::json([
                        'success'   => false,
                        'priority'  => 'danger',
                        'messages'  => 'Lưu thông tin tài khoản thất bại'
                    ]);
            }

            echo "Thêm thành công";
        } else {
            echo "404";
        }
    }

    //Store data chop
    public function postStoreChopsChops(Request $request) {
        var_dump($request->all());
    }

    public function postStoreChopsChopsGift(Request $request) {
        var_dump($request->all());
    }

    public function postStoreLevel2(Request $request) {
        var_dump($request->all());
    }

    public function postStoreLevel3(Request $request) {
        $value = array();

        $value1 = array();
        $value1['point'] = $request->point1;
        $value1['bonus'] = $request->bonus1;
        $value1['unit'] = $request->value;
        array_push($value, $value1);

        $value2 = array();
        $value2['point'] = $request->point2;
        $value2['bonus'] = $request->bonus2;
        $value2['unit'] = $request->value;
        array_push($value, $value2);

        $value3 = array();
        $value3['point'] = $request->point3;
        $value3['bonus'] = $request->bonus3;
        $value3['unit'] = $request->value;
        array_push($value, $value3);

        var_dump($value);


    }

    /**
     * Active store
     */

    public function configActiveStore(Request $request) {
        $check = Self::checkStoreMerchant($request->id);
        
        if ( $check ) {

            if ( Auth::merchant()->get()->active == 1 ) {

                $maxStore = Self::maxStore();
                $countAllStore = Store::where( 'merchants_id' , Auth::merchant()->get()->id )->where('active' , 1)->count();
                if ( $countAllStore < $maxStore ) {
                    $result = Store::where( 'id' , $request->id )->update([
                            'active' => 1
                        ]);

                    if ( $result != null ) {
                        return Response::json([
                                'type'          => 'dialog',
                                'success'       => true,
                                'title'         => 'Kích hoạt thành công',
                                'messages'      => 'Kích hoạt cửa hàng thành công',
                            ]);
                    } else {
                        return Response::json([
                                'type'          => 'dialog',
                                'success'       => false,
                                'title'         => 'Kích hoạt thất bại',
                                'messages'      => 'Kích hoạt cửa hàng thất bại. Vui lòng thử lại',
                            ]);
                    }
                } else {
                    return Response::json([
                            'type'          => 'upgrade',
                            'success'       => false,
                            'title'         => 'Yêu cầu nâng cấp dịch vụ',
                            'messages'      => 'Bạn đã sử dụng tối đa số lượng cửa hàng. Vui lòng nâng cấp dịch vụ sau đó kích hoạt lại cửa hàng',
                        ]);
                }

            } else {
                return Response::json([
                    'type'          => 'dialog',
                    'success'       => false,
                    'title'         => 'Kích hoạt thất bại',
                    'messages'      => 'Vui lòng hoàn thành khởi tạo thẻ và đợi Admin xét duyệt thương hiệu của bạn thì bạn mới có thể sử dụng chức năng kích hoạt cửa hàng',
                ]);
            }
        } else {
            return Response::json([
                'type'          => 'dialog',
                'title'         => 'Access Denied',
                'success'       => false,
                'messages'      => 'Bạn không đủ quyền hạn để kích hoạt cửa hàng này',
            ]);
        }
    }

    /**
     * Inactive Store
     */

    public function configInactiveStore(Request $request) {
        $check = Self::checkStoreMerchant($request->id);
        
        if ( $check ) {

            if ( Auth::merchant()->get()->active == 1 ) {

                $result = Store::where( 'id' , $request->id )->update([
                        'active' => 0
                    ]);

                if ( $result != null ) {
                    return Response::json([
                            'type'          => 'dialog',
                            'success'       => true,
                            'title'         => 'Thành công',
                            'messages'      => 'Tạm ngưng cửa hàng thành công',
                        ]);
                } else {
                    return Response::json([
                            'type'          => 'dialog',
                            'success'       => false,
                            'title'         => 'Tạm ngưng cửa hàng thất bại',
                            'messages'      => 'Tạm ngưng cửa hàng cửa hàng thất bại. Vui lòng thử lại',
                        ]);
                }
            } else {
                return Response::json([
                    'type'          => 'dialog',
                    'success'       => false,
                    'title'         => 'Tạm ngưng cửa hàng thất bại',
                    'messages'      => 'Vui lòng hoàn thành khởi tạo thẻ và đợi Admin xét duyệt thương hiệu của bạn thì bạn mới có thể sử dụng chức năng kích hoạt cửa hàng',
                ]);
            }
        } else {
            return Response::json([
                'type'          => 'dialog',
                'title'         => 'Access Denied',
                'success'       => false,
                'messages'      => 'Bạn không đủ quyền hạn để tạm ngưng cửa hàng này',
            ]);
        }
    }
    /**
     *  Check StoreMerchant
     */

    public function checkStoreMerchant($storeID) {
        $getMerchantID = Store::find($storeID);

        if ($getMerchantID != null) {
            if ($getMerchantID->merchants_id == Auth::merchant()->get()->id) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    /**
     * Get MaxStore
     * @return init
     */

    public function maxStore() {
        $data = OptionValue::where('id', Auth::merchant()->get()->package)->first();
        if ($data != null) {
            $maxStore = json_decode($data->info)->stores_max;
        } else {
            $maxStore = 0;
        }
        return $maxStore;
    }

    /**
     * Save Update Name & Address Store
     */

    public function postUpdateNameStore(Request $request) {

        if ($request->storename == "" or $request->storeaddress == "") {
            return Response::json([
                'type'          => 'dialog',
                'success'       => false,
                'title'         => 'Cập nhật thất bại',
                'messages'      => 'Tên và địa chỉ cửa hàng không được để trống. Vui lòng thử lại',
            ]);
        } else {
            $check = Self::checkStoreMerchant($request->id);
            
            if ( $check ) {
                    $result = Store::where( 'id' , $request->id )->update([
                            'store_name' => $request->storename,
                            'address'    => $request->storeaddress,
                        ]);

                    if ( $result != null ) {
                        return Response::json([
                                'type'          => 'dialog',
                                'success'       => true,
                                'title'         => 'Cập nhật thành công',
                                'messages'      => 'Cập nhật tên và địa chỉ cửa hàng thành công',
                            ]);
                    } else {
                        return Response::json([
                                'type'          => 'dialog',
                                'success'       => false,
                                'title'         => 'Cập nhật thất bại',
                                'messages'      => 'Cập nhật tên và địa chỉ cửa hàng thất bại. Vui lòng thử lại',
                            ]);
                    }
            } else {
                return Response::json([
                    'type'          => 'dialog',
                    'title'         => 'Access Denied',
                    'success'       => false,
                    'messages'      => 'Bạn không đủ quyền hạn để chỉnh sửa cửa hàng này',
                ]);
            }
        }
    }

}
