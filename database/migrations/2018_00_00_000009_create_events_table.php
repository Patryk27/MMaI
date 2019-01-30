<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration {

    /**
     * @return void
     */
    public function up() {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->jsonb('payload');
            $table->timestamps();

            // -- indexes -- //

            $table->index('type');
        });
    }

    /**
     * @return void
     */
    public function down() {
        Schema::dropIfExists('events');
    }
}
