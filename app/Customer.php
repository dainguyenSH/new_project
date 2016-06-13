<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{ 
    protected $table = 'customers';

    protected $fillable = array('email','token', 'active', 'facebook_id', 'device_token', 'device_type', 'name','gender', 'work', 'avatar', 'birthday', 'location', 'province', 'district', 'mobile');
    /**
     * Kiểm tra xem tài khoản đã tồn tại chưa
     */
    static function checkExist($facebookID)
    {
    	return self::where('facebook_id', $facebookID)->count();
    }
    public static function getAcountInfo($merchants_id) {
    	$info =  Customer::select("customers.id","customers.customers_code","customers.created_at","customers.name","customers.active","customers.email","customer_merchants.point","customer_merchants.level","customer_merchants.status","customers.avatar")
        ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
            ->where("customer_merchants.merchants_id","=",$merchants_id)
            // ->where("customers.active","=",1)
            ->orderby('created_at','desc')
            ->paginate(env('PAGE'));
         return $info;
    }

    public static function countCustomerActive($merchants_id) {
        $count =  Customer::select("customers.id","customers.created_at","customers.name","customers.active","customers.email","customer_merchants.point","customer_merchants.level","customers.avatar")
        ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
            ->where("customer_merchants.merchants_id","=",$merchants_id)
            ->where("customers.active","=",1)
            ->where("customer_merchants.status","=",1)
            ->count();
         return $count;
    }

    public static function countAllCustomer($merchants_id) {
        $count =  Customer::select("customers.id","customers.created_at","customers.name","customers.active","customers.email","customer_merchants.point","customer_merchants.level","customers.avatar")
        ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
            ->where("customer_merchants.merchants_id","=",$merchants_id)
            ->where("customers.active","=",1)
            
            ->count();
         return $count;
    }

    public static function getActiveAcountInfo($merchants_id) {
        $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.active","customers.email","customer_merchants.point","customer_merchants.level","customers.avatar","customers.device_token")
        ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
            ->where("customer_merchants.merchants_id","=",$merchants_id)
            ->where("customers.active","=",1)
            ->where("customer_merchants.status","=",1)
            ->get();
         return $info;
    }
    public static function getInfoSearch($merchants_id) {
        $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.active","customer_merchants.point","customer_merchants.level","customers.avatar")
        ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
            ->where("customer_merchants.merchants_id","=",$merchants_id);
    }

    public static function getAcountInfoByID($merchants_id, $id) {
        $info =  Customer::select("customers.id","customers.customers_code","customers.birthday","customers.created_at","customers.name","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.point","customer_merchants.status","customer_merchants.current_point","customer_merchants.level","customers.avatar")
        ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
            ->where("customer_merchants.merchants_id","=",$merchants_id)
            ->where("customers.customers_code","=",$id)
            // ->where('customer_merchants.status',1)
            ->where("customers.active","=",1)
            ->first();
         return $info;
    }



    public static function getAcountByFilter($merchants_id, $status, $card_id,$search_box = null, $page = 0) {
        // dd($status);
        if($search_box != "") {
            // dd("da vao");
            // dd($search_box);
            if (($card_id != 0) && ($status != 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    ->where("customer_merchants.level", $card_id)
                    ->where("customer_merchants.status", $status)
                    ->where("customers.name","like", "%".$search_box."%")
// 
                    // ->paginate(2);
                    ->skip($page * env('SEARCH_PAGE'))->take(env('SEARCH_PAGE'))->get();
            } 
            if (($card_id == 0) && ($status != 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    // ->where("customers.level", $card_id)
                    ->where("customers.name","like", "%".$search_box."%")
                    ->where("customer_merchants.status", $status)
                    // ->paginate(2);
                    ->skip($page * env('SEARCH_PAGE'))->take(env('SEARCH_PAGE'))->get();
            }  
            if (($card_id != 0) && ($status == 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    ->where("customer_merchants.level", $card_id)
                    ->where("customers.name","like", "%".$search_box."%")
                    // ->where("customer_merchants.status", $status)
                    // ->paginate(2);
                    ->skip($page * env('SEARCH_PAGE'))->take(env('SEARCH_PAGE'))->get();
            }  
            if (($card_id == 0) && ($status == 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    // ->where("customers.level", $card_id)
                    ->where("customers.name","like", "%".$search_box."%")
                    // ->where("customer_merchants.status", $status)
                    // ->paginate(2);
                    ->skip($page * env('SEARCH_PAGE'))->take(env('SEARCH_PAGE'))->get();
            }  
        } else {

            if (($card_id != 0) && ($status != 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    ->where("customer_merchants.level", $card_id)
                    ->where("customer_merchants.status", $status)
                    ->where("customers.name","like", "%".$search_box."%")

                    // ->paginate(2);
                    ->skip($page * env('SEARCH_PAGE'))->take(env('SEARCH_PAGE'))->get();
            } 
            if (($card_id == 0) && ($status != 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    // ->where("customers.level", $card_id)
                    ->where("customers.name","like", "%".$search_box."%")
                    ->where("customer_merchants.status", $status)
                    // ->paginate(2);
                    ->skip($page * env('SEARCH_PAGE'))->take(env('SEARCH_PAGE'))->get();
            }  
            if (($card_id != 0) && ($status == 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    ->where("customer_merchants.level", $card_id)
                    ->where("customers.name","like", "%".$search_box."%")
                    // ->where("customer_merchants.status", $status)
                    // ->paginate(2);
                    ->skip($page * env('SEARCH_PAGE'))->take(env('SEARCH_PAGE'))->get();
            }  
            if (($card_id == 0) && ($status == 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    // ->where("customers.level", $card_id)
                    ->where("customers.name","like", "%".$search_box."%")
                    // ->where("customer_merchants.status", $status)
                    // ->paginate(2);
                    ->skip($page * env('SEARCH_PAGE'))->take(env('SEARCH_PAGE'))->get();
            }  
        }
        
        
            // dd($info);
         return $info;
    }

    public static function countAcountByFilter($merchants_id, $status, $card_id, $search_box) {
        if($search_box != "") {
            // dd("da vao");
            // dd($search_box);
            if (($card_id != 0) && ($status != 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    ->where("customer_merchants.level", $card_id)
                    ->where("customer_merchants.status", $status)
                    ->where("customers.name","like", "%".$search_box."%")
                    ->count();
            } 
            if (($card_id == 0) && ($status != 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    // ->where("customers.level", $card_id)
                    ->where("customers.name","like", "%".$search_box."%")
                    ->where("customer_merchants.status", $status)
                    ->count();
            }  
            if (($card_id != 0) && ($status == 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    ->where("customer_merchants.level", $card_id)
                    ->where("customers.name","like", "%".$search_box."%")
                    ->count();
            }  
            if (($card_id == 0) && ($status == 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    // ->where("customers.level", $card_id)
                    ->where("customers.name","like", "%".$search_box."%")
                    ->count();
            }  
        } else {

            if (($card_id != 0) && ($status != 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    ->where("customer_merchants.level", $card_id)
                    ->where("customer_merchants.status", $status)
                    ->where("customers.name","like", "%".$search_box."%")
                    ->count();
            } 
            if (($card_id == 0) && ($status != 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    ->where("customers.name","like", "%".$search_box."%")
                    ->where("customer_merchants.status", $status)
                    ->count();
            }  
            if (($card_id != 0) && ($status == 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    ->where("customer_merchants.level", $card_id)
                    ->where("customers.name","like", "%".$search_box."%")
                    ->count();
            }  
            if (($card_id == 0) && ($status == 2)) {
                $info =  Customer::select("customers.id","customers.created_at","customers.name","customers.customers_code","customers.location","customers.gender","customers.mobile","customers.email","customers.active","customer_merchants.status","customer_merchants.point","customer_merchants.level","customers.avatar")
                ->join("customer_merchants","customers.id","=","customer_merchants.customers_id")
                    ->where("customer_merchants.merchants_id","=",$merchants_id)
                    ->where("customers.name","like", "%".$search_box."%")
                    ->count();
            }  
        }
        
            // dd($info);
         return $info;
    }

    static function getCustomerByToken($accessToken){
        return self::where('token', $accessToken)->where('active', 1)->first();
    }

    /**
     * Trả về customer dựa vào code
     */
    static function getCustomerByCode($code)
    {
        return self::where('customers_code', $code)->first();
    }
    /**
     * Trả về loai thiet bi customer dung
     */
    
    static function getDevice($device_token){
        return self::where('device_token', $device_token)->where('active', 1)->first()->device_type;
    }
}

