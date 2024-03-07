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
        Schema::create('group.studies_groups', function (Blueprint $table) {
            $table->id();
            $table->string('start_year');
            $table->bigInteger('college_id')->unsigned()->nullable();
            $table->bigInteger('adviser_id')->unsigned()->nullable();
            $table->bigInteger('department_id')->unsigned()->nullable();
            $table->bigInteger('speciality_id')->unsigned()->nullable();
            $table->bigInteger('edu_base_id')->unsigned()->nullable();
            $table->foreign('edu_base_id')->references('id')->on('group.edu_bases')->onDelete('cascade');
      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group.studies_groups');
    }
};
