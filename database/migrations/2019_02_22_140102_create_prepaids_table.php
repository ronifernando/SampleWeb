<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrepaidsTable extends Migration
{

    public function up()
    {
        Schema::create('prepaids', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile_number');
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prepaids');
    }
}
