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
        Schema::create('group.students', function (Blueprint $table) {
        
            $table->unsignedBigInteger('person_unit_id')->primary(); 
            $table->unsignedBigInteger('college_id');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group.students', function (Blueprint $table) {
            //
        });
    }
};
