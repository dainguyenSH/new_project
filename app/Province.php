<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';
    protected $primaryKey = 'provinces_id';

	protected $fillable = array('provinces_id', 'name', 'type');
}
