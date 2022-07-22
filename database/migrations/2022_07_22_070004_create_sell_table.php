<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('product_id');
            $table->integer('customer_id');
            $table->date('date');
            $table->string('month_year');
            $table->string('year');
            $table->integer('quantity');
            $table->integer('usd');
            $table->integer('khr');
            $table->integer('status')->default(0);
            $table->string('description')->nullable();
            $table->date('created_at')->nullable();
            $table->date('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sell');
    }
}
