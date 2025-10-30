@extends('layouts.app')

@section('content')
<div class="container-fluid">


    <div class="row">
        <div class="col-6 text-stat"><h1 class="">商品データ管理</h1></div>
        <div class="col-6 text-end"><a href="{{ route('commodities.upload') }}"
                                       class="btn btn-sm btn-success">ファイルのアップロード</a></div>
    </div>

    <div class="row mt-3">
        <!-- グループボタン -->


        <div class="col-6 offset-3 mb-4">
            <form method="GET" action="{{ route('commodities.index') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="検索キーワードを入力"
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">検索</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ページネーション（上部） -->
    <div class="row bg-white mt-3">
        <div class="col-12 text-end py-3 bg-light">
            {{ $commodities->links('pagination.custom') }}
        </div>


        <!-- 横スクロール可能なテーブル -->
        <div style="overflow-x: auto; width: calc(100%);">
            <table class="table table-striped" style="max-width: 2000px; border-collapse: collapse;">
                <thead style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1;">
                <tr>
                    <th style="white-space: nowrap;">ID</th>
                    <th style="white-space: nowrap;">商品コード</th>
                    <th style="white-space: nowrap;">JANコード</th>
                    <th style="white-space: nowrap;">メーカー名</th>
                    <th style="white-space: nowrap;">商品名</th>
                    <th style="white-space: nowrap;">規格</th>
                    <th style="white-space: nowrap;">略称</th>
                    <th style="white-space: nowrap;">歯式名</th>
                    <th style="white-space: nowrap;">商品名カナ</th>
                    <th style="white-space: nowrap;">発売元</th>
                    <th style="white-space: nowrap;">発売元名</th>
                    <th style="white-space: nowrap;">社内名</th>
                    <th style="white-space: nowrap;">通常数量１</th>
                    <th style="white-space: nowrap;">通常数量２</th>
                    <th style="white-space: nowrap;">通常数量３</th>
                    <th style="white-space: nowrap;">標準販売単価１</th>
                    <th style="white-space: nowrap;">標準販売単価２</th>
                    <th style="white-space: nowrap;">標準販売単価３</th>
                    <th style="white-space: nowrap;">定価</th>
                    <th style="white-space: nowrap;">患者価格</th>
                    <th style="white-space: nowrap;">商品グループコード</th>
                    <th style="white-space: nowrap;">商品統一コード</th>
                    <th style="white-space: nowrap;">更新日</th>
                    <th style="white-space: nowrap;">更新回数</th>
                    <th style="white-space: nowrap;">中止区分</th>
                    <th style="white-space: nowrap;">無効区分</th>
                    <th style="white-space: nowrap;">停止区分</th>
                    <th style="white-space: nowrap;">在庫区分</th>
                    <th style="white-space: nowrap;">在庫数</th>
                    <th style="white-space: nowrap;">受注残数</th>
                    <th style="white-space: nowrap;">欠品数</th>
                    <th style="white-space: nowrap;">発注残数</th>
                    <th style="white-space: nowrap;">在庫更新日</th>
                    <th style="white-space: nowrap;">セール数量１</th>
                    <th style="white-space: nowrap;">セール数量２</th>
                    <th style="white-space: nowrap;">セール数量３</th>
                    <th style="white-space: nowrap;">セール単価１</th>
                    <th style="white-space: nowrap;">セール単価２</th>
                    <th style="white-space: nowrap;">セール単価３</th>
                    <th style="white-space: nowrap;">セール適用日時Ｆ</th>
                    <th style="white-space: nowrap;">セール適用日時Ｔ</th>
                    <th style="white-space: nowrap;">セール情報更新日</th>

                </tr>
                </thead>
                <tbody>
                @foreach($commodities as $commodity)
                <tr>
                    <td style="white-space: nowrap;">{{ $commodity->id }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->ProductCode }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->JANCode }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->ManufacturerName }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->ProductName }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->Specification }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->Abbreviation }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->DentalFormulaName }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->ProductNameKana }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->Publisher }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->PublisherName }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->InternalName }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->NormalQuantity1 }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->NormalQuantity2 }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->NormalQuantity3 }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->StandardUnitPrice1 }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->StandardUnitPrice2 }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->StandardUnitPrice3 }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->ListPrice }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->PatientPrice }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->ProductGroupCode }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->ProductUnifiedCode }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->UpdateDate }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->UpdateCount }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->StopClassification }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->InvalidClassification }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->PauseClassification }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->StockClassification }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->StockCount }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->OutstandingOrderCount }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->ShortageCount }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->PendingOrderCount }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->StockUpdateDate }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->SaleQuantity1 }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->SaleQuantity2 }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->SaleQuantity3 }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->SaleUnitPrice1 }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->SaleUnitPrice2 }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->SaleUnitPrice3 }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->SaleStartDateTime }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->SaleEndDateTime }}</td>
                    <td style="white-space: nowrap;">{{ $commodity->SaleInformationUpdateDate }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>


        <!-- ページネーション（下部） -->
        <div class="col-12 text-end py-3 bg-light">
            {{ $commodities->links('pagination.custom') }}
        </div>
    </div>
</div>
@endsection
