<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToSupertypeablesTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supertypeables', function (Blueprint $table) {
            $table->dropIndex('supertypeable_id');
        });
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supertypeables', function (Blueprint $table) {
            $table->index('supertypeable_id');
        });
    }
}
