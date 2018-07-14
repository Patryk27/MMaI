<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditItemsTable extends Migration
{

    /**
     * @return void
     */
    public function up()
    {
        Schema::create('audit_items', function (Blueprint $table) {
            $table->increments('id');
            $table->char('ip', 45)->nullable(); // as defined by the INET6_ADDRSTRLEN header
            $table->text('url')->nullable();
            $table->char('type', 32);
            $table->jsonb('data');
            $table->text('message');
            $table->timestamps();

            // -- indexes -- //

            $table->index('ip');
            $table->index('type');
            $table->index(['ip', 'type']);
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_items');
    }

}
