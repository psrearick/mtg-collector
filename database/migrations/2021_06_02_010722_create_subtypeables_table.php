<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubtypeablesTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subtypeables');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subtypeables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subtype_id');
            $table->unsignedBigInteger('subtypeable_id')->index();
            $table->string('subtypeable_type');
            $table->timestamps();
        });
    }
}
