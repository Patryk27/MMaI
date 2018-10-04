<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{

    /**
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('login');
            $table->string('name');
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->timestamps();

            // -- indexes -- //

            $table->unique('login');
            $table->unique('name');
            $table->unique('remember_token');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }

}
