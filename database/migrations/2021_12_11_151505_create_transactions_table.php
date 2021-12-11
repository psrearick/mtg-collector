<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id');
            $table->foreignId('collection_id');
            $table->float('price')->nullable();
            $table->boolean('foil')->default(false);
            $table->string('condition')->nullable();
            $table->integer('quantity')->default(0);
            $table->timestamp('date_added');
            $table->softDeletes();
            $table->timestamps();
        });
    }
}
