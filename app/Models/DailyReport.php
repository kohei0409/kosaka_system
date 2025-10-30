<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

    protected $table = 'daily_reports';

    protected $fillable = [
        'user_id',
        'ManagerCode',
        'Course',
        'customer_code',
        'report_date',
        'content',
        'next_schedule',
        'attachments',
        'is_read',
        'read_by',
        'comments',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_read' => 'boolean',
        'comments' => 'array', // ✅ `comments` を配列としてキャスト
        'read_by' => 'array',
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_code', 'CustomerCode');
    }


    public function getCustomerNameAttribute()
    {
        return optional($this->customer)->CustomerName ?? '不明';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'ManagerCode', 'Code');
    }

// ✅ 担当者名を取得するアクセサ
    public function getManagerNameAttribute()
    {
        return optional($this->user)->name ?? '不明';
    }

    public function addComment($comment)
    {
        $comments = $this->comments ?? []; // 既存のコメントを取得（nullなら空配列）
        $comments[] = [
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'comment' => $comment,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];

        $this->comments = $comments; // JSONフィールドにコメントを更新
        $this->save(); // モデルを保存
    }

    public function getCommentsAttribute($value)
    {
        $comments = is_array($value) ? $value : (json_decode($value, true) ?? []);
        return array_reverse($comments); // 🔄 新しいコメントを上にする
    }

    public function updateComment($commentIndex, $newComment)
    {
        $comments = $this->comments ?? [];

        if (isset($comments[$commentIndex])) {
            $comments[$commentIndex]['comment'] = $newComment;
            $comments[$commentIndex]['timestamp'] = now()->format('Y-m-d H:i:s');
            $this->update(['comments' => $comments]);
        }
    }

    public function deleteComment($commentIndex)
    {
        $comments = $this->comments ?? [];

        if (isset($comments[$commentIndex])) {
            array_splice($comments, $commentIndex, 1); // ✅ コメントを削除
            $this->update(['comments' => $comments]);
        }
    }


}
