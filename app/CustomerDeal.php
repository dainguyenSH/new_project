<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerDeal extends Model
{
    protected $table = 'customer_deals';

    public static function getCountDealByIdCustomer($customerID)
    {
    	return self::where('customers_id', $customerID)->where('read', 0)->count();
    }
}
