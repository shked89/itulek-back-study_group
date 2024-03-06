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
        Schema::create('group.study_group_info', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('language_iso');
            $table->unsignedBigInteger('study_group_id');
            $table->foreign('study_group_id')->references('id')->on('group.studies_groups')->onDelete('cascade');
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
        Schema::dropIfExists('group.study_group_info');
    }
};
