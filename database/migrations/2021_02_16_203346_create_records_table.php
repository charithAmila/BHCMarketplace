<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nft_id');
            $table->unsignedBigInteger('owner_id');
            $table->integer('copies');
            $table->integer('copies_left');
            $table->boolean('selling');
            $table->boolean('bidding');
            $table->timestamps();

            $table->foreign('nft_id')->references('id')->on('collectibles');
            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('records');
    }
}
