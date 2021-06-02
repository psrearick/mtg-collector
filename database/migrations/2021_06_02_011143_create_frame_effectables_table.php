<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrameEffectablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frame_effectables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('frame_effects_id');
            $table->unsignedBigInteger('frame_effectable_id');
            $table->string('frame_effectable_type');
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
        Schema::dropIfExists('frame_efectables');
    }
}
