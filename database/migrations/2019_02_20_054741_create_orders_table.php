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
            $table->string('product_name')->default('-');
            $table->string('address')->default('-');
            $table->string('shipping_code')->default('-');
            $table->string('mobile_number')->default('-');
            $table->string('price')->default('-');
            $table->integer('product_type');//1 is prepaid ------- 0 is product
            // $table->morphs('product');
            $table->integer('paidstatus')->default(0); //1 is paid  //2 fail //3 is cancel
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
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
