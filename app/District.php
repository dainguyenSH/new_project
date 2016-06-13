<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';
    protected $primaryKey = 'districts_id';

	protected $fillable = array('districts_id', 'provinces_id', 'name','location', 'type');
}
