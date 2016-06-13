<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardOption extends Model
{
    protected $table = "card_options";

    protected $primaryKey = 'id';

	protected $fillable = array('merchants_id', 'info');
}
