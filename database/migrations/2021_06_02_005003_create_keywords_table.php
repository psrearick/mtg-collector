<?php

use App\Domain\CardAttributes\Models\Keyword;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeywordsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keywords');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('type')->nullable();
            $table->timestamps();
        });

        $keywordAbilities = Http::get('https://api.scryfall.com/catalog/keyword-abilities')->json()['data'];
        $keywordActions   = Http::get('https://api.scryfall.com/catalog/keyword-actions')->json()['data'];
        $abilityWords     = Http::get('https://api.scryfall.com/catalog/ability-words')->json()['data'];

        foreach ($keywordAbilities as $ability) {
            Keyword::create([
                'name' => $ability,
                'type' => 'ability',
            ]);
        }

        foreach ($keywordActions as $action) {
            Keyword::create([
                'name' => $action,
                'type' => 'action',
            ]);
        }

        foreach ($abilityWords as $word) {
            Keyword::create([
                'name' => $word,
                'type' => 'ability word',
            ]);
        }
    }
}
