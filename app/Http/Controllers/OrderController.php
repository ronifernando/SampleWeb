<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Jobs\CancelJob;
use Auth;
use App\Order;
use App\Product;
use App\Prepaid;
use Session;

class OrderController extends BaseController
{
    public function index()
    {
        $data = Order::getOrder(Auth::user()->id)->paginate(20);
        return view('orders.orderhistory', compact(['data']));
    }

    public function search(){
        $key = Input::get('search');
        if(strlen($key)==10){
            $data = Order::where('order_no', $key)->orderBy('created_at', 'DSC')->get();
        }else{
            $data = Order::where('order_no','LIKE','%'.$key.'%')->orderBy('created_at', 'DSC')->paginate(20);
        }
        return view('orders.orderhistory', compact(['data']));
    }

    public function success()
    {
        $data = Session::get( 'status' );

        if(isset($data)){
            CancelJob::dispatch($data[0])->delay(now()->addMinutes(5));
            return view('orders.success', compact(['data']));
        }
        return redirect('home');
    }

    public function payment(Request $request)
    {   
        $status = Session::get( 'status' );
        $data = Input::get('data');
        return view('orders.payorder', compact(['data','status']));
    }

    public function addpayment(Request $request)
    {
        $data = Input::get('orderno');
        
        if(isset($data)){

            if(!empty(Order::checkUser($data, Auth::user()->id)->first())){
                do{$rand = $this->randomNumber(8);}while(!empty(Order::checkShippingCode($rand)));
                Order::changePaidstatus($rand, $data);
                
                return redirect('orders');
            }else{
                $status = "Order no invalid";
                return redirect('payment')->with('status', $status);
            }
        }
        return redirect('payment');
    }

    public function randomNumber($length) {
        $result = '';
    
        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
    
        return $result;
    }

    public function successchance($goodtime) {
        
        $result = mt_rand(0, 100);

        if($goodtime>=9 && $goodtime<=17){
            if($result<=90)
                return 1;
            else
                return 2;
        }else{
            if($result<=40)
                return 1;
            else
                return 2;
        }
        return 1;
    }
}
