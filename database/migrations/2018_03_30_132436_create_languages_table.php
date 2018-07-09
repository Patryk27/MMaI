<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable
    extends Migration {

    /**
     * @return void
     */
    public function up() {
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('iso_name');
            $table->string('english_name');
            $table->string('translated_name');
            $table->timestamps();

            // -- indexes -- //

            $table->unique('slug');
            $table->unique('iso_name');
            $table->unique('english_name');
            $table->unique('translated_name');
        });
    }

    /**
     * @return void
     */
    public function down() {
        Schema::dropIfExists('languages');
    }

}
