<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupertypeablesTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supertypeables');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supertypeables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supertype_id');
            $table->unsignedBigInteger('supertypeable_id');
            $table->string('supertypeable_type');
            $table->timestamps();
        });
    }
}
