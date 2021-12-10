<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardColorTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_color');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_color', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('card_id')->index();
            $table->unsignedBigInteger('color_id')->index();
            $table->string('type')->default('color');
            $table->timestamps();
        });
    }
}
