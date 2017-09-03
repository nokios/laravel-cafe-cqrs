<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventStreamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Prooph\Package\Migration\Schema\EventStoreSchema::createSingleStream('event_stream', true);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Prooph\Package\Migration\Schema\EventStoreSchema::dropStream('event_stream');
    }
}
