<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('artist')->nullable();
            $table->string('asciiName')->nullable();
            $table->string('borderColor')->nullable();
            $table->integer('edhrecRank')->nullable();
            $table->string('faceName')->nullable();
            $table->text('flavorText')->nullable();
            $table->string('frameVersion')->nullable();
            $table->integer('hasFoil')->nullable();
            $table->integer('hasNonFoil')->nullable();
            $table->string('image_path')->nullable();
            $table->integer('isFullArt')->nullable();
            $table->integer('isPromo')->nullable();
            $table->integer('isReprint')->nullable();
            $table->string('layout')->nullable();
            $table->string('mcmId')->nullable();
            $table->string('mtgArenaId')->nullable();
            $table->string('mtgjsonV4Id')->nullable();
            $table->string('multiverseId')->nullable();
            $table->string('name');
            $table->text('number')->nullable();
            $table->text('originalText')->nullable();
            $table->string('originalType')->nullable();
            $table->string('power')->nullable();
            $table->string('promoTypes')->nullable();
            $table->string('scryfallId')->nullable();
            $table->string('scryfallIllustrationId')->nullable();
            $table->string('scryfallOracleId')->nullable();
            $table->string('setCode')->nullable();
            $table->string('side')->nullable();
            $table->string('tcgplayerProductId')->nullable();
            $table->text('text')->nullable();
            $table->string('toughness')->nullable();
            $table->string('type')->nullable();
            $table->char('uuid', 36)->unique();
            $table->text('watermark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tokens');
    }
}
