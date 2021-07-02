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
            $table->string('setId')->index();
            $table->string('name')->index();
            $table->string('code', 6)->index();
            $table->string('mtgoCode')->nullable();
            $table->integer('tcgPlayerGroupId')->nullable();
            $table->string('type');
            $table->date('releaseDate')->nullable();
            $table->string('block')->nullable();
            $table->string('blockCode')->nullable();
            $table->string('parentCode')->nullable();
            $table->integer('setSize');
            $table->integer('printedSetSize')->nullable();
            $table->boolean('isOnlineOnly');
            $table->boolean('isFoilOnly');
            $table->boolean('isNonFoilOnly');
            $table->string('scryfallUri');
            $table->string('scryfallApiUri');
            $table->string('scryfallSvgUri');
            $table->string('scryfallApiSearch');
            $table->string('svgPath')->nullable();
            $table->timestamps();
        });
    }
}
