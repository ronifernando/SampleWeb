<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;
use App\Order;

class PrepaidController extends BaseController
{
    public function index()
    {
        return view('orders.prepaid');
    }

    public function store(Request $request)
    {
        // $phoneRegex = "/\+?(?:[ -]?\d+)+|(\d+)(?:[ -]\d+)/";
        $phoneRegex = "/081\d{4,9}$/";

        $this->validate($request,[
            'phonenumber' => ['required', "regex:$phoneRegex"],
            'price' => ['required','numeric','in:10000,50000,100000'],
        ]);

        $order = new Order;

        do{$rand = $this->randomNumber(10);}while(!empty(Order::where('order_no',$rand)->first()));

        $order->user_id= Auth::user()->id;
        $order->order_no = $rand;
        $order->mobile_number = $phonenumber = Input::get('phonenumber');
        $order->price = $price = Input::get('price') + (Input::get('price')*0.05);
        $order->product_type = 1;
        $order->save();

        return redirect('success')
                        ->with('status', [$rand,$phonenumber,$price]);
    }

    public function randomNumber($length) {
        $result = '';
    
        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
    
        return $result;
    }
}
