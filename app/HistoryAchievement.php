<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Auth;

class HistoryAchievement extends Model
{
    protected $table = 'history_achievements';
    protected $primaryKey = 'id';

	protected $fillable = array('customers_id', 'stores_id' , 'order_id', 'type', 'change_points', 'status');

	//All History
	public static function getAllHistoryOfMerchant($arrStore) {
	    $info =  Customer::select("customers.id", "customers.customers_code" ,"history_achievements.created_at","customers.name","customers.active","customers.email","history_achievements.order_id","history_achievements.type","history_achievements.change_points","history_achievements.stores_id","stores.store_name")
	    ->join("history_achievements","customers.id","=","history_achievements.customers_id")
	    ->join("stores", "history_achievements.stores_id","=","stores.id")
	        // ->where("history_achievements.customers_id","=",$customers_id)
	        ->whereIn("history_achievements.stores_id" , $arrStore)
	        ->orderBy("history_achievements.created_at","desc")
	        // ->where("customers.active","=",1)
	        ->paginate(env('PAGE'));
	     return $info;
	}

	public static function getHistoryOfMerchant($customers_id, $arrStore) {
	    $info =  Customer::select("customers.id","history_achievements.created_at","customers.name","customers.active","customers.email","history_achievements.order_id","history_achievements.type","history_achievements.change_points","history_achievements.stores_id","stores.store_name")
	    ->join("history_achievements","customers.id","=","history_achievements.customers_id")
	    ->join("stores", "history_achievements.stores_id","=","stores.id")
	        ->where("history_achievements.customers_id","=",$customers_id)
	        ->whereIn("history_achievements.stores_id" , $arrStore)
	        ->orderBy("history_achievements.created_at","desc")
	        // ->where("customers.active","=",1)
	        ->paginate(env('PAGE'));
	     return $info;
	}
	public static function getAllstoryOfMerchant() {
	    $info =  Customer::select("customers.id","history_achievements.created_at","customers.name","customers.active","customers.email","history_achievements.order_id","history_achievements.type","history_achievements.change_points","history_achievements.stores_id","stores.store_name")
	    ->join("history_achievements","customers.id","=","history_achievements.customers_id")
	    ->join("stores", "history_achievements.stores_id","=","stores.id")
	        // ->where("history_achievements.stores_id","=", Auth::manage()->get()->id)
	        ->where("customers.active","=",1)
	        ->get();
	     return $info;
	}
	public static function getAllHistoryAchievementByCustomerId($id, $currentDate) {
		$info =  Customer::select("customers.id","customers.customers_code","history_achievements.customers_id","history_achievements.created_at","customers.name","customers.active","customers.email","history_achievements.order_id","history_achievements.type","history_achievements.change_points","history_achievements.stores_id","stores.store_name")
	    ->join("history_achievements","customers.id","=","history_achievements.customers_id")
	    ->join("stores", "history_achievements.stores_id","=","stores.id")
	    	->orderby('history_achievements.id','desc')
	        ->where("history_achievements.stores_id","=", $id)
	        ->where("customers.active","=",1)
	        ->where("history_achievements.created_at", "like",  $currentDate. "%")
	        ->paginate(env('PAGE'));
	     return $info;
	}

	static function getTransactionById($customerID, $merchantID, $lastID, $limit = 20)
	{
		$data = self::select('history_achievements.id', 'history_achievements.type', 'history_achievements.change_points', 'history_achievements.created_at')
					->where('history_achievements.customers_id', $customerID)
					->whereRaw('history_achievements.stores_id IN (SELECT stores.id FROM stores WHERE stores.merchants_id = ?)', [$merchantID])
					->orderBy('history_achievements.id', 'desc')
					->limit($limit);

		if( $lastID ) {
			$data->where('history_achievements.id', '<', $lastID);
		}

		return $data->get();
	}
}
