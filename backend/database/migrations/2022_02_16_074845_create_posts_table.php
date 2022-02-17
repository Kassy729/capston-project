<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignID('user_id')  //Users의 user_id를 참조한다
                ->constrained()
                ->onDelete('cascade');  //같이 삭제 요청
            $table->string('kind');
            $table->integer('time');
            $table->integer('calorie');
            $table->string('average_speed');
            $table->string('altitude');
            $table->integer('distance');
            $table->string('img');
            $table->string('content');
            $table->string('range');
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
        Schema::dropIfExists('posts');
    }
}
