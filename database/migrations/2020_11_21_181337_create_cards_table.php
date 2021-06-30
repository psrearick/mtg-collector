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
            $table->string('cardId')->index();
            $table->string('arenaId')->nullable();
            $table->string('languageCode');
            $table->integer('mtgoId')->nullable();
            $table->integer('mtgoFoilId')->nullable();
            $table->integer('tcgplayerId')->nullable();
            $table->integer('cardmarketId')->nullable();
            $table->string('oracleId')->index();
            $table->string('printsSearchUri');
            $table->string('rulingsUri');
            $table->string('scryfallUri');
            $table->string('scryfallApiUri');
            $table->string('artist')->nullable();
            $table->boolean('booster');
            $table->string('borderColor');
            $table->string('cardBackId');
            $table->text('collectorNumber');
            $table->integer('hasContentWarning')->nullable();
            $table->integer('isOnlineOnly');
            $table->string('frameVersion');
            $table->boolean('isFullArt');
            $table->boolean('isHighresImage');
            $table->string('illustrationId')->nullable();
            $table->string('imageStatus');
            $table->string('printedName')->nullable();
            $table->string('printedText')->nullable();
            $table->string('printedTypeLine')->nullable();
            $table->integer('isPromo');
            $table->string('rarity');
            $table->date('releaseDate');
            $table->integer('isReprint');
            $table->string('scryfallSetId');
            $table->string('scryfallSetUri');
            $table->unsignedBigInteger('set_id')->index();
            $table->integer('isStorySpotlight');
            $table->integer('isTextless');
            $table->integer('isVariation');
            $table->string('variationOf')->nullable();
            $table->text('watermark')->nullable();
            $table->float('convertedManaCost', 10, 2);
            $table->integer('edhrecRank')->nullable();
            $table->integer('hasFoil');
            $table->integer('hasNonFoil');
            $table->string('layout');
            $table->string('handModifier')->nullable();
            $table->string('lifeModifier')->nullable();
            $table->string('loyalty')->nullable();
            $table->string('manaCost')->nullable();
            $table->string('oracleText')->nullable();
            $table->integer('isOversized')->nullable();
            $table->string('power')->nullable();
            $table->integer('isReserved')->nullable();
            $table->string('toughness')->nullable();
            $table->string('typeLine');
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
