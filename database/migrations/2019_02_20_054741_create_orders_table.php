<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('user_id');
            $table->string('order_no')->unique();
            $table->string('total_price');
            $table->string('shipping_code')->nullable();
            $table->morphs('orderable');
            $table->integer('paidstatus')->default(0); //1 success  //2 fail //3 is cancel
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
