<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerMerchant extends Model
{
    protected $table = "customer_merchants";

    protected $fillable = array('customers_id','merchants_id','avatar', 'front_card_image', 'back_card_image', 'barcode', 'point', 'current_point','level', 'status', 'info');

    static function checkExistsById($customerID) {
    	return self::select('customers_id')
                    ->where('customers_id', $customerID)
                    ->where('status', 1)
                    ->count();
    }

    /**
     * Get Customer Merchant by customer ID và merchant ID
     */
    static function getByCustomerIdMerchantId($customerID, $merchantID)
    {
    	return self::where('customers_id', $customerID)->where('merchants_id', $merchantID)->first();
    }

    /**
     * Remove merchant từ list merchant mà customer đã chọn
     * @param  [type] $customerID [Customer ID]
     * @param  [type] $merchantID [Merchant ID]
     * @return [int]             [Số bản ghi được update]
     */
    static function removeMerchantOfCustomer($customerID, $merchantID)
    {
        return self::where('customers_id', $customerID)->where('merchants_id', $merchantID)->update(['status' => 0]);
    }
    static function countCustomerByLevel($merchants_id, $level ) {
        return self::where('merchants_id', $merchants_id)->where('level',$level)->count();
    }
}
