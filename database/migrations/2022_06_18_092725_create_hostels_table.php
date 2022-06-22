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
        Schema::create('hostels', function (Blueprint $table) {
            $table->id();
            $table->string('hostelName');
            $table->integer('roomNumber');
            $table->string('roomType');
            $table->integer('numOfBed');
            $table->integer('costPerBed');
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
        Schema::dropIfExists('hostels');
    }
};
