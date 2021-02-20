<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->unsignedBigInteger('record_id');
            $table->unsignedBigInteger('nft_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('quantity')->nullable();
            $table->double('price', 10, 2)->nullable();
            $table->string('currency')->nullable();
            $table->boolean('sold')->nullable();
            $table->timestamps();

            $table->foreign('record_id')->references('id')->on('records');
            $table->foreign('nft_id')->references('id')->on('collectibles');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
