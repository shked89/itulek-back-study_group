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
            $table->unsignedBigInteger('qualification_id');        
            // Устанавливаем составной первичный ключ
            $table->primary(['study_group_id', 'qualification_id']);
        
            // Определение внешних ключей, если необходимо
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
        Schema::dropIfExists('group.ref_study_group_to_qualifications');
    }
};
