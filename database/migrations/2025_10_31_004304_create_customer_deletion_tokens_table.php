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
        Schema::create('customer_deletion_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 50)->comment('顧客コード');
            $table->string('branch_code', 50)->nullable()->comment('支店コード');
            $table->string('token', 6)->comment('削除確認コード');
            $table->timestamp('expires_at')->comment('有効期限');
            $table->boolean('used')->default(false)->comment('使用済みフラグ');
            $table->timestamps();

            $table->index(['customer_code', 'branch_code']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_deletion_tokens');
    }
};
