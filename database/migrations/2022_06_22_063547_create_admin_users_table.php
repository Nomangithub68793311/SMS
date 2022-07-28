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
        Schema::create('admin_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');  
            $table->string('fathers_name');
            $table->string('mothers_name');
            $table->date('joining_date')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('email'); 
            $table->integer('phone');    
            $table->string('address');
            $table->string('religion');
            $table->string('hashedPassword');
            $table->string('password');
            $table->integer('id_no');     
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
        Schema::dropIfExists('admin_users');
    }
};
