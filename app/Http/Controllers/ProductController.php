<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;
use App\Order;

class ProductController extends BaseController
{
    public function index()
    {
        return view('orders.product');
    }

    public function store(Request $request)
    {

        $this->validate($request,[
            'product' => ['required', 'between:10,50'],
            'address' => ['required', 'between:10,50'],
            'price' => ['required','numeric'],
        ]);

        $order = new Order;

        do{$rand = $this->randomNumber(10);}while(!empty(Order::where('order_no',$rand)->first()));

        $order->user_id= Auth::user()->id;
        $order->order_no = $rand;
        $order->product_name = $product =Input::get('product');
        $order->address = $address = Input::get('address');
        $order->price = $price = Input::get('price') + 10000;
        $order->product_type = 0;
        $order->save();

        return redirect('success')
                        ->with('status', [$rand,$product, $address, $price]);
    }

    public function randomNumber($length) {
        $result = '';
    
        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
    
        return $result;
    }
}
