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
        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id')->primary();           
             $table->string('exam_name');
            $table->date('select_date')->nullable();
            $table->string('subject_type');
            $table->string('select_class');
            $table->string("select_section"); 
            $table->time('select_time')->default('00:00:00');
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
        Schema::dropIfExists('exams');
    }
};
