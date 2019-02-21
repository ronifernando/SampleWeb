<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
// use Illuminate\Support\Facades\Log;
use App\Order;

class CancelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function handle()
    {
        Order::where('order_no', $this->data )->where('paidstatus',0)->update(['paidstatus'=> 3 ]);
    }
}
