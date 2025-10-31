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
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('alert_title', 255)->nullable()->comment('アラートタイトル');
                $table->text('alert_message')->nullable()->comment('アラートメッセージ');
            });
        } else {
            // テーブルが既に存在する場合、不足しているカラムを追加
            Schema::table('settings', function (Blueprint $table) {
                if (!Schema::hasColumn('settings', 'alert_title')) {
                    $table->string('alert_title', 255)->nullable()->comment('アラートタイトル');
                }
                if (!Schema::hasColumn('settings', 'alert_message')) {
                    $table->text('alert_message')->nullable()->comment('アラートメッセージ');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
