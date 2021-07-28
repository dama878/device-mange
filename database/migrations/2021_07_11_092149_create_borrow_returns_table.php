<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrow_returns', function (Blueprint $table) {
            $table->integer('BORROWER_ID')->unsigned();
            $table->increments('BORETURN_ID');
            $table->date('Date');
            $table->boolean('IsDeleted');
            $table->dateTime('CreatedDate');
            $table->integer('CreatedBy')->nullable();
            $table->dateTime('UpdatedDate')->nullable();
            $table->integer('UpdatedBy')->nullable();
            $table->foreign('BORROWER_ID')->references('BORROWER_ID')->on('borrowers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('borrow_returns');
    }
}