<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Order;
use App\Prepaid;

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
        
        $array = DB::transaction(function(){
            $prepaid = new Prepaid;
            $prepaid->mobile_number = $phonenumber = Input::get('phonenumber');
            $prepaid->value = $value = Input::get('price');
            $prepaid->save();
            
            do{$rand =  $this->randomNumber(10);}while(!empty(Order::where('order_no',$rand)->first()));

            $order = new Order;
            $order->user_id= Auth::user()->id;
            $order->order_no = $rand;
            $order->total_price = $total_price = Input::get('price') + (Input::get('price')*0.05);
 
            $save= Prepaid::find($prepaid->id);
            $status = $save->orders()->save($order);

            if( !$status )
            {
                throw new \Exception('Failed to create order');
            }
            return compact('rand', 'phonenumber', 'total_price', 'value');
        });        
        return redirect('success')
                        ->with('status', [$array['rand'],$array['phonenumber'],$array['total_price']]);
    }

    public function randomNumber($length) {
        $result = '';
    
        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
    
        return $result;
    }
}
