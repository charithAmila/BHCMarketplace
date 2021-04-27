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
            $table->decimal('buy_amount', 10,8)->default(0);
            $table->decimal('sell_amount',10,8)->default(0);
            $table->decimal('bid_amount',10,8)->default(0);
            $table->string('currency')->default('');
            $table->string('owner')->default('');
            $table->string('contract')->default('');
            $table->integer('token_id')->default(0);
            $table->boolean('status')->default(1);
            $table->string('type')->default('');
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