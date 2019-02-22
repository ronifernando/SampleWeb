<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    public function orderable()
    {
        return $this->morphTo();
    }

    public function scopeCheckUser($query, $data, $userid)
    {
        return $query->where('order_no',$data)
                        ->where('user_id', $userid)
                        ->where('paidstatus', 0);
    }

    public function scopeChangePaidstatus($query, $rand, $data)
    {
        return $query->where(function ($query) use ($rand,$data){
                            $query->where('order_no', $data)
                                    ->where('orderable_type', "App\Product")
                                    ->update([
                                        'paidstatus' => 1,
                                        'shipping_code' => $rand
                                    ]);})
                        ->orWhere(function ($query) use ($data) {
                            //9-17
                            $time = date('G', time());
                            $paid = $this->successchance($time);

                            $query->where('order_no', $data)
                                    ->where('orderable_type', "App\Prepaid")
                                    ->update([
                                        'paidstatus' => $paid
                                    ]);
                                });
    }

    public function scopeCheckShippingCode($query, $rand)
    {
        return $query->where('shipping_code',$rand)->first();
    }

    public function scopeGetOrder($query, $userid)
    {
        return $query->where('user_id', $userid)->with('orderable')->orderBy('created_at', 'DSC');
    }
}