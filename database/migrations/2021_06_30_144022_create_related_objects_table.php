<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelatedObjectsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('related_objects');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('related_objects', function (Blueprint $table) {
            $table->id();
            $table->string('object_id');
            $table->string('component');
            $table->string('name');
            $table->string('type');
            $table->string('uri');
            $table->timestamps();
        });
    }
}
