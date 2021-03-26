<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiddingTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bidding_tokens', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('token_type');
            $table->integer('collection_type');
            $table->integer('collection_id');
            $table->integer('token_id');
            $table->boolean('bidding_status');
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bidding_tokens');
    }
}
