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
        Schema::create('group.ref_study_group_to_qualifications', function (Blueprint $table) {

            $table->unsignedBigInteger('study_group_id');
            $table->primary('study_group_id'); 
            $table->foreign('study_group_id')->references('id')->on('group.studies_groups')->onDelete('cascade');
            $table->bigInteger('qualification_id')->unsigned()->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group.ref_study_group_to_qualifications');
    }
};
