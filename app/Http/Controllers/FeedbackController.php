<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Notification;
use App\Feedback;
use Route;
use Auth;
use DB;
use View;
use Redirect;

class FeedbackController extends Controller
{
    public function __construct(){

        $this->middleware('merchant');
        
        $titlePage = 'Quản trị phản hồi - AbbyCard';
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
        $package_merchant = checkNowPackage(isset(Auth::merchant()->get()->id) ? Auth::merchant()->get()->package : 0);
        // dd($package_merchant);
        // dd($this->getWeekend(0,2));
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
                $feeds = Feedback::getFeedBackInfo($merchant_id);
                
                // dd($feeds);
                // $count = Feedback::getAverageRateOfMount(1,$merchant_id);
                $average = array();
                $weekend = array();
                $number_day = $this->getNumberToMonday();
                // dd($count);
                // dd($feed);
                for($i = 0; $i < 23; $i++) {
                    if($i % 7 == 0) {
                        array_push($average, Feedback::getAverageRateOfWeek($i, $number_day, $merchant_id));
                    }
                }
                // $test = Feedback::getAverageRateOfWeek(0, $number_day, $merchant_id);
                // dd($average);
                for($i = 0; $i < 4; $i++) {
                    array_push($weekend, $this->getWeekend($i,$number_day));
                }
                // dd($weekend);

                return view('merchant.feedback' , array(
                    "feeds" => $feeds,
                    "averages" => $average,
                    "weekend" => $weekend,
                    "package_merchant" => $package_merchant
                ));
            } else {
                return redirect('/login');
            }
        }
        

        
    }
    public function getWeekend($index, $number_of_day) {
        $start = time() - $index*(7 * 24 * 60 * 60) - $number_of_day*( 24 * 60 * 60);
        if($index == 0) {
            $end = time();
        } else {
            $end = time() - ($index - 1)*(7 * 24 * 60 * 60) - $number_of_day*( 24 * 60 * 60);
        }
        return date('d.m.Y', $start) ." - ".date('d.m.Y', $end);
    }
    public function getNumberToMonday() {
        switch (getdate()["weekday"]) {
            case 'Monday':
                # code...
                return 0;
                break;
            case 'Tuesday':
                # code...
                return 1;
                break;
            case 'Wednesday':
                # code...
                return 2;
                break;
            case 'Thursday':
                # code...
                return 3;
                break;
            case 'Friday':
                # code...
                return 4;
                break;
            case 'Saturday':
                # code...
                return 5;
                break;
            case 'Sunday':
                # code...
                return 6;
                break;
            default: return 0;
                # code...
                break;
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
