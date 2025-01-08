<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesCourse extends Model
{
    use HasFactory;

    // テーブル名を明示的に指定
    protected $table = 'SalesCorse'; // テーブル名を明示的に指定
    public $timestamps = false;      // タイムスタンプを無効化
    protected $primaryKey = 'id';

    // 必要に応じて、更新可能なカラムを指定（ホワイトリスト方式）
    protected $fillable = [
        'Course',
        'ManagerCode',
        'Manager',
        'ExecutiveOfficerCode',
        'ExecutiveOfficer',
        'Department',
    ];
}
