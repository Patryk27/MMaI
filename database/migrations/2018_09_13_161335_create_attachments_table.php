<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('page_id')->nullable();
            $table->string('name');
            $table->string('mime');
            $table->unsignedInteger('size');
            $table->string('path');
            $table->timestamps();

            // -- foreign keys -- //

            $table->foreign('page_id')
                ->references('id')
                ->on('pages');

            // -- indexes -- //

            $table->index('name');
            $table->index('mime');
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
}
