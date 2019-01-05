<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageTagTable extends Migration {
    /**
     * @return void
     */
    public function up() {
        Schema::create('page_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('page_id');
            $table->unsignedInteger('tag_id');
            $table->timestamps();

            // -- foreign keys -- //

            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onDelete('cascade');

            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                ->onDelete('cascade');

            // -- indexes -- //

            $table->unique(['page_id', 'tag_id']);
        });
    }

    /**
     * @return void
     */
    public function down() {
        Schema::dropIfExists('page_tag');
    }
}
