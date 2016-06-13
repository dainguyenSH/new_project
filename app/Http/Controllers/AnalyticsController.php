<?php

namespace App\Http\Controllers;
use App\Customer;
use Illuminate\Http\Request;
use App\OptionValue;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Route;
use App\Merchant;
use App\Statistical;
use App\Feedback;
use App\CustomerMerchant;
use App\Notification;
use Redirect;
use View;
use Auth;

class AnalyticsController extends Controller
{
    public function __construct(){
        
        $this->middleware('merchant');

        $titlePage = 'Thống kê - AbbyCard';
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
        // updateStatistical();


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
        } else  {
            if(Auth::merchant()->get()) {
                $merchant_id = Auth::merchant()->get()->id;
                // dd(getCountFeedbackInMonthOfMerchant($merchant_id, 1));
                $infoCardMember = Merchant::getMerchantById($merchant_id);
                $merchant_type = $infoCardMember['card_type'];

                $analystic_info = Statistical::where('merchants_id',$merchant_id)->where('status',1)->where('year',2016)->first();
                
                // dd($merchant_type);
                $temp = json_decode($infoCardMember->card_info);
                $temp_id = $temp->id;
                // dd($level_id);
                $temp_value = $temp->value;
                $cardLevel = OptionValue::getOptionValue($infoCardMember->card_type);
                // dd($cardLevel);
                $search = array_pluck($cardLevel, 'name' , 'id');
                $count_customer = Customer::countCustomerActive($merchant_id);
                // dd($search);    
                $data = [];
                foreach ($temp_value as $value) {
                    $data[] = [

                        'card_type_id' => $value->id, 
                        'card_type_name' => $search[$value->id],

                        'count_customer' => $count_customer,
                        'count' => CustomerMerchant::countCustomerByLevel($merchant_id, $value->id)

                    ];
                }
                // dd($data);
                return view('merchant.analytics', array(
                    'datas' => $data,
                    'analystic_info' => $analystic_info,
                    'type_merchant' => $merchant_type
                ));
            } else {
                return redirect('/login');
            }
        }
    }

    

    public function getAnalystic(Request $request) {
        $year = $request->year;
        dd($year);
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
}
