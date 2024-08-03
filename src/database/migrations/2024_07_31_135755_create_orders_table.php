<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('orderID');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('total_price', 10, 2);
            $table->timestamps();

            $table->foreign('item_id')->references('itemID')->on('items')->onDelete('cascade');
            $table->foreign('user_id')->references('userID')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
