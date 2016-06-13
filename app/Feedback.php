<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Customer;
use App\Store;
use App\Notification;
class Feedback extends Model 
{

	protected $table = 'feedbacks';

    protected $fillable = array('customers_id', 'notifications_id', 'customers_name','stores_name','rate', 'content', 'status','created_at');

    public function scopeGetNameCustomer()
    {
    	return Customer::find($this->customers_id)->name;
    }
    public function scopeGetNameStore()
    {
    	Notification::find($this->notifications_id)->stores_id;
    	return Store::find($this->customers_id)->name;
    }
    public static function getFeedBackInfo($merchants_id) {
        $feed = Feedback::select("feedbacks.id","feedbacks.created_at","feedbacks.customers_name","feedbacks.customers_id","feedbacks.stores_name","feedbacks.rate","feedbacks.content","customers.customers_code")
        ->join("notifications","notifications_id","=","notifications.id")
        ->join("customers","customers_id","=","customers.id")
            ->where("notifications.merchants_id","=",$merchants_id)
            ->where("feedbacks.status","=",1)
            ->orderby("feedbacks.created_at","desc")
            ->paginate(env("PAGE"));
        return $feed;
    }

    public static function getAverageRateOfMount($number_of_day, $merchants_id) {
        $count =  Feedback::select("feedbacks.id","feedbacks.created_at","feedbacks.customers_name","feedbacks.stores_name","feedbacks.rate","feedbacks.content")
        ->join("notifications","feedbacks.notifications_id","=","notifications.id")
            ->where("notifications.merchants_id","=",$merchants_id)
            ->where("feedbacks.status","=",1)
            ->whereRaw("feedbacks.created_at >= (LAST_DAY(NOW()) - INTERVAL 2 MONTH + INTERVAL ".$number_of_day." DAY) AND feedbacks.created_at <= (LAST_DAY(NOW()) - INTERVAL 2 MONTH + INTERVAL ".$number_of_day." + 7 DAY)")
            ->count();

        $sum =  Feedback::select("feedbacks.id","feedbacks.created_at","feedbacks.customers_name","feedbacks.stores_name","feedbacks.rate","feedbacks.content")
        ->join("notifications","feedbacks.notifications_id","=","notifications.id")
            ->where("notifications.merchants_id","=",$merchants_id)
            ->where("feedbacks.status","=",1)
            ->whereRaw("feedbacks.created_at >= (LAST_DAY(NOW()) - INTERVAL 2 MONTH + INTERVAL ".$number_of_day." DAY) AND feedbacks.created_at <= (LAST_DAY(NOW()) - INTERVAL 2 MONTH + INTERVAL ".$number_of_day." + 7 DAY)")
            ->sum("feedbacks.rate");
        if ($count != 0) {
            return round($sum/$count,1);
        } else {
            return $count;
        }
    }
    public static function getAverageRateOfWeek($index, $number_of_day, $merchants_id) {
        $count =  Feedback::select("feedbacks.id","feedbacks.created_at","feedbacks.customers_name","feedbacks.stores_name","feedbacks.rate","feedbacks.content")
        ->join("notifications","feedbacks.notifications_id","=","notifications.id")
            ->where("notifications.merchants_id","=",$merchants_id)
            ->where("feedbacks.status","=",1)
            ->whereRaw("(feedbacks.created_at >=  NOW() - INTERVAL ".$index." DAY - INTERVAL ".$number_of_day." DAY) AND (feedbacks.created_at <=  NOW() - INTERVAL ".$index." DAY - INTERVAL ".$number_of_day." DAY + INTERVAL 7 DAY)")
            ->count();

        $sum =  Feedback::select("feedbacks.id","feedbacks.created_at","feedbacks.customers_name","feedbacks.stores_name","feedbacks.rate","feedbacks.content")
        ->join("notifications","feedbacks.notifications_id","=","notifications.id")
            ->where("notifications.merchants_id","=",$merchants_id)
            ->where("feedbacks.status","=",1)
            ->whereRaw("(feedbacks.created_at >=  NOW() - INTERVAL ".$index." DAY - INTERVAL ".$number_of_day." DAY) AND (feedbacks.created_at <=  NOW() - INTERVAL ".$index." DAY - INTERVAL ".$number_of_day." DAY + INTERVAL 7 DAY)")
            ->sum("feedbacks.rate");
        $result = array();
        if ($count != 0) {
            $result = ["count" => $count, "average" => round($sum/$count,1)];
            return $result;
        } else {
            $result = ["count" => $count, "average" => 0];
            return $result;
        }
    }

    static function checkExistFeedback($customerID, $notifyID) {
        return self::where('feedbacks.customers_id', $customerID)
           ->where('feedbacks.notifications_id', $notifyID)
           ->count();
    }
    
}
