@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="row">
        <!-- グループボタン -->
        <div class="col-8 mb-4 text-start">
            <a href="{{ route('customers.index', ['group' => 'all']) }}"
               class="btn btn-primary {{ $group === 'all' ? 'active' : '' }}">全て</a>
            <a href="{{ route('customers.index', ['group' => 'a']) }}"
               class="btn btn-secondary {{ $group === 'a' ? 'active' : '' }}">あ行</a>
            <a href="{{ route('customers.index', ['group' => 'k']) }}"
               class="btn btn-secondary {{ $group === 'k' ? 'active' : '' }}">か行</a>
            <a href="{{ route('customers.index', ['group' => 's']) }}"
               class="btn btn-secondary {{ $group === 's' ? 'active' : '' }}">さ行</a>
            <a href="{{ route('customers.index', ['group' => 't']) }}"
               class="btn btn-secondary {{ $group === 't' ? 'active' : '' }}">た行</a>
            <a href="{{ route('customers.index', ['group' => 'n']) }}"
               class="btn btn-secondary {{ $group === 'n' ? 'active' : '' }}">な行</a>
            <a href="{{ route('customers.index', ['group' => 'h']) }}"
               class="btn btn-secondary {{ $group === 'h' ? 'active' : '' }}">は行</a>
            <a href="{{ route('customers.index', ['group' => 'm']) }}"
               class="btn btn-secondary {{ $group === 'm' ? 'active' : '' }}">ま行</a>
            <a href="{{ route('customers.index', ['group' => 'y']) }}"
               class="btn btn-secondary {{ $group === 'y' ? 'active' : '' }}">や行</a>
            <a href="{{ route('customers.index', ['group' => 'r']) }}"
               class="btn btn-secondary {{ $group === 'r' ? 'active' : '' }}">ら行</a>
            <a href="{{ route('customers.index', ['group' => 'w']) }}"
               class="btn btn-secondary {{ $group === 'w' ? 'active' : '' }}">わ行</a>

        </div>

        <div class="col-4">
            <form method="GET" action="{{ route('customers.index') }}">
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

        <div class="col-12 text-end py-3 bg-light"> {{ $customers->appends(['group' =>
            $group])->links('pagination.custom') }}
        </div>


        <!-- 横スクロール可能なテーブル -->
        <div style="overflow-x: auto; width: calc(100%);">
            <table class="table table-striped" style="max-width: 2000px; border-collapse: collapse;">
                <thead style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1;">
                <tr>
                    <th style="white-space: nowrap;">ID</th>
                    <th style="white-space: nowrap;">得意先コード</th>
                    <th style="white-space: nowrap;">支店コード</th>
                    <th style="white-space: nowrap;">得意先正式名１</th>
                    <th style="white-space: nowrap;">得意先正式名２</th>
                    <th style="white-space: nowrap;">得意先名</th>
                    <th style="white-space: nowrap;">得意先カナ</th>
                    <th style="white-space: nowrap;">代表者名</th>
                    <th style="white-space: nowrap;">管理者名</th>
                    <th style="white-space: nowrap;">郵便番号</th>
                    <th style="white-space: nowrap;">住所１</th>
                    <th style="white-space: nowrap;">住所２</th>
                    <th style="white-space: nowrap;">電話番号</th>
                    <th style="white-space: nowrap;">電話番号２</th>
                    <th style="white-space: nowrap;">電話番号３</th>
                    <th style="white-space: nowrap;">ＦＡＸ</th>
                    <th style="white-space: nowrap;">営業コース</th>
                    <th style="white-space: nowrap;">担当者コード</th>
                    <th style="white-space: nowrap;">取引停止区分</th>
                    <th style="white-space: nowrap;">取引中止区分</th>
                    <th style="white-space: nowrap;">更新日</th>
                    <th style="white-space: nowrap;">更新回数</th>
                    <th style="white-space: nowrap;">無効区分</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td style="white-space: nowrap;">{{ $customer->id }}</td>
                    <td style="white-space: nowrap;">{{ $customer->CustomerCode }}</td>
                    <td style="white-space: nowrap;">{{ $customer->BranchCode }}</td>
                    <td style="white-space: nowrap;">{{ $customer->CustomerOfficialName1 }}</td>
                    <td style="white-space: nowrap;">{{ $customer->CustomerOfficialName2 }}</td>
                    <td style="white-space: nowrap;">{{ $customer->CustomerName }}</td>
                    <td style="white-space: nowrap;">{{ $customer->CustomerKana }}</td>
                    <td style="white-space: nowrap;">{{ $customer->RepresentativeName }}</td>
                    <td style="white-space: nowrap;">{{ $customer->ManagerName }}</td>
                    <td style="white-space: nowrap;">{{ $customer->PostalCode }}</td>
                    <td style="white-space: nowrap;">{{ $customer->Address1 }}</td>
                    <td style="white-space: nowrap;">{{ $customer->Address2 }}</td>
                    <td style="white-space: nowrap;">{{ $customer->PhoneNumber }}</td>
                    <td style="white-space: nowrap;">{{ $customer->PhoneNumber2 }}</td>
                    <td style="white-space: nowrap;">{{ $customer->PhoneNumber3 }}</td>
                    <td style="white-space: nowrap;">{{ $customer->Fax }}</td>
                    <td style="white-space: nowrap;">{{ $customer->SalesCourse }}</td>
                    <td style="white-space: nowrap;">{{ $customer->ManagerCode }}</td>
                    <td style="white-space: nowrap;">{{ $customer->TransactionStopType }}</td>
                    <td style="white-space: nowrap;">{{ $customer->TransactionEndType }}</td>
                    <td style="white-space: nowrap;">{{ $customer->UpdateDate }}</td>
                    <td style="white-space: nowrap;">{{ $customer->UpdateCount }}</td>
                    <td style="white-space: nowrap;">{{ $customer->InvalidType }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- ページネーション（下部） -->
        <div class="col-12 text-end py-3 bg-light"> {{ $customers->appends(['group' =>
            $group])->links('pagination.custom') }}
        </div>
    </div>
</div>

<style>


    .table {
        overflow-x: auto;
        display: block;
    }

    .pagination {
        display: flex;
        justify-content: right;
        flex-wrap: wrap;
    }


    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
        padding: 10px 0;
    }

    .page-link {
        display: inline-block;
        padding: 3px 15px;
        border: 1px solid darkorange;
        border-radius: 4px;
        text-decoration: none;
        color: darkorange;
        background-color: #fff;
        transition: background-color 0.3s, color 0.3s;
    }

    .page-link:hover {
        background-color: #0056b3;
        color: #fff;
    }

    .page-link.active {
        background-color: darkorange;
        color: #fff;
        border-color: darkorange;
        pointer-events: none;
    }


</style>


@endsection
