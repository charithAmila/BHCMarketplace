<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("collection")->nullable(false);
            $table->string("current_owner")->nullable(false);
            $table->integer("token_id")->nullable(false);
            $table->integer("signed_to")->nullable(false);
            $table->decimal("price",10,8)->nullable(false);
            $table->boolean('is_instant')->default(false);
            $table->string("currency")->default("0x0000000000000000000000000000000000000000");
            $table->string("signature")->nullable(false);
            $table->string("salt")->nullable(false);
            $table->integer("sold")->default(0);
            $table->string("col_name")->nullable(false);
            $table->string("owner_name")->nullable(false);
            $table->string("nft_name")->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
