<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectiblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collectibles', function (Blueprint $table) {
            $table->id();
            $table->string('nft');
            $table->boolean('aop');
            $table->string('aop_link')->nullable();
            $table->unsignedBigInteger('collection_id');
            $table->string('type');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->decimal('royalties');
            $table->integer('copies')->default(1);
            $table->text('properties')->nullable();
            $table->boolean('pos');
            $table->boolean('isp');
            $table->decimal('price')->nullable();
            $table->string('currency');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('legend_id')->nullable();

            $table->timestamps();

            //$table->foreign('collection_id')->references('id')->on('collections');
            //$table->foreign('category_id')->references('id')->on('categories');
            //$table->foreign('legend_id')->references('id')->on('legends');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collectibles');
    }
}
