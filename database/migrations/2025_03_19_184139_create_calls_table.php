<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->string('sid')->unique()->comment('Twilio Call SID');
            $table->string('from')->comment('Caller phone number');
            $table->string('to')->comment('Recipient phone number');
            $table->string('status')->comment('Call status');
            $table->string('direction')->comment('inbound or outbound');
            $table->text('message')->nullable()->comment('TTS message for outbound calls');
            $table->string('recording_url')->nullable()->comment('URL to call recording if any');
            $table->integer('duration')->nullable()->comment('Call duration in seconds');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
