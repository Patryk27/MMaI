<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{

    /**
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_id');
            $table->char('name', 64);
            $table->timestamps();

            // -- foreign keys -- //

            $table->foreign('language_id')
                ->references('id')
                ->on('languages');

            // -- indexes -- //

            $table->unique(['language_id', 'name']);
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }

}
