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
        Schema::create('event_stream', function (Blueprint $table) {
            // UUID4 of the event
            $table->char('event_id', 36);
            // Version of the aggregate after event was recorded
            $table->integer('version', false, true);
            // Name of the event
            $table->string('event_name', 100);
            // Event payload
            $table->text('payload');
            // DateTime ISO8601 + microseconds UTC stored as a string e.g. 2016-02-02T11:45:39.000000
            $table->char('created_at', 26);
            // UUID4 of linked aggregate
            $table->char('aggregate_id', 36);
            // Class of the linked aggregate
            $table->string('aggregate_type', 150);
            $table->primary('event_id');
            // Concurrency check on database level
            $table->unique(['aggregate_id', 'aggregate_type', 'version'], 'event_stream_m_v_uix');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_stream');
    }
}
