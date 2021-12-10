<?php

use App\Domain\CardAttributes\Models\FrameEffect;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrameEffectsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('frame_efects');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frame_effects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        $frameEffects = [
            'legendary'              => 'The cards have a legendary crown',
            'miracle'                => 'The miracle frame effect',
            'nyxtouched'             => 'The Nyx-touched frame effect',
            'draft'                  => 'The draft-matters frame effect',
            'devoid'                 => 'The Devoid frame effect',
            'tombstone'              => 'The Odyssey tombstone mark',
            'colorshifted'           => 'A colorshifted frame',
            'inverted'               => 'The FNM-style inverted frame',
            'sunmoondfca'            => 'The sun and moon transform marks',
            'compasslanddfc'         => 'The compass and land transform marks',
            'originpwdfc'            => 'The Origins and planeswalker transform marks',
            'mooneldrazidfc'         => 'The moon and Eldrazi transform marks',
            'waxingandwaningmoondfc' => 'The waxing and waning crescent moon transform marks',
            'showcase'               => 'A custom Showcase frame',
            'extendedart'            => 'An extended art frame',
            'companion'              => 'The cards have a companion frame',
            'etched'                 => 'The cards have an etched foil treatment',
            'snow'                   => 'The cards have the snowy frame effect',
        ];

        foreach ($frameEffects as $frameEffect => $description) {
            FrameEffect::create([
                'name'          => $frameEffect,
                'description'   => $description,
            ]);
        }
    }
}
