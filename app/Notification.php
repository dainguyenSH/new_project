<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';

	protected $fillable = array('merchants_id', 'history_achievements_id', 'deals_id','content', 'type','count','status');
	
	static function getListNotificationById($customerID, $lastID, $limit = 20)
	{
		$data = self::select('notifications.id','notifications.content','notifications.type', 'notifications.created_at', 'notifications.stores_id', 'merchants.logo', 'merchants.name')
					->where('customers_id', '=', $customerID)
					->join('merchants', 'merchants.id', '=', 'notifications.merchants_id')
					->orderBy('notifications.id', 'desc')
					->limit($limit);

		if( $lastID ) {
			$data->where('notifications.id', '<', $lastID);
		}

		return $data->get();
	}

	static function getCountNotificationByIdCustomer($customerID)
	{
		return self::where('customers_id', $customerID)->where('read', 0)->count();
	}
	public static function storeNotification($dataMessage) {
    	
    	$message = new Notification;
    	$message->merchants_id = $dataMessage['merchants_id'];
    	$message->deals_id = $dataMessage['deals_id'];
    	$message->content = $dataMessage['content'];
        $message->count = $dataMessage['count'];
    	$message->percentage = $dataMessage['percentage'];
        
    	$message->save();
    	
    	if ($message != null) {
    		return true;
    	} else {
    		return false;
    	}
    }
    public static function getLastNotification($merchants_id) {
        $info = Notification::where('merchants_id',$merchants_id)->where('history_achievements_id',null)->orderBy('updated_at','DESC')->first();
        return $info;
    }
    public static function getMessageInfo($merchants_id) {
    	$info = Notification::where('merchants_id',$merchants_id)->where('history_achievements_id',null)->orderBy('updated_at','DESC')->paginate(env('PAGE'));
    	return $info;
    }

    public static function getNotificationByIdOfCustomer($customerID, $lastID, $limit=20)
    {
        if ( $lastID ) {
            $where = "AND notifications.id < ?";
            $bindings = [$customerID, $customerID, $customerID, $lastID];
        } else {
            $where = "";
            $bindings = [$customerID, $customerID, $customerID];
        }

        $rawSQLQuery = "SELECT notifications.id, notifications.content, merchants.name, 
                        merchants.logo, notifications.created_at,
                            history_achievements.type, history_achievements.change_points, deals.id as dealsID, 'none' AS sendmsg,
                            EXISTS (SELECT 1 FROM feedbacks WHERE feedbacks.customers_id = ? 
                            AND feedbacks.notifications_id = notifications.id) AS sendfeedback
                        FROM notifications
                        INNER JOIN merchants ON merchants.id = notifications.merchants_id
                        AND merchants.active = 1
                        AND EXISTS (
                         SELECT 1 FROM customer_merchants
                            WHERE customer_merchants.merchants_id = merchants.id
                            AND customer_merchants.status = 1
                            AND customer_merchants.customers_id = ?
                        )
                        LEFT JOIN history_achievements ON history_achievements.id = notifications.history_achievements_id
                        LEFT JOIN deals ON deals.id = notifications.deals_id
                        WHERE (history_achievements.customers_id = ? OR history_achievements.customers_id IS NULL)
                        $where
                        ORDER BY notifications.id DESC
                        LIMIT $limit";

        return DB::select($rawSQLQuery, $bindings);
    }
}
