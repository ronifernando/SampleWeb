<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;

class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
