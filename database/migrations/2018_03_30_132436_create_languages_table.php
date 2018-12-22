<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('iso_639_code');
            $table->string('iso_3166_code');
            $table->string('english_name');
            $table->string('native_name');
            $table->timestamps();

            // -- indexes -- //

            $table->unique('slug');
            $table->unique('iso_639_code');
            $table->unique('iso_3166_code');
            $table->unique('english_name');
            $table->unique('native_name');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
