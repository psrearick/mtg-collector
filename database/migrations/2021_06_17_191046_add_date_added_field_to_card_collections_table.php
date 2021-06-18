<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateAddedFieldToCardCollectionsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_collections', function (Blueprint $table) {
            $table->dropColumn('date_added');
        });
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_collections', function (Blueprint $table) {
            $table->timestamp('date_added')->nullable();
        });
    }
}
