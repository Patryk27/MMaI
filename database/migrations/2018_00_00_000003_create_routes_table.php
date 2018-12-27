<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->increments('id');
            $table->char('subdomain', 16);
            $table->char('url', 128);
            $table->morphs('model');
            $table->timestamps();

            // -- indexes -- //

            $table->index('subdomain');
            $table->unique(['subdomain', 'url']);
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
