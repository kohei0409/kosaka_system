@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">BackLog 一覧</h1>

    <div class="row">
        <!-- 検索フォーム -->
        <div class="col-6 offset-3 mb-4">
            <form method="GET" action="{{ route('backlogs.index') }}">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" placeholder="検索キーワードを入力"
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">検索</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row bg-white mt-3">
        <div class="col-12 text-end py-3 bg-light">

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
                        <th style="white-space: nowrap;">受注数</th>
                        <th style="white-space: nowrap;">受注残数</th>
                        <th style="white-space: nowrap;">欠品数</th>
                        <th style="white-space: nowrap;">売上単価</th>
                        <th style="white-space: nowrap;">売上金額</th>
                        <th style="white-space: nowrap;">得意先名</th>
                        <th style="white-space: nowrap;">受注日</th>
                        <th style="white-space: nowrap;">伝票番号</th>
                        <th style="white-space: nowrap;">行番号</th>
                        <th style="white-space: nowrap;">営業コース</th>
                        <th style="white-space: nowrap;">担当者名</th>
                        <th style="white-space: nowrap;">売上区分</th>
                        <th style="white-space: nowrap;">更新日</th>
                        <th style="white-space: nowrap;">更新回数</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($backlogs as $backlog)
                    <tr>
                        <td>{{ $backlog->id }}</td>
                        <td>{{ $backlog->CustomerCode }}</td>
                        <td>{{ $backlog->BranchCode }}</td>
                        <td>{{ $backlog->ProductCode }}</td>
                        <td>{{ $backlog->JanCode }}</td>
                        <td>{{ $backlog->ManufacturerName }}</td>
                        <td>{{ $backlog->ProductName }}</td>
                        <td>{{ $backlog->Specification }}</td>
                        <td>{{ $backlog->Quantity }}</td>
                        <td>{{ $backlog->Unit }}</td>
                        <td>{{ $backlog->OrderQuantity }}</td>
                        <td>{{ $backlog->RemainingOrderQty }}</td>
                        <td>{{ $backlog->OutOfStockQty }}</td>
                        <td>{{ $backlog->SalesUnitPrice }}</td>
                        <td>{{ $backlog->SalesAmount }}</td>
                        <td>{{ $backlog->CustomerName }}</td>
                        <td>{{ $backlog->OrderDate }}</td>
                        <td>{{ $backlog->InvoiceNumber }}</td>
                        <td>{{ $backlog->LineNumber }}</td>
                        <td>{{ $backlog->SalesCourse }}</td>
                        <td>{{ $backlog->SalesRepresentative }}</td>
                        <td>{{ $backlog->SalesCategory }}</td>
                        <td>{{ $backlog->LastUpdateDate }}</td>
                        <td>{{ $backlog->UpdateCount }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- ページネーション -->
                <div class="d-flex justify-content-center">
                    {{ $backlogs->links() }}
                </div>
            </div>
            @endsection
