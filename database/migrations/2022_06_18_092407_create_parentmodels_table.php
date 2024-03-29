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
        Schema::create('parentmodels', function (Blueprint $table) {
            $table->uuid('id')->primary();           
             $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->date('date_of_birth')->nullable(); 
            $table->string('occupation');
            $table->string('student_email');
            $table->string('blood_group');
            $table->string('religion');
            $table->string('email');
           
            $table->integer('phone');
            $table->string('address');
            $table->string('bio');
            $table->string('hashedPassword');
            $table->string('password');

            $table->string('role')->default('parent'); 


            $table->timestamps();
            $table->uuid('school_id')->nullable(); 
            $table->foreign('school_id')
            ->references('id')
            ->on('schools')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parentmodels');
    }
};
