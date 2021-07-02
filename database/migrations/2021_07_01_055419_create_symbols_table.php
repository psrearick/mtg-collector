<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSymbolsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('symbols');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('symbols', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->string('svgUri')->nullable();
            $table->string('svgPath')->nullable();
            $table->string('looseVariant')->nullable();
            $table->string('english')->nullable();
            $table->boolean('transpose')->default(false)->nullable();
            $table->boolean('representsMana')->default(false)->nullable();
            $table->boolean('appearsInManaCosts')->default(false)->nullable();
            $table->string('cmc')->nullable();
            $table->boolean('funny')->default(false)->nullable();

            $table->timestamps();
        });
    }
}
