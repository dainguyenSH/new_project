<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Merchant;
use View;
use Auth;
use App\OptionValue;
use Redirect;

class AjaxController extends Controller
{
    /**
     * Ajax Paginate Partner
     */

    public function getPageMerchant(Request $request) {
        $month = OptionValue::where('options_id' ,5)->get();
        $getPackage = OptionValue::where('options_id' ,2)->get();
        $data = Merchant::whereIn('kind',[1,2])->where('name', 'LIKE', '%'.$request->keyword.'%')->paginate(env('PAGE'));
        return View::make('admin.index-paginate' , array(
            'merchant'      => $data,
            'package'       => $getPackage,
            'month'         => $month
        ))->render();
    }

    /**
     * Ajax Paginate and Search Partner
     */

    public function getSearchMerchant(Request $request) {

        $merchantsResultSearch = Merchant::whereIn('kind',[1,2])->where('name', 'LIKE', '%'.$request->keyword.'%')->paginate(env('PAGE'));
        return View::make('admin.index-paginate' , array(
            'merchant'      => $merchantsResultSearch,
        ))->render();

    }



    /**
     * Ajax Paginate Partner
     */

    public function getPagePartner(Request $request) {
        $partners = Merchant::select('id','logo','name','active','kind')->where('kind',4)->where('name', 'LIKE', '%'.$request->keyword.'%')->paginate(env('PAGE'));
        return View::make('admin.partner-paginate' , array(
            'partners'      => $partners,
        ))->render();
    }

    /**
     * Ajax Paginate and Search Partner
     */

    public function getSearchPartner(Request $request) {

        $partnersResultSearch = Merchant::select('id','logo','name','active','kind')->where('kind',4)->where('name', 'LIKE', '%'.$request->keyword.'%')->paginate(env('PAGE'));
        return View::make('admin.partner-paginate' , array(
            'partners'      => $partnersResultSearch,
        ))->render();

    }


    /**
     * Ajax Paginate New Merchant
     */

    public function getPageNewPartner(Request $request) {
        $partners = Merchant::select('id','logo','name','active','kind')->where('name', 'LIKE', '%'.$request->keyword.'%')->where('kind',5)->paginate(env('PAGE'));
        return View::make('admin.partner-paginate' , array(
            'partners'      => $partners,
        ))->render();
    }

    /**
     * Ajax Paginate and Search new Merchant
     */

    public function getSearchNewMerchant(Request $request) {

        $newPartnersResultSearch = Merchant::select('id','logo','name','active','kind')->where('kind',5)->where('name', 'LIKE', '%'.$request->keyword.'%')->paginate(env('PAGE'));
        return View::make('admin.partner-paginate' , array(
            'partners'      => $newPartnersResultSearch,
        ))->render();

    }


    /**
     * Ajax Paginate Boo Merchant
     */

    public function getPageBooPartner(Request $request) {
        $partners = Merchant::select('id','logo','name','active','kind')->where('name', 'LIKE', '%'.$request->keyword.'%')->where('kind',3)->paginate(env('PAGE'));
        return View::make('admin.partner-paginate' , array(
            'partners'      => $partners,
        ))->render();
    }

    /**
     * Ajax Paginate and Search Boo Merchant
     */

    public function getSearchBooMerchant(Request $request) {

        $newPartnersResultSearch = Merchant::select('id','logo','name','active','kind')->where('kind',3)->where('name', 'LIKE', '%'.$request->keyword.'%')->paginate(env('PAGE'));
        return View::make('admin.partner-paginate' , array(
            'partners'      => $newPartnersResultSearch,
        ))->render();

    }

}
