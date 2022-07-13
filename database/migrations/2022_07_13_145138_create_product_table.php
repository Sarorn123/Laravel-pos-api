<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->integer('usd')->default(0);
            $table->integer('khr')->default(0);
            $table->integer('stock')->default(0);
            $table->unsignedInteger('stock_usd')->default(0);
            $table->integer('total_stock')->default(0);
            $table->date('date_in_stock');
            $table->date('date_out_stock')->nullable();
            $table->text('description')->nullable();
            $table->integer('category_id');
            $table->date('created_at');
            $table->date('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}
