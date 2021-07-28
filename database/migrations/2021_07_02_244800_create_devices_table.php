<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->increments('DEV_ID');
            $table->integer('TYPE_ID')->unsigned();
            $table->integer('MAN_ID')->unsigned();
            $table->string('DevName')->nullable();
            $table->longText('Description')->nullable();
            $table->string('KeyWord')->nullable();
            $table->tinyInteger('Status')->nullable();
            $table->string('SerialNumber')->nullable();
            $table->string('Detail')->nullable();
            $table->date('GuaranteeStart')->nullable();
            $table->date('GuaranteeEnd')->nullable();
            $table->string('Img')->nullable();
            $table->integer('DisplayOrder');
            $table->boolean('IsPublished');
            $table->boolean('IsDeleted');
            $table->dateTime('CreatedDate');
            $table->integer('CreatedBy')->nullable();
            $table->dateTime('UpdatedDate')->nullable();
            $table->integer('UpdatedBy')->nullable();
            $table-> foreign('TYPE_ID')->references('TYPE_ID')->on('types')->onDelete('cascade');
            $table-> foreign('MAN_ID')->references('MAN_ID')->on('manufacturers')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
