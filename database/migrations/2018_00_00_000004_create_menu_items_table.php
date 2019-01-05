<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration {
    /**
     * @return void
     */
    public function up() {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('website_id');
            $table->unsignedInteger('position');
            $table->text('url');
            $table->string('title');
            $table->timestamps();

            // -- foreign keys -- //

            $table->foreign('website_id')
                ->references('id')
                ->on('websites');

            // -- indexes -- //

            $table->unique(['website_id', 'position']);
        });
    }

    /**
     * @return void
     */
    public function down() {
        Schema::dropIfExists('menu_items');
    }
}
