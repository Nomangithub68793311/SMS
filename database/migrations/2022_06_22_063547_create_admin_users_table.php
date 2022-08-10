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
            $table->string('user_name');  
           
            $table->date('joining_date')->nullable();
            $table->string('email'); 
            $table->string('role')->default('admin'); 
            $table->integer('phone');    
            $table->string('address');
            $table->string('hashedPassword');
            $table->string('password');
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
        Schema::dropIfExists('admin_users');
    }
};
