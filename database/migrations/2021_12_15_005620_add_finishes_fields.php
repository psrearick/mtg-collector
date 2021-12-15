<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinishesFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('finish')->default('nonfoil')->after('quantity')->nullable();
        });

        Schema::table('card_collections', function (Blueprint $table) {
            $table->string('finish')->default('nonfoil')->after('quantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('finish');
        });

        Schema::table('card_collections', function (Blueprint $table) {
            $table->dropColumn('finish');
        });
    }
}
