<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageVariantsTable extends Migration
{

    /**
     * @return void
     */
    public function up()
    {
        Schema::create('page_variants', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('page_id');
            $table->unsignedInteger('language_id');
            $table->char('status', 16);
            $table->text('title');
            $table->text('lead')->nullable();
            $table->mediumText('content');
            $table->timestamps();
            $table->dateTime('published_at')->nullable();

            // -- foreign keys -- //

            $table->foreign('page_id')
                ->references('id')
                ->on('pages');

            $table->foreign('language_id')
                ->references('id')
                ->on('languages');

            // -- indexes -- //

            $table->unique(['page_id', 'language_id']);
            $table->index('status');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_variants');
    }

}
