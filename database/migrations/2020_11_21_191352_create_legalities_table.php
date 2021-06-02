<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegalitiesTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('legalities');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legalities', function (Blueprint $table) {
            $table->id();
            $table->string('format')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('card_id');
            $table->timestamps();
        });
    }
}
