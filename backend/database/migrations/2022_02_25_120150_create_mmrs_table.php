<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mmrs', function (Blueprint $table) {
            $table->id();
            $table->foreignID('user_id')  //Users의 user_id를 참조한다
                ->constrained()
                ->onDelete('cascade');  //같이 삭제 요청
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
        Schema::dropIfExists('mmrs');
    }
}
