<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CustomerDeletionToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'branch_code',
        'token',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    /**
     * トークンが有効かチェック
     */
    public function isValid(): bool
    {
        return !$this->used && $this->expires_at->isFuture();
    }

    /**
     * トークンを使用済みにする
     */
    public function markAsUsed(): void
    {
        $this->used = true;
        $this->save();
    }

    /**
     * ランダムな6桁のトークンを生成
     */
    public static function generateToken(): string
    {
        return str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * 新しい削除トークンを作成
     */
    public static function createToken(string $customerCode, ?string $branchCode = null): self
    {
        return self::create([
            'customer_code' => $customerCode,
            'branch_code' => $branchCode,
            'token' => self::generateToken(),
            'expires_at' => Carbon::now()->addMinutes(5),
            'used' => false,
        ]);
    }
}
