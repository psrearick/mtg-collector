<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRulingsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rulings');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rulings', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->text('text')->nullable();
            $table->unsignedBigInteger('card_id');
            $table->timestamps();
        });
    }
}
