<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardFrameEffectTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_frame_effect');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_frame_effect', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('frame_effect_id');
            $table->unsignedBigInteger('card_id');
            $table->timestamps();
        });
    }
}
