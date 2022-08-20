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
        Schema::create('student_leaves', function (Blueprint $table) {
            $table->uuid('id')->primary();           
            $table->string('leave_name');
            $table->string('name');
            $table->string('class');
            $table->integer('section');
            $table->string('email');
            $table->string('approved')->nullable(); 

            $table->string('reason');
            $table->integer('total_days');
            $table->date("start_date")->nullable(); 
            $table->date("finish_date")->nullable(); 
            $table->timestamps();
            $table->uuid('school_id')->nullable(); 
            $table->foreign('school_id')
            ->references('id')
            ->on('schools')
            ->onDelete('cascade');
            $table->uuid('student_id')->nullable(); 
            $table->foreign('student_id')
            ->references('id')
            ->on('students')
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
        Schema::dropIfExists('student_leaves');
    }
};
