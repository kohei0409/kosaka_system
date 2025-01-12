@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">注文データ管理</h1>

    <div class="row">
        <!-- 検索フォーム -->
        <div class="col-6 offset-3 mb-4">
            <form method="GET" action="{{ route('orderdata.index') }}">
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
            {{ $orders->links('pagination.custom') }}
        </div>

        <!-- 横スクロール可能なテーブル -->
        <div style="overflow-x: auto; width: calc(100%);">
            <table class="table table-striped" style="max-width: 2000px; border-collapse: collapse;">
                <thead style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1;">
                <tr>
                    <th style="white-space: nowrap;">ID</th>
                    <th style="white-space: nowrap;">得意先コード</th>
                    <th style="white-space: nowrap;">支店コード</th>
                    <th style="white-space: nowrap;">商品コード</th>
                    <th style="white-space: nowrap;">JANコード</th>
                    <th style="white-space: nowrap;">メーカー名</th>
                    <th style="white-space: nowrap;">商品名</th>
                    <th style="white-space: nowrap;">規格</th>
                    <th style="white-space: nowrap;">数量</th>
                    <th style="white-space: nowrap;">単位</th>
                    <th style="white-space: nowrap;">総バラ数</th>
                    <th style="white-space: nowrap;">売上単価</th>
                    <th style="white-space: nowrap;">売上金額</th>
                    <th style="white-space: nowrap;">ロット番号</th>
                    <th style="white-space: nowrap;">シリアル番号</th>
                    <th style="white-space: nowrap;">得意先名</th>
                    <th style="white-space: nowrap;">売上日</th>
                    <th style="white-space: nowrap;">伝票番号</th>
                    <th style="white-space: nowrap;">行番号</th>
                    <th style="white-space: nowrap;">営業コース</th>
                    <th style="white-space: nowrap;">担当者名</th>
                    <th style="white-space: nowrap;">売上区分</th>
                    <th style="white-space: nowrap;">更新日</th>
                    <th style="white-space: nowrap;">更新回数</th>
                    <th style="white-space: nowrap;">無効区分</th>
                    <th style="white-space: nowrap;">チェックコード</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                <tr>
                    <td style="white-space: nowrap;">{{ $order->id }}</td>
                    <td style="white-space: nowrap;">{{ $order->CustomerCode }}</td>
                    <td style="white-space: nowrap;">{{ $order->BranchCode }}</td>
                    <td style="white-space: nowrap;">{{ $order->ProductCode }}</td>
                    <td style="white-space: nowrap;">{{ $order->JANCode }}</td>
                    <td style="white-space: nowrap;">{{ $order->ManufacturerName }}</td>
                    <td style="white-space: nowrap;">{{ $order->ProductName }}</td>
                    <td style="white-space: nowrap;">{{ $order->Specification }}</td>
                    <td style="white-space: nowrap;">{{ $order->Quantity }}</td>
                    <td style="white-space: nowrap;">{{ $order->Unit }}</td>
                    <td style="white-space: nowrap;">{{ $order->TotalUnits }}</td>
                    <td style="white-space: nowrap;">{{ $order->SalesUnitPrice }}</td>
                    <td style="white-space: nowrap;">{{ $order->SalesAmount }}</td>
                    <td style="white-space: nowrap;">{{ $order->LotNumber }}</td>
                    <td style="white-space: nowrap;">{{ $order->SerialNumber }}</td>
                    <td style="white-space: nowrap;">{{ $order->CustomerName }}</td>
                    <td style="white-space: nowrap;">{{ $order->SalesDate }}</td>
                    <td style="white-space: nowrap;">{{ $order->InvoiceNumber }}</td>
                    <td style="white-space: nowrap;">{{ $order->LineNumber }}</td>
                    <td style="white-space: nowrap;">{{ $order->SalesCourse }}</td>
                    <td style="white-space: nowrap;">{{ $order->SalesRepresentativeName }}</td>
                    <td style="white-space: nowrap;">{{ $order->SalesCategory }}</td>
                    <td style="white-space: nowrap;">{{ $order->UpdateDate }}</td>
                    <td style="white-space: nowrap;">{{ $order->UpdateCount }}</td>
                    <td style="white-space: nowrap;">{{ $order->InvalidCategory }}</td>
                    <td style="white-space: nowrap;">{{ $order->CheckUnique }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- ページネーション（下部） -->
        <div class="col-12 text-end py-3 bg-light">
            {{ $orders->links('pagination.custom') }}
        </div>
    </div>
</div>
@endsection
