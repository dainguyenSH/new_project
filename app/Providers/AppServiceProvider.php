<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use View;
use App\Merchant;
use App\OptionValue;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // if ( Auth::check() ) {
        //     View::composer('*', function($view) {
        //     $getPackages = Merchant::where('package' , Auth::merchant()->get()->package ? Auth::merchant()->get()->package : 0);
        //     if ( $getPackages->count() ) {
        //         $packageId = $getPackages->first();

        //         $getPackagesToMaster = OptionValue::where('id' , $packageId->package);

        //         $packagessss = $getPackagesToMaster->first();
        //     } else {
        //         $packagessss = '';
        //     }
        //     $view->with('packagessss', $packagessss);
        // });
        // }
         Validator::extend('checkCurrentTime', function($attribute, $value, $parameters, $validator) {
            if(strtotime($value) > time()) {
                return true;
            } else {
                return false;
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
