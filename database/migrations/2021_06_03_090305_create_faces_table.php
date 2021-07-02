<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacesTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faces');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('card_id')->index();
            $table->string('name')->index();
            $table->string('artist')->nullable();
            $table->string('flavorText')->nullable();
            $table->string('illustrationId')->nullable();
            $table->string('loyalty')->nullable();
            $table->string('manaCost')->nullable();
            $table->text('oracleText')->nullable();
            $table->string('power')->nullable();
            $table->string('printedName')->nullable();
            $table->text('printedText')->nullable();
            $table->string('printedTypeLine')->nullable();
            $table->string('toughness')->nullable();
            $table->string('typeLine')->nullable();
            $table->text('watermark')->nullable();
            $table->timestamps();
        });
    }
}
