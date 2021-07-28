<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowDetailModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrow_detail_models', function (Blueprint $table) {
            $table->increments('BORDE_ID');
            $table->integer('MOD_ID')->unsigned();
            $table->integer('BOR_ID')->unsigned();
            $table->integer('BORETURN_ID')->unsigned();
            $table->dateTime('DueDateReturn')->nullable();
            $table->dateTime('DateReturn')->nullable();
            $table->boolean('IsRenew')->nullable();
            $table->boolean('IsDeleted')->default(0);
            $table->dateTime('CreatedDate')->nullable();
            $table->integer('CreatedBy')->nullable();
            $table->dateTime('UpdatedDate')->nullable();
            $table->integer('UpdatedBy')->nullable();

            $table->foreign('MOD_ID')->references('MOD_ID')->on('models')->onDelete('cascade');
            $table->foreign('BOR_ID')->references('BOR_ID')->on('borrows')->onDelete('cascade');
            $table->foreign('BORETURN_ID')->references('BORETURN_ID')->on('borrow_returns')->onDelete('cascade');
        });
    }

    /**
     *
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('borrow_detail_models');
    }
}
