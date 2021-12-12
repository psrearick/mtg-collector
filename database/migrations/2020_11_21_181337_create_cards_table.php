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
            $table->string('name')->index();
            $table->string('name_normalized')->virtualAs("regexp_replace(name, '[^A-Za-z0-9]', '')")->index();
            $table->string('cardId')->index();
            $table->string('arenaId')->nullable();
            $table->string('languageCode')->nullable();
            $table->integer('mtgoId')->nullable();
            $table->integer('mtgoFoilId')->nullable();
            $table->integer('tcgplayerId')->nullable();
            $table->integer('cardmarketId')->nullable();
            $table->string('oracleId')->index();
            $table->string('printsSearchUri')->nullable();
            $table->string('rulingsUri')->nullable();
            $table->string('scryfallUri')->nullable();
            $table->string('scryfallApiUri')->nullable();
            $table->string('artist')->nullable();
            $table->boolean('booster')->nullable();
            $table->string('borderColor')->nullable();
            $table->string('cardBackId')->nullable();
            $table->text('collectorNumber')->nullable();
            $table->integer('hasContentWarning')->nullable();
            $table->integer('isOnlineOnly')->nullable();
            $table->string('frameVersion')->nullable();
            $table->boolean('isFullArt')->nullable();
            $table->boolean('isHighresImage')->nullable();
            $table->string('illustrationId')->nullable();
            $table->string('imageStatus')->nullable();
            $table->string('printedName')->nullable();
            $table->text('printedText')->nullable();
            $table->string('printedTypeLine')->nullable();
            $table->integer('isPromo')->nullable();
            $table->string('rarity')->nullable();
            $table->date('releaseDate')->nullable();
            $table->integer('isReprint')->nullable();
            $table->string('scryfallSetId')->nullable();
            $table->string('scryfallSetUri')->nullable();
            $table->unsignedBigInteger('set_id')->index();
            $table->integer('isStorySpotlight')->nullable();
            $table->integer('isTextless')->nullable();
            $table->integer('isVariation')->nullable();
            $table->string('variationOf')->nullable();
            $table->text('watermark')->nullable();
            $table->float('convertedManaCost', 10, 2)->nullable();
            $table->integer('edhrecRank')->nullable();
            $table->integer('hasFoil')->nullable();
            $table->integer('hasNonFoil')->nullable();
            $table->string('layout')->nullable();
            $table->string('handModifier')->nullable();
            $table->string('lifeModifier')->nullable();
            $table->string('loyalty')->nullable();
            $table->string('manaCost')->nullable();
            $table->text('oracleText')->nullable();
            $table->integer('isOversized')->nullable();
            $table->string('power')->nullable();
            $table->integer('isReserved')->nullable();
            $table->string('toughness')->nullable();
            $table->string('typeLine')->nullable();
            $table->string('imagePath')->nullable();
            $table->string('imagePngUri')->nullable();
            $table->string('imageBorderCropUri')->nullable();
            $table->string('imageArtCropUri')->nullable();
            $table->string('imageLargeUri')->nullable();
            $table->string('imageNormalUri')->nullable();
            $table->string('imageSmallUri')->nullable();
            $table->timestamps();
        });
    }
}
