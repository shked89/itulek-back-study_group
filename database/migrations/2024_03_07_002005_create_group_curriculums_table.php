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
        Schema::create('group.curriculums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('year');
            $table->bigInteger('college_id');
            $table->boolean('status_delete');
            $table->string('file_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group.curriculums', function (Blueprint $table) {
            //
        });
    }
};
