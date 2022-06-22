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
            $table->string('teacherName');
            $table->string('gender');
            $table->string('class');
            $table->string('subject');
            $table->string('section');
            $table->string('email');
            $table->string('photo');
            $table->date("date")->nullable(); 
            $table->date("time")->nullable(); 
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
