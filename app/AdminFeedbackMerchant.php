<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminFeedbackMerchant extends Model
{
    protected $table = 'admin_feedback_merchants';
    protected $primaryKey = 'id'; 

	protected $fillable = array('merchant_feedback_admins_id', 'admins_id','messages', 'status');
}
