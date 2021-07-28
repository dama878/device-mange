<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('USE_ID');
            $table->integer('ROLE_ID')->unsigned();
            $table->string('username')->unique();
            $table->string('FirstName')->nullable();
            $table->string('LastName')->nullable();
            $table->String('Address')->nullable();
            $table->String('Phone')->nullable();
            $table->string('Email')->unique();
            $table->string('password');
            $table->tinyInteger('Gender')->nullable();
            $table->tinyInteger('Status')->nullable();
            $table->date('DOB')->nullable();
            $table->date('Date')->nullable();
            $table->boolean('IsDeleted');
            $table->dateTime('CreatedDate');
            $table->integer('CreatedBy')->nullable();
            $table->dateTime('UpdatedDate')->nullable();
            $table->integer('UpdatedBy')->nullable();
            $table-> foreign('ROLE_ID')->references('ROLE_ID')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
