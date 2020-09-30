<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArrivalImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrival_image', function (Blueprint $table) {
            $table->id();
            $table->integer('arrival_id');
            // $table->foreign('arrival_id')->references('id')->on('arrival')->constrained()->onDelete('cascade');
            $table->integer('image_id');
            // $table->foreign('image_id')->references('id')->on('images')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('arrival_image');
    }
}
