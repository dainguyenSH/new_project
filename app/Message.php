<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id';

	protected $fillable = array('merchants_id', 'deals_id','content', 'count','status');

	public static function getMessageInfo($merchants_id) {
    	$info = Message::where('merchants_id',$merchants_id)->paginate(5);
    	return $info;
    }

    public static function storeMessage($dataMessage) {
    	
    	$message = new Message;
    	$message->merchants_id = $dataMessage['merchants_id'];
    	$message->deals_id = $dataMessage['deals_id'];
    	$message->content = $dataMessage['content'];
    	$message->count = $dataMessage['count'];
    	$message->save();
    	
    	if ($message != null) {
    		return true;
    	} else {
    		return false;
    	}
    }
}
