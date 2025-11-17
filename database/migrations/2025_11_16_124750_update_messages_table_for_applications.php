<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['receiver_id']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['sender_id', 'receiver_id']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('sender_id')->nullable()->after('id')->constrained('admins')->onDelete('cascade');
            $table->foreignId('receiver_id')->nullable()->after('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('application_id')->nullable()->after('receiver_id')->constrained('applications')->onDelete('cascade');
            $table->string('type')->default('application')->after('content'); // application, interview, rejection, acceptance
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['receiver_id']);
            $table->dropForeign(['application_id']);
            $table->dropColumn(['receiver_id', 'application_id', 'type', 'is_read']);
            $table->foreignId('receiver_id')->constrained('admins')->onDelete('cascade');
            $table->timestamp('sent_at')->nullable();
        });
    }
};
