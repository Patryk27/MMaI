<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageVariantTagTable
    extends Migration {

    /**
     * @return void
     */
    public function up() {
        Schema::create('page_variant_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('page_variant_id');
            $table->unsignedInteger('tag_id');
            $table->timestamps();

            // -- foreign keys -- //

            $table->foreign('page_variant_id')->references('id')->on('page_variants');
            $table->foreign('tag_id')->references('id')->on('tags');

            // -- indexes -- //

            $table->unique(['page_variant_id', 'tag_id']);
        });
    }

    /**
     * @return void
     */
    public function down() {
        Schema::dropIfExists('page_variant_tag');
    }
}
