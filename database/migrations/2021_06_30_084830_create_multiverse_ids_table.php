<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultiverseIdsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('multiverse_ids');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multiverse_ids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('multiverse_id')->index();
            $table->unsignedBigInteger('card_id')->index();
            $table->timestamps();
        });
    }
}
