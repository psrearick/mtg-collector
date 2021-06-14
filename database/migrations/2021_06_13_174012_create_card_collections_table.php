<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardCollectionsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_collections');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_collections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('card_id');
            $table->unsignedBigInteger('collection_id');
            $table->float('price_when_added')->nullable();
            $table->boolean('foil')->default(false);
            $table->text('description')->nullable();
            $table->string('condition')->nullable();
            $table->integer('quantity')->default(1);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }
}
