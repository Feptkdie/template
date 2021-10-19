<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advice', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullabe();
            $table->string('views', 255)->nullabe();
            $table->string('likes', 255)->nullabe();
            $table->string('owner_id', 255)->nullabe();
            $table->string('owner_name', 255)->nullabe();
            $table->string('owner_avatar', 255)->nullabe();
            $table->text('content', 512)->nullabe();
            $table->string('cover', 255)->nullabe();
            $table->string('image_1', 255)->nullabe();
            $table->string('image_2', 255)->nullabe();
            $table->string('image_3', 255)->nullabe();
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
        Schema::dropIfExists('advice');
    }
}
