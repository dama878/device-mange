<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exports', function (Blueprint $table) {
            $table->increments('EXP_ID');
            $table->string('Invoice');
            $table->date('Date');
            $table->integer('CUS_ID')->unsigned();
            $table->string('Depot')->nullable();
            $table->string('Place')->nullable();
            $table->tinyInteger('Export')->nullable();
            $table->integer('CreatedBy')->nullable();
            $table->dateTime('UpdatedDate')->nullable();
            $table->integer('UpdatedBy')->nullable();
            $table->boolean('IsDeleted');

            $table->foreign('CUS_ID')->references('CUS_ID')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exports');
    }
}
