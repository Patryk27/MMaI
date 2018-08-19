<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{

    /**
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_id');
            $table->unsignedInteger('position');
            $table->text('url');
            $table->char('title', 64);
            $table->timestamps();

            // -- indexes -- //

            $table->index('language_id');
            $table->unique(['language_id', 'position']);
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }

}
