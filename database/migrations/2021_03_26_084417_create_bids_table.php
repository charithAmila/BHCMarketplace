<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('owner');
            $table->string('bidding_address');
            $table->string('contract_address');
            $table->integer('token_id');
            $table->string('bidding_token');
            $table->decimal('bidding_amount',10,8);
            $table->string('signature');
            $table->string('message');
            $table->string('salt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bids');
    }
}
