<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Merchant;
use Hash;
use DB;

class Deal extends Model
{
    protected $table = 'deals';

    protected $fillable = array('merchants_id', 'name', 'description', 'apply_objects', 'start_day', 'end_day', 'image_avatar', 'image_content', 'stores', 'slug', 'status');

    public static function storeDeal($dataDeal) {
    	$deal = new Deal;
    	$deal->merchants_id = $dataDeal['merchant_id'];
        $deal->name = $dataDeal['titleIncentives'];
        $deal->image_content = $dataDeal['image_content'];
        $deal->apply_objects = $dataDeal["object-apply"];
    
        $deal->description = $dataDeal['contentIncentives'];
        $deal->image_avatar = $dataDeal['image_avatar'];
        $deal->start_day = $dataDeal['start-date'];
        $deal->end_day = $dataDeal['end-date'];
        $deal->slug = str_slug($dataDeal['titleIncentives'], "-".time());
        
    	$deal->save();

    	if ($deal != null) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    public static function getDealActive($merchant_id) {
        $result = Deal::where('merchants_id',$merchant_id)
        ->where("status",1)
        ->get();
        return $result;
    }

    static function getDealActiveById($customerID, $lastEndDay, $limit=20) {
        // $data = Deal::select('deals.id', 'deals.name', 'deals.description', 'deals.image_avatar', 'merchants.logo')
        //   ->join('merchants', 'merchants.id', '=', 'deals.merchants_id')
        //   ->join('customer_merchants','customer_merchants.merchants_id','=', 'merchants.id')
        //   // ->join('customers','customers.id','=','customer_merchants.customers_id')
        //   ->where('customer_merchants.customers_id', '=', $customerID)
        //   ->whereRaw('((NOW() between deals.start_day and deals.end_day and deals.status <> 2)
        //               OR (NOW() < deals.start_day and deals.status = 1)) and customer_merchants.customers_id = $customerID')
        //   ->orderBy('deals.id', 'desc')
        //   ->limit($limit);
      if ( $lastEndDay ) {
            $where = "AND deals.end_day > ?";
            $bindings = [$customerID, $lastEndDay];
        } else {
            $where = "";
            $bindings = [$customerID];
        }

      $rawSQLQuery = "SELECT deals.id, deals.end_day, deals.name, deals.description, deals.image_avatar, deals.radio_image, merchants.logo FROM deals
                      INNER JOIN merchants ON deals.merchants_id = merchants.id
                      AND merchants.active = 1
                      INNER JOIN customer_merchants ON customer_merchants.merchants_id = merchants.id
                      AND customer_merchants.customers_id = ?
                      AND customer_merchants.status = 1
                      WHERE ((NOW() between deals.start_day AND deals.end_day AND deals.status <> 2)
                            OR (NOW() < deals.start_day AND deals.status = 1 ))
                      $where
                      ORDER BY deals.end_day ASC
                      LIMIT $limit";


        return DB::select($rawSQLQuery,$bindings);   

    }

    static function getAllDeal($lastEndDay, $limit=20) {
        /*$data = self::select('deals.id', 'deals.end_day','deals.name', 'deals.description', 'deals.image_avatar', 'merchants.logo')
                   // ->where('deals.status', '<>', 2)
                   // ->whereRaw('NOW() between deals.start_day and deals.end_day')
                    ->join('merchants', 'merchants.id', '=', 'deals.merchants_id')
                    ->whereRaw('(NOW() between deals.start_day and deals.end_day and deals.status <> 2)
                      OR (NOW() < deals.start_day and deals.status = 1)')
                    ->orderby('deals.end_day', 'asc')
                    ->limit($limit);
        if( $lastEndDay ) {
          dd('sss');
            $data->where('deals.end_days', '>', $lastEndDay);
        }

        return $data->get();*/
      if ( $lastEndDay ) {
            $where = "AND deals.end_day > ?";
            $bindings = [$lastEndDay];
        } else {
            $where = "";
            $bindings = [];
        }

      $rawSQLQuery = "SELECT deals.id, deals.end_day, deals.name, deals.description, deals.image_avatar, deals.radio_image, merchants.logo
                      FROM deals
                      INNER JOIN merchants on merchants.id = deals.merchants_id 
                      WHERE ((NOW() between deals.start_day and deals.end_day and deals.status <> 2)
                      OR (NOW() < deals.start_day and deals.status = 1))
                      $where
                      ORDER BY `deals`.`end_day` ASC
                      LIMIT $limit";


        return DB::select($rawSQLQuery,$bindings);
    }

    static function getDealDetailById($dealID){
        return self::select('deals.merchants_id', 'deals.name as nameOfDeals', 'deals.image_avatar', 'deals.image_content', 'deals.description', 'deals.apply_objects', 'deals.start_day', 'deals.end_day', 'merchants.name as nameOfMerchant')
           ->where('deals.id', '=', $dealID)
           ->join('merchants', 'merchants.id', '=', 'deals.merchants_id')
           ->first();
    }
}
