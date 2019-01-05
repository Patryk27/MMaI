<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsitesTable extends Migration {
    /**
     * @return void
     */
    public function up() {
        Schema::create('websites', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_id');
            $table->string('slug');
            $table->string('name');
            $table->string('description');
            $table->timestamps();

            // -- foreign keys -- //

            $table->foreign('language_id')
                ->references('id')
                ->on('languages');

            // -- indexes -- //

            $table->unique('slug');
            $table->unique('name');
        });
    }

    /**
     * @return void
     */
    public function down() {
        Schema::dropIfExists('websites');
    }
}
