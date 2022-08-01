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
        Schema::create('schools', function (Blueprint $table) {
            $table->uuid('id')->primary();   
            $table->string('institution_name');
            $table->string('address');
            $table->string('city');
            $table->integer('zip_code');  
            $table->string('institution_type');
            $table->string('institution_medium');
            $table->string('country');
            $table->string('category');
            $table->string('website')->nullable(); 
            $table->string('phone_no')->unique();
            $table->string('mobile_no')->unique();
            $table->string('principal_phone_no')->unique();


            $table->date('establishment_year')->nullable(); 
            $table->string('principal_name');
            $table->date('payment_date')->nullable(); 
            $table->boolean('payment_status')->default(false); ;


            $table->string('institution_email')->unique(); 
            $table->string('principal_email')->unique(); 

            $table->boolean('login_permitted')->default(false); 
            $table->integer('total_students');  

            $table->string('hashedPassword')->nullable(); 
            $table->string('password')->nullable(); 
            $table->string('logo');
            $table->string('license_copy');

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
        Schema::dropIfExists('admin_signups');
    }
};
