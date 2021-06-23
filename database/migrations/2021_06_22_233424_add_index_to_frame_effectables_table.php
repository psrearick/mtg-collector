<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToFrameEffectablesTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('frame_effectables', function (Blueprint $table) {
            $table->dropIndex('frame_effectable_id');
        });
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('frame_effectables', function (Blueprint $table) {
            $table->index('frame_effectable_id');
        });
    }
}
