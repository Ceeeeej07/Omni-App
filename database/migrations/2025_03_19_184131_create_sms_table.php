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
        Schema::create('sms', function (Blueprint $table) {
            $table->id();
            $table->string('sid')->unique()->comment('Twilio Message SID');
            $table->string('from')->comment('Sender phone number');
            $table->string('to')->comment('Recipient phone number');
            $table->text('body')->comment('Message content');
            $table->string('status')->comment('Message status');
            $table->string('direction')->comment('inbound or outbound');
            $table->json('media_urls')->nullable()->comment('URLs to media attachments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms');
    }
};
