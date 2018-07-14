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
            $table->char('login', 32);
            $table->char('name', 128);
            $table->char('password', 60);
            $table->char('remember_token', 100)->nullable();
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
