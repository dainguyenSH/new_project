<?php

namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Kbwebs\MultiAuth\PasswordResets\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Kbwebs\MultiAuth\PasswordResets\Contracts\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Store extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
	use Authenticatable, Authorizable, CanResetPassword;
	protected $table = "stores";

    protected $primaryKey = 'id';

	protected $fillable = array('merchants_id', 'user_name', 'email', 'password_confirmation', 'active', 'remember_token', 'store_name', 'address', 'mobile', 'status');

	static function getById($storeID){
        return self::find($storeID);
    }

    static function getNameStoreByNotifyID($notifyID) {
        return self::select('stores.store_name')
                   ->join('history_achievements', 'stores.id', '=', 'history_achievements.stores_id')
                   ->join('notifications', 'history_achievements.id', '=', 'notifications.history_achievements_id')
                   ->where('notifications.id', $notifyID)
                   ->get();
    }
}
