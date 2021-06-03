<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sets');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sets', function (Blueprint $table) {
            $table->id();
            $table->integer('baseSetSize')->nullable();
            $table->string('block')->nullable();
            $table->string('code', 8)->unique();
            $table->boolean('isFoilOnly')->nullable();
            $table->boolean('isForeignOnly')->nullable();
            $table->boolean('isNonFoilOnly')->nullable();
            $table->boolean('isOnlineOnly')->nullable();
            $table->boolean('isPartialPreview')->nullable();
            $table->string('keyruneCode')->nullable();
            $table->integer('mcmid')->nullable();
            $table->integer('mcmIdExtras')->nullable();
            $table->string('mcmName')->nullable();
            $table->string('mtgoCode')->nullable();
            $table->string('name')->nullable();
            $table->string('parentCode')->nullable();
            $table->date('releaseDate')->nullable();
            $table->integer('tcgplayerGroupId')->nullable();
            $table->integer('totalSetSize')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }
}
