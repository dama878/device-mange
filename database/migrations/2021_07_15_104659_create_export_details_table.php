<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_details', function (Blueprint $table) {
            $table->increments('EXPDE_ID');
            $table->integer('EXP_ID')->unsigned();
            $table->integer('MOD_ID')->unsigned()->nullable();
            $table->string('Unit')->nullable();
            $table->smallInteger('Type')->nullable();
            $table->integer('Quantity')->nullable();
            $table->double('Price')->nullable();
            $table->string('Note')->nullable();
            $table->integer('CreatedBy')->nullable();
            $table->dateTime('UpdatedDate')->nullable();
            $table->integer('UpdatedBy')->nullable();
            $table->boolean('IsDeleted');

            $table->foreign('EXP_ID')->references('EXP_ID')->on('exports')->onDelete('cascade');
            $table->foreign('MOD_ID')->references('MOD_ID')->on('models')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('export_details');
    }
}
