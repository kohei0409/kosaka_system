<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderData extends Model
{
    protected $table = 'OrderData';

    protected $fillable = [
        'CustomerCode',
        'BranchCode',
        'ProductCode',
        'JANCode',
        'ManufacturerName',
        'ProductName',
        'Specification',
        'Quantity',
        'Unit',
        'TotalUnits',
        'SalesUnitPrice',
        'SalesAmount',
        'LotNumber',
        'SerialNumber',
        'CustomerName',
        'SalesDate',
        'InvoiceNumber',
        'LineNumber',
        'SalesCourse',
        'SalesRepresentativeName',
        'SalesCategory',
        'UpdateDate',
        'UpdateCount',
        'InvalidCategory',
        'CheckUnique',
    ];
}
