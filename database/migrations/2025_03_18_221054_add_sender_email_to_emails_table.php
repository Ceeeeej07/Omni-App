<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->string('sender_email')->nullable()->after('recipient_email'); // ✅ Add sender_email column
        });
    }

public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn('sender_email'); // ✅ Remove column if rollback
        });
    }

};
