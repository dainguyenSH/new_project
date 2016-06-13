<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Kbwebs\MultiAuth\PasswordResets\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Kbwebs\MultiAuth\PasswordResets\Contracts\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Hash;
use DB;

class Merchant extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'merchants';
    protected $fillable = array('user_name', 'email', 'password', 'password_confirmation', 'text_color', 'active_token','active','active_at','facebook_token','google_token','name','logo','color','field','information','card_type','card_info','package','start_day','end_day','message_count','package_status','kind');
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    // protected $hidden = ['password', 'remember_token'];

    /**
     * Lấy toàn bộ merchant mà đã được active theo customer
     */
    static function listByCustomer($customerID, $lastID, $limit=20)
    {
        if ( $lastID ) {
            $whereLastId = "AND merchants.id < ?";
            $bindings = [$customerID, $lastID];
        } else {
            $whereLastId = "";
            $bindings = [$customerID];
        }

        $rawSQLQuery = "SELECT merchants.id, merchants.name, merchants.color, merchants.logo, merchants.kind, optionvalues.name AS level 
                        FROM merchants 
                        INNER JOIN customer_merchants ON merchants.id = customer_merchants.merchants_id 
                        AND customer_merchants.customers_id = ? 
                        AND customer_merchants.status = 1 
                        LEFT JOIN optionvalues ON customer_merchants.level = optionvalues.id 
                        WHERE merchants.active = 1 
                        $whereLastId 
                        ORDER BY merchants.id DESC 
                        LIMIT $limit";

        return DB::select($rawSQLQuery, $bindings);
    }

    /**
     * Lấy toàn bộ merchant mà đã được active theo IDs
     * @param  $IDs [Không lấy merchant theo các ID này]
     */
    static function listMerchantByIds($IDs, $customerID)
    {
        return self::where('merchants.active', 1)
            ->whereNotIn('merchants.id', $IDs)
            ->whereRaw("merchants.id NOT IN ( 
                    SELECT DISTINCT customer_merchants.merchants_id 
                    FROM customer_merchants 
                    WHERE customer_merchants.customers_id = $customerID 
                    AND customer_merchants.status = 1)")
            ->select(['merchants.id', 'merchants.name', 'merchants.color', 'merchants.logo', 'merchants.kind'])
            ->orderBy('merchants.name')
            ->get();
    }

    static function countMerchantByFilter($search_box) {
        $info =  Merchant::select()
        ->whereIn('kind',[1,2])
        ->where("merchants.name","like", "%".$search_box."%")
        ->count();
        return $info;
    }

    static function getMerchantByFilter($search_box, $page) {
        $info =  Merchant::select()
        ->whereIn('kind',[1,2])
        ->where("merchants.name","like", "%".$search_box."%")
        ->skip($page * env('SEARCH_PAGE'))->take(env('SEARCH_PAGE'))
        ->get();
        return $info;
    }
    /**
     * Top 3 merchant được customer sử dụng nhiều nhất
     */
    static function listTopMerchant($customerID)
    {
        $rawSQLQuery = "SELECT t.* 
                        FROM ( 
                            SELECT merchants.id, merchants.name, merchants.color, merchants.logo, merchants.kind 
                            FROM merchants 
                            LEFT JOIN customer_merchants ON customer_merchants.merchants_id = merchants.id 
                            AND customer_merchants.status = 1 
                            WHERE merchants.active = 1 
                            GROUP BY merchants.id 
                            ORDER BY COUNT(customer_merchants.customers_id) DESC 
                        ) AS t 
                        WHERE t.id NOT IN ( 
                            SELECT DISTINCT customer_merchants.merchants_id 
                            FROM customer_merchants 
                            WHERE customer_merchants.customers_id = ? 
                            AND customer_merchants.status = 1 
                        ) 
                        LIMIT 3";
                        
        return DB::select($rawSQLQuery, [$customerID]);
    }

    static function getMerchantById($merchantID)
    {
        return self::find($merchantID);
    }
    static function getMerchantIdByNames($names) 
    {   
        return self::select(['id', 'name'])->whereIn('name', $names)->where('active', 1)->get();
        // return self::where('name',$name)->first() != null ? self::where('name',$name)->first()->id : null ;
    }
    

}
