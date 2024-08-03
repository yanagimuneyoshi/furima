<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('commentID');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('item_id');
            $table->text('contend');
            $table->timestamps();

            $table->foreign('user_id')->references('userID')->on('users')->onDelete('cascade');
            $table->foreign('item_id')->references('itemID')->on('items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
