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
            $table->uuid('id')->primary();         
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
            $table->integer('admission_id');
            $table->string('role')->default('student'); 

            $table->string('testimonial');
            $table->string('certificate');    
            $table->string('signature');
            $table->string('marksheet');
            $table->string('photo');

            $table->integer('phone');
            $table->date('admitted_year');
            $table->string('address');
            $table->string('hashedPassword');
            $table->string('password');
            $table->string('bio'); 
            // $table->json('votes_detail');
           


            $table->timestamps();
            $table->uuid('school_id')->nullable(); 
            $table->foreign('school_id')
            ->references('id')
            ->on('schools')
            ->onDelete('cascade');

            $table->uuid('parentmodel_id')->nullable(); 
            $table->foreign('parentmodel_id')
            ->references('id')
            ->on('parentmodels')
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
        Schema::dropIfExists('students');
    }
};
