<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('live_video_id')->nullable();
            $table->string('name');
            $table->string('description');
            $table->integer('quantity')->nullable();
            $table->integer('price');
            $table->string('picture')->nullable();
            $table->unsignedInteger('expired_time')->nullable();
            $table->boolean('buyable')->default('0');
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
        Schema::dropIfExists('products');
    }
}
