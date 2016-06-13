<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Customer;
use App\HistoryAchievement;
use App\OptionValue;
use Auth;
use App\Store;
use Route;
use View;
use App\Merchant;
use Response;
use Redirect;

class AccountManageController extends Controller
{
    public function __construct(){

        $this->middleware('merchant');
        
        $titlePage = 'Quản trị member - AbbyCard';
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
            $result = HistoryAchievement::getAllstoryOfMerchant();
        
            if(Auth::merchant()->get()) {
                $merchant_id = Auth::merchant()->get()->id;

                $merchant_info = Merchant::where("id",$merchant_id)->get();
                // dd($merchant_info);
                $type_merchant = $merchant_info[0]['card_type'];
                // dd($type_merchant);

                $info = Customer::getAcountInfo($merchant_id);
                foreach ($info as $key => $value) {
                    # code...
                    // dd($value['level']);
                    $value['level'] = $this->getLevelOfCustomer($value['level']);
                }
                // dd($info[0]['level']);
                if($type_merchant == 3) {
                    
                    
                    $type_card_list = $this->getTypeCardList($merchant_id);
                } else if ($type_merchant == 4) {
                    $type_card_list = [];
                }
                // dd($type_card_list);

                return view('merchant/account-manage', array(
                    "infos" => $info,
                    "type_merchant" => $type_merchant,
                    "type_card_list" => $type_card_list
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
    public function store(Request $request)
    {
        //
    }

    public function getAccountByFilter($card_id){
        dd($card_id);
    }


    public function filterAccount(Request $request) {
        $data = $request->all();
        // dd($data);
        // dd($data['status']);
        $merchant_id = Auth::merchant()->get()->id;
        // dd($merchant_id);
        $status= intval($data['status']);
        // dd($status);
        $card_id = intval($data['id']);
        // dd($card_id);
        $page = intval($data['page']);

        // dd($page);
        // dd($card_id);
        $search_box = $data['search_box'];
        // dd($search_box);
        $count = Customer::countAcountByFilter($merchant_id, $status, $card_id, $search_box);
        // dd($count);
        if ($count % env('SEARCH_PAGE') == 0) {
            $count_page = intval($count / env('SEARCH_PAGE'));
        } else {
            $count_page = intval($count / env('SEARCH_PAGE')) + 1;
        }
        // dd($count." va ".$count_page);
        $result = Customer::getAcountByFilter($merchant_id, $status, $card_id, $search_box, $page);
        // dd($result);
        foreach ($result as $key => $value) {
            # code...
            $value['level'] = $this->getLevelOfCustomer($value['level']);
        }
        
        // var_dump($result);
        $htmlBoxInfo = View::make('html.list-member-detail' , array(
            'infos'      => $result,
            "count_page" => $count_page,
            "current_page" => $page,
            

        ))->render();

        return Response::json([
            'success'   => true,
            'result' => $htmlBoxInfo,
            'count' => $count
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
    public function destroy($id)
    {
        //
    }

    public function memberDetail($id) {
        
        if($id == null) {
                dd("404");
            }
        // dd($id);
        if(Auth::merchant()->get()) {
            $merchant_id = Auth::merchant()->get()->id;
            // dd($merchant_id);
            $customer_info = Customer::getAcountInfoByID($merchant_id, $id);
            
            $getCustomerId = Customer::select('customers_code','id')->where('customers_code' , $id)->first()->id;
            
            $getArrStoreIdByMerchant = Store::where('merchants_id', Auth::merchant()->get()->id)->get();
            $arrayStore = [];
            if ($getArrStoreIdByMerchant != null) {
                foreach ($getArrStoreIdByMerchant as $row) {
                    array_push($arrayStore, $row->id);
                }
            }

            $history_info = HistoryAchievement::getHistoryOfMerchant($getCustomerId, $arrayStore);
                // dd($history_info);
            // dd($customer_info);
            if ($customer_info == null) {
                die("404");
            }
            // dd($customer_info->level);

            $level = $this->getLevelOfCustomer($customer_info->level);
            $customer_info->level = $level;
            
            return view('merchant.member-detail', array(
                "infos" => $customer_info,
                "history_infos" => $history_info
            ));
        } else {
            return redirect('/login');
        }
    }



    public function getLevelOfCustomer($level) {
        
        // dd($level);
        $option = OptionValue::find($level);
        if ( $option == null ) {
            $levelCard = 'Chops';
        } else {
            $levelCard = $option->name;
        }
        return $levelCard;
    }

    public function getTypeCardList($merchant_id) {
        $infoCardMember = Merchant::getMerchantById($merchant_id);       
        $temp = json_decode($infoCardMember->card_info);
        $temp = json_decode($infoCardMember->card_info);
        $temp_id = $temp->id;
        // dd($level_id);
        $temp_value = $temp->value;
        $cardLevel = OptionValue::getOptionValue($infoCardMember->card_type);
        // dd($cardLevel);
        // dd($cardLevel);
        $data = [];
        $search = array_pluck($cardLevel, 'name' , 'id');
        // dd($search);
        // dd($temp_value);
        foreach ($temp_value as $value) {
            array_push($data, array(
                "id" => $value->id,
                "name" => $search[$value->id]
            ));
        }
        // dd($data);
        return $data;
    }

}
