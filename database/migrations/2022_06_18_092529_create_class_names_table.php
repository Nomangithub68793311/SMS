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
        Schema::create('class_names', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_name');
            $table->string('gender');
            $table->string('class');
            $table->integer('id_no');
            $table->integer('phone');
            $table->string('subject');
            $table->string('section');
            $table->string('email')->nullable();
            $table->date("date")->nullable(); 
            $table->time('time')->default('00:00:00');
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
        Schema::dropIfExists('class_names');
    }
};
