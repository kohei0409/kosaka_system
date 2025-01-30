<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackLog extends Model
{
    use HasFactory;

    protected $table = 'BackLog';
    public $timestamps = false;

    protected $fillable = [
        'CustomerCode',
        'BranchCode',
        'ProductCode',
        'JanCode',
        'ManufacturerName',
        'ProductName',
        'Specification',
        'Quantity',
        'Unit',
        'OrderQuantity',
        'RemainingOrderQty',
        'OutOfStockQty',
        'SalesUnitPrice',
        'SalesAmount',
        'CustomerName',
        'OrderDate',
        'InvoiceNumber',
        'LineNumber',
        'SalesCourse',
        'SalesRepresentative',
        'SalesCategory',
        'LastUpdateDate',
        'UpdateCount',
    ];
}
