<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // テーブル名を指定
    protected $table = 'CustomerData';

    // タイムスタンプを無効化
    public $timestamps = false;

    // 更新可能なカラム（ホワイトリスト方式）
    protected $fillable = [
        'CustomerCode',
        'BranchCode',
        'CustomerOfficialName1',
        'CustomerOfficialName2',
        'CustomerName',
        'CustomerKana',
        'RepresentativeName',
        'ManagerName',
        'PostalCode',
        'Address1',
        'Address2',
        'PhoneNumber',
        'PhoneNumber2',
        'PhoneNumber3',
        'Fax',
        'SalesCourse',
        'ManagerCode',
        'TransactionStopType',
        'TransactionEndType',
        'UpdateDate',
        'UpdateCount',
        'InvalidType',
    ];
}
