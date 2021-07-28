<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_details', function (Blueprint $table) {
            $table->increments('IMPDE_ID');
            $table->integer('IMP_ID')->unsigned();
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

            $table->foreign('IMP_ID')->references('IMP_ID')->on('imports')->onDelete('cascade');
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
        Schema::dropIfExists('import_details');
    }
}
