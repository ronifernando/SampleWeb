<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Jobs\CancelJob;
use Auth;
use App\Order;
use Session;

class OrderController extends BaseController
{
    public function index()
    {
        $data = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'DSC')->paginate(20);
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
            // echo dd($data[0]);
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

            if(!empty(Order::where('order_no',$data)
                            ->where('user_id', Auth::user()->id)
                            ->where('paidstatus', 0)
                            ->first())){
                do{$rand = $this->randomNumber(8);}while(!empty(Order::where('shipping_code',$rand)->first()));
                Order::where(function ($query) use ($rand,$data){
                            $query->where('order_no', $data)
                                    ->where('product_type', 0)
                                    ->update([
                                        'paidstatus' => 1,
                                        'shipping_code' => $rand
                                    ]);})
                        ->orWhere(function ($query) use ($data) {
                            //9-17
                            $time = date('G', time());
                            $paid = $this->successrate($time);

                            $query->where('order_no', $data)
                                    ->where('product_type', 1)
                                    ->update([
                                        'paidstatus' => $paid
                                    ]);
                                });
                
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

    public function successrate($goodtime) {
        
        $result = mt_rand(0, 100);

        if($goodtime>=9 && $goodtime<=17){
            if($result<=40)
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
