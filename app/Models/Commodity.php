<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commodity extends Model
{
    use HasFactory;

    protected $table = 'CommodityData';

    protected $fillable = [
        'ProductCode',
        'JANCode',
        'ManufacturerName',
        'ProductName',
        'Specification',
        'Abbreviation',
        'DentalFormulaName',
        'ProductNameKana',
        'Publisher',
        'PublisherName',
        'InternalName',
        'NormalQuantity1',
        'NormalQuantity2',
        'NormalQuantity3',
        'StandardUnitPrice1',
        'StandardUnitPrice2',
        'StandardUnitPrice3',
        'ListPrice',
        'PatientPrice',
        'ProductGroupCode',
        'ProductUnifiedCode',
        'UpdateDate',
        'UpdateCount',
        'StopClassification',
        'InvalidClassification',
        'PauseClassification',
        'StockClassification',
        'StockCount',
        'OutstandingOrderCount',
        'ShortageCount',
        'PendingOrderCount',
        'StockUpdateDate',
        'SaleQuantity1',
        'SaleQuantity2',
        'SaleQuantity3',
        'SaleUnitPrice1',
        'SaleUnitPrice2',
        'SaleUnitPrice3',
        'SaleStartDateTime',
        'SaleEndDateTime',
        'SaleInformationUpdateDate',
    ];
}
