<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrowers', function (Blueprint $table) {
            $table->integer('BOGROUP_ID')->unsigned();
            $table->increments('BORROWER_ID');
            $table->string('FirstName');
            $table->string('LastName');
            $table->string('Phone');
            $table->string('Email');
            $table->string('Image')->nullable();
            $table->string('Note');
            // $table->smallInteger('Active');
            $table->boolean('IsDeleted');
            $table->dateTime('CreatedDate');
            $table->integer('CreatedBy')->nullable();
            $table->dateTime('UpdatedDate')->nullable();
            $table->integer('UpdatedBy')->nullable();
            $table->foreign('BOGROUP_ID')->references('BOGROUP_ID')->on('borrower_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('borrowers');
    }
}