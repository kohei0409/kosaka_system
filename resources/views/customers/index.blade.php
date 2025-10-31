@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="row">
        <div class="col-6 text-stat"><h1 class="">得意先データ</h1></div>
        <div class="col-6 text-end"><a href="{{ route('customers.upload') }}"
                                       class="btn btn-sm btn-success">ファイルのアップロード</a></div>
    </div>


    <div class="row mt-3">
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
                    <th style="white-space: nowrap;">操作</th>
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
                    <td style="white-space: nowrap;">
                        <button class="btn btn-sm btn-danger delete-customer-btn"
                                data-customer-code="{{ $customer->CustomerCode }}"
                                data-branch-code="{{ $customer->BranchCode }}"
                                data-customer-name="{{ $customer->CustomerOfficialName1 }}">
                            削除
                        </button>
                    </td>
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




</style>

<!-- 削除確認モーダル -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">得意先削除確認</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="step1" class="delete-step">
                    <p><strong>削除対象:</strong> <span id="customerNameDisplay"></span></p>
                    <p class="text-danger">この操作は取り消せません。本当に削除しますか?</p>
                    <p class="text-muted small">削除確認コードを <strong>sawachi@adtrust.jp</strong> に送信します。</p>
                </div>
                <div id="step2" class="delete-step" style="display: none;">
                    <div class="alert alert-success" role="alert">
                        <strong>削除コードを送信しました</strong><br>
                        sawachi@adtrust.jp にメールを送信しました。<br>
                        メールに記載された6桁のコードを入力してください。<br>
                        <small class="text-muted">有効期限: <span id="expiresAt"></span></small>
                    </div>
                    <div class="mb-3">
                        <label for="deletionCode" class="form-label">削除確認コード (6桁)</label>
                        <input type="text" class="form-control form-control-lg text-center" id="deletionCode"
                               placeholder="000000" maxlength="6" pattern="[0-9]{6}">
                    </div>
                </div>
                <div id="deleteMessage" class="alert" style="display: none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                <button type="button" class="btn btn-danger" id="requestDeleteBtn">削除コードを送信</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" style="display: none;">削除実行</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentCustomerCode = '';
    let currentBranchCode = '';
    let currentCustomerName = '';

    // 削除ボタンクリック
    document.querySelectorAll('.delete-customer-btn').forEach(button => {
        button.addEventListener('click', function() {
            currentCustomerCode = this.dataset.customerCode;
            currentBranchCode = this.dataset.branchCode;
            currentCustomerName = this.dataset.customerName;

            document.getElementById('customerNameDisplay').textContent = currentCustomerName;

            // モーダルをリセット
            document.getElementById('step1').style.display = 'block';
            document.getElementById('step2').style.display = 'none';
            document.getElementById('requestDeleteBtn').style.display = 'inline-block';
            document.getElementById('confirmDeleteBtn').style.display = 'none';
            document.getElementById('deletionCode').value = '';
            document.getElementById('deleteMessage').style.display = 'none';

            // モーダルを表示
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });
    });

    // 削除コード送信
    document.getElementById('requestDeleteBtn').addEventListener('click', function() {
        this.disabled = true;
        const btn = this;

        fetch('{{ route("customers.request-delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                customer_code: currentCustomerCode,
                branch_code: currentBranchCode || null
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('step1').style.display = 'none';
                document.getElementById('step2').style.display = 'block';
                btn.style.display = 'none';
                document.getElementById('confirmDeleteBtn').style.display = 'inline-block';
                document.getElementById('expiresAt').textContent = new Date(data.expires_at).toLocaleString('ja-JP');
            } else {
                showMessage(data.message, 'danger');
            }
        })
        .catch(error => {
            showMessage('エラーが発生しました: ' + error.message, 'danger');
        })
        .finally(() => {
            btn.disabled = false;
        });
    });

    // 削除実行
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        const code = document.getElementById('deletionCode').value;

        if (code.length !== 6) {
            showMessage('6桁のコードを入力してください', 'warning');
            return;
        }

        this.disabled = true;
        const btn = this;

        fetch('{{ route("customers.confirm-delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                customer_code: currentCustomerCode,
                branch_code: currentBranchCode || null,
                token: code
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                showMessage(data.message, 'danger');
                btn.disabled = false;
            }
        })
        .catch(error => {
            showMessage('エラーが発生しました: ' + error.message, 'danger');
            btn.disabled = false;
        });
    });

    function showMessage(message, type) {
        const messageDiv = document.getElementById('deleteMessage');
        messageDiv.className = 'alert alert-' + type;
        messageDiv.textContent = message;
        messageDiv.style.display = 'block';
    }
});
</script>

@endsection
