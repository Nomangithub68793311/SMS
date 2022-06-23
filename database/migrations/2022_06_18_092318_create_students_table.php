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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->date('date_of_birth')->nullable();
            $table->integer('roll');
            $table->string('blood_group');
            $table->string('religion');
            $table->string('email')->unique();
            $table->string('class');
            $table->string('section');
            $table->string('car');
            $table->integer('admission_id');
            $table->integer('phone');
            // $table->string('photo');
            $table->string('address');
            $table->string('hashedPassword');
            $table->string('password');
            $table->string('bio'); 
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
