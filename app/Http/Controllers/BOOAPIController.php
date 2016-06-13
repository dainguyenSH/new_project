<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BOOAPIController extends Controller
{
    /**
     * Index page
     */
    function getIndex()
    {
        return 'Nếu là một người dùng bình thường, sẽ không bao giờ truy cập vào đường link này!';
    }
}
