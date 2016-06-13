<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ErrorsController extends Controller
{
    public function manage401() {
        return redirect('login/manage')->withErrors('Phiên đăng nhập Thu ngân của bạn đã hết hạn. Vui lòng đăng nhập lại');
    }

    public function merchant401() {
        return redirect('login')->withErrors('Phiên đăng nhập Merchant của bạn đã hết hạn. Vui lòng đăng nhập lại');
    }
}
