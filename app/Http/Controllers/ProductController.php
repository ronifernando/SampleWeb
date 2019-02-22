<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Order;
use App\Product;

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

        $array = DB::transaction(function(){
            $product = new Product;
            $product->product_name = $product_name =Input::get('product');
            $product->address = $address = Input::get('address');
            $product->price = Input::get('price');
            $product->save();
            
            do{$rand =  $this->randomNumber(10);}while(!empty(Order::where('order_no',$rand)->first()));

            $order = new Order;
            $order->user_id= Auth::user()->id;
            $order->order_no = $rand;
            $order->total_price = $total_price = Input::get('price') + 10000;
 
            $save= Product::find($product->id);
            $status = $save->orders()->save($order);

            if( !$status )
            {
                throw new \Exception('Failed to create order');
            }
            return compact('rand', 'product_name', 'address', 'total_price');
        });        
        return redirect('success')
                        ->with('status', [$array['rand'],$array['product_name'],$array['address'],$array['total_price']]);
    }

    public function randomNumber($length) {
        $result = '';
    
        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
    
        return $result;
    }
}
