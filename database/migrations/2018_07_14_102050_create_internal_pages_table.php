<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalPagesTable extends Migration
{

    /**
     * @return void
     */
    public function up()
    {
        Schema::create('internal_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->char('type', 32);
            $table->timestamps();

            // -- indexes -- //

            $table->unique('type');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internal_pages');
    }

}
