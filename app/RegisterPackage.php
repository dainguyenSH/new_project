<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisterPackage extends Model
{
     protected $table = 'register_packagers';
    protected $primaryKey = 'id';

	protected $fillable = array('package', 'merchants_id','month', 'name','mobile', 'email', 'content','status');
}
