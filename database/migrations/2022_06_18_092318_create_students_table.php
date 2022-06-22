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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('gender');
            $table->integer('roll');
            $table->string('bloodGroup');
            $table->string('religion');
            $table->string('class');
            $table->string('section');
            $table->integer('phone');
            $table->string('shortBio');
            $table->string('photo');
            $table->string('address');
            $table->date("birth_date")->nullable(); 
          


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
        Schema::dropIfExists('students');
    }
};
