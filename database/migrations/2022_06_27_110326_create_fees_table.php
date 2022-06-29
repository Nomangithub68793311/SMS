<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees', function (Blueprint $table) {
         
            $table->uuid('id')->primary();
            $table->string('class');
            $table->string('section');
            $table->string('fee_name');
            $table->integer('fee_amount');
            $table->string('fee_type');
            $table->date("starts_from")->nullable(); 
            $table->date("finishes_at")->nullable(); 
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
        Schema::dropIfExists('fees');
    }
};
