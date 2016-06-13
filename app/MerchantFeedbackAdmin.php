<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantFeedbackAdmin extends Model
{
    protected $table = 'merchant_feedback_admins';
    protected $primaryKey = 'id';

	protected $fillable = array('merchants_id', 'messages','status');
}
