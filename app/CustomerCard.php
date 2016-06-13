<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerCard extends Model
{
    protected $table = 'customer_cards';
    protected $primaryKey = 'id';

	protected $fillable = array('customers_id', 'barcode', 'name','logo','front_card_image','back_card_image','status');

}
