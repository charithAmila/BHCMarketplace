<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->default('');
            $table->string('message')->default('');
            $table->float('buy_amount')->default(0);
            $table->float('sell_amount')->default(0);
            $table->float('bid_amount')->default(0);
            $table->string('currency')->default('');
            $table->string('owner')->default('');
            $table->string('contract')->default('');
            $table->integer('token_id')->default(0);
            $table->boolean('status')->default(1);
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
        //
    }
}