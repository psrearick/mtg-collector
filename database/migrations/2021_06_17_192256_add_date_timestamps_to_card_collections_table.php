<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateTimestampsToCardCollectionsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_collections', function (Blueprint $table) {
            //
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
            $table->timestamp('created_at')->useCurrent()->change();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->change();
        });
    }
}
