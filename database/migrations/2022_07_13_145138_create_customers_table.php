<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('name');
            $table->string('email');
            $table->integer('gender');
            $table->integer('age');
            $table->date('date_of_birth');
            $table->string('phone')->nullable();
            $table->text('profile_picture')->nullable();
            $table->string('address');
            $table->string('image')->nullable();
            $table->string('company_name')->nullable();
            $table->string('position')->nullable();
            $table->string('company_address', 5000)->nullable();
            $table->string('skill')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
