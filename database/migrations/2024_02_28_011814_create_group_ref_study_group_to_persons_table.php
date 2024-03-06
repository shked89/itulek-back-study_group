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
        Schema::create('group.ref_study_group_to_persons', function (Blueprint $table) {
            $table->unsignedBigInteger('person_unit_id');
            $table->unsignedBigInteger('study_group_id');
            
            $table->primary(['person_unit_id', 'study_group_id']);
            
            $table->foreign('person_unit_id')->references('person_unit_id')->on('group.students')->onDelete('cascade');
            $table->foreign('study_group_id')->references('id')->on('group.studies_groups')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group.ref_study_group_to_persons');
    }
};
