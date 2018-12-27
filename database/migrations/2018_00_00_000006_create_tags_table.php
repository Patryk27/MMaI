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
            $table->unsignedInteger('website_id');
            $table->string('name');
            $table->timestamps();

            // -- foreign keys -- //

            $table->foreign('website_id')
                ->references('id')
                ->on('websites');

            // -- indexes -- //

            $table->unique(['website_id', 'name']);
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
