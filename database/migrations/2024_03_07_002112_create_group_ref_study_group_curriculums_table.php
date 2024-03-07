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
        Schema::create('group.ref_study_group_curriculums', function (Blueprint $table) {
            $table->bigInteger('study_group_id');
            $table->bigInteger('curriculum_id');
            $table->primary(['study_group_id', 'curriculum_id']);
            $table->foreign('study_group_id')->references('id')->on('group.studies_groups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('curriculum_id')->references('id')->on('group.curriculums')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group.ref_study_group_curriculums', function (Blueprint $table) {
            //
        });
    }
};
