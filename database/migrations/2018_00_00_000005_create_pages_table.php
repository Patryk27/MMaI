<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration {

    /**
     * @return void
     */
    public function up() {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('website_id');
            $table->text('title');
            $table->text('lead')->nullable();
            $table->longText('content');
            $table->mediumText('notes')->nullable();
            $table->string('type');
            $table->string('status');
            $table->timestamps();
            $table->dateTime('published_at')->nullable();

            // -- foreign keys -- //

            $table->foreign('website_id')
                ->references('id')
                ->on('websites');

            // -- indexes -- //

            $table->index('type');
            $table->index('status');

            $table->index(['website_id', 'type']);
            $table->index(['website_id', 'status']);
            $table->index(['website_id', 'type', 'status']);
        });
    }

    /**
     * @return void
     */
    public function down() {
        Schema::dropIfExists('pages');
    }
}
