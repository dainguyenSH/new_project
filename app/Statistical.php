<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statistical extends Model
{
    protected $table = 'statisticals';
    protected $primaryKey = 'id';
	protected $fillable = array('merchants_id', 'year', 'messages', 'feedbacks','status');
	
}
