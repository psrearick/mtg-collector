<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('artist')->nullable();
            $table->string('asciiName')->nullable();
            $table->string('availability')->nullable();
            $table->string('borderColor')->nullable();
            $table->string('cardKingdomFoilId')->nullable();
            $table->string('cardKingdomId')->nullable();
            $table->string('colorIdentity')->nullable();
            $table->string('colorIndicator')->nullable();
            $table->float('convertedManaCost', 10, 2)->nullable();
            $table->text('duelDeck')->nullable();
            $table->integer('edhrecRank')->nullable();
            $table->float('faceConvertedManaCost', 10, 2)->nullable();
            $table->string('faceName')->nullable();
            $table->string('flavorName')->nullable();
            $table->text('flavorText')->nullable();
            $table->string('frameVersion')->nullable();
            $table->text('hand')->nullable();
            $table->integer('hasAlternativeDeckLimit')->nullable();
            $table->integer('hasContentWarning')->nullable();
            $table->integer('hasFoil')->nullable();
            $table->integer('hasNonFoil')->nullable();
            $table->string('imagePath')->nullable();
            $table->integer('isAlternative')->nullable();
            $table->integer('isFullArt')->nullable();
            $table->integer('isOnlineOnly')->nullable();
            $table->integer('isOversized')->nullable();
            $table->integer('isPromo')->nullable();
            $table->integer('isReprint')->nullable();
            $table->integer('isReserved')->nullable();
            $table->integer('isStarter')->nullable();
            $table->integer('isStorySpotlight')->nullable();
            $table->integer('isTextless')->nullable();
            $table->integer('isTimeshifted')->nullable();
            $table->string('layout')->nullable();
            $table->string('leadershipSkills')->nullable();
            $table->string('life')->nullable();
            $table->string('loyalty')->nullable();
            $table->string('manaCost')->nullable();
            $table->string('mcmId')->nullable();
            $table->string('mcmMetaId')->nullable();
            $table->string('mtgArenaId')->nullable();
            $table->string('mtgjsonV4Id')->nullable();
            $table->string('mtgoFoilId')->nullable();
            $table->string('mtgoId')->nullable();
            $table->string('multiverseId')->nullable();
            $table->text('number')->nullable();
            $table->string('originalReleaseDate')->nullable();
            $table->text('originalText')->nullable();
            $table->string('originalType')->nullable();
            $table->string('otherFaceIds')->nullable();
            $table->string('power')->nullable();
            $table->integer('price_foil')->nullable();
            $table->integer('price_normal')->nullable();
            $table->text('printings')->nullable();
            $table->string('rarity')->nullable();
            $table->string('scryfallId')->nullable();
            $table->string('scryfallIllustrationId')->nullable();
            $table->string('scryfallOracleId')->nullable();
            $table->unsignedBigInteger('set_id')->nullable();
            $table->string('side')->nullable();
            $table->string('tcgplayerProductId')->nullable();
            $table->text('text')->nullable();
            $table->string('toughness')->nullable();
            $table->string('type')->nullable();
            $table->string('uuid')->nullable();
            $table->text('variations')->nullable();
            $table->text('watermark')->nullable();
            $table->timestamps();
        });
    }
}
