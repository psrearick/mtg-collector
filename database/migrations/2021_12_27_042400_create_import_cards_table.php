<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_cards', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('printing')->nullable();
            $table->string('collection_number')->nullable();
            $table->string('finish')->nullable();
            $table->string('foil')->nullable();
            $table->string('multiverse_id')->nullable();
            $table->string('scryfall_id')->nullable();
            $table->string('condition')->nullable();
            $table->string('language')->nullable();
            $table->foreignId('card_id')->nullable();
            $table->foreignId('import_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('imports_cards');
    }
}
