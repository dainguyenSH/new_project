<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
	protected $table = 'optionvalues';
    protected $primaryKey = 'id';

	protected $fillable = array('options_id', 'name', 'info');

	static function getOptionValue($card_type)
	{
		return self::select('id', 'name')
					->where('options_id', $card_type)
					->get()->toArray();
	}

	static function getIdNameOptionValue()
	{
		return self::select('id', 'name')
					->get()->toArray();
	}
}
