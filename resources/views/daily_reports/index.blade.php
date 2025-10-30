@extends('layouts.app')

@section('title', '日報一覧')

@section('content')

<div class="container">
    <h1 class="mb-4 text-center">日報一覧</h1>

        <!-- 🔍 検索フォーム -->
    <form method="GET" action="{{ route('daily_reports.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="report_date">日付</label>
                <input type="date" id="report_date" name="report_date" class="form-control" value="{{ request('report_date') }}">
            </div>
            <div class="col-md-4">
                <label for="manager_id">担当者</label>
                <select id="manager_id" name="manager_id" class="form-control">
                    <option value="">選択してください</option>
                    @foreach($managers as $code => $name)
                        <option value="{{ $code }}" {{ request('manager_id') == $code ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">検索</button>
                <a href="{{ route('daily_reports.index') }}" class="btn btn-secondary ms-2">リセット</a>
            </div>
        </div>
    </form>

    <div class="row">
        <!-- 日報一覧 -->
        <div class="col-8">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>日付</th>
                    <th>コース</th>
                    <th>担当者</th>
                    <th>顧客名</th>
                    <th>業務内容</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                <tr onclick="showReportDetail({{ $report->id }})" style="cursor: pointer;">
                    <td>{{ $report->report_date }}</td>
                    <td>{{ $report->Course }}</td>
                    <td>{{ $report->manager_name }}</td>
                    <td>{{ $report->customer_name }}</td>
                    <td>
                        @if(!empty($report->content))
                        {{ Str::limit($report->content, 50) }}
                        @else
                        <span class="text-muted">内容なし</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $reports->links() }}
            </div>
        </div>

        <!-- 詳細表示エリア -->
        <div class="col-4">
            <div id="report-detail" class="card">
                <div class="card-header">
                    <strong>日報詳細</strong>
                </div>
                <div class="card-body">
                    <p class="text-muted">詳細を表示するには、一覧から選択してください。</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Ajax -->
@push('scripts')
<script>
    let selectedReportId = null;
    let fetchingReport = false;

    function showReportDetail(reportId) {
        if (fetchingReport || selectedReportId === reportId) return;
        selectedReportId = reportId;
        fetchingReport = true;

        document.getElementById('report-detail').innerHTML = `
            <div class="card">
                <div class="card-header"><strong>日報詳細</strong></div>
                <div class="card-body"><p class="text-muted">読み込み中...</p></div>
            </div>
        `;

        fetch(`/daily_reports/${reportId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`サーバーエラー: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                document.getElementById('report-detail').innerHTML = html;
                attachCommentFormHandler();
            })
            .catch(error => {
                console.error('Error fetching report details:', error);
                document.getElementById('report-detail').innerHTML = `
                    <div class="card">
                        <div class="card-header"><strong>日報詳細</strong></div>
                        <div class="card-body"><p class="text-danger">読み込みエラーが発生しました</p></div>
                    </div>
                `;
            })
            .finally(() => fetchingReport = false);
    }

    function attachCommentFormHandler() {
        const form = document.getElementById('comment-form');
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const reportId = document.getElementById('report_id').value;
                const comment = document.getElementById('comment').value;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/daily_reports/${reportId}/comment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ comment })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    showReportDetail(reportId);
                    document.getElementById('comment').value = '';
                })
                .catch(error => console.error('Error:', error));
            });
        }

        // ✅ 編集ボタンを再設定
        document.querySelectorAll('.edit-comment').forEach(button => {
            button.addEventListener('click', function() {
                editComment(this.dataset.index);
            });
        });

        // ✅ 保存ボタンを再設定
        document.querySelectorAll('.save-comment').forEach(button => {
            button.addEventListener('click', function() {
                saveComment(this.dataset.reportId, this.dataset.index);
            });
        });
    }

    function editComment(commentIndex) {
        document.getElementById(`comment-text-${commentIndex}`).classList.add('d-none');
        document.getElementById(`comment-edit-${commentIndex}`).classList.remove('d-none');
        document.querySelector(`.save-comment[data-index="${commentIndex}"]`).classList.remove('d-none');
        document.querySelector(`.edit-comment[data-index="${commentIndex}"]`).classList.add('d-none'); // ✅ 編集ボタンを非表示
    }

    function saveComment(reportId, commentIndex) {
        let newComment = document.getElementById(`comment-edit-${commentIndex}`).value;
        fetch(`/daily_reports/${reportId}/comment/${commentIndex}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ comment: newComment })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById(`comment-content-${commentIndex}`).innerText = newComment;
            document.getElementById(`comment-text-${commentIndex}`).classList.remove('d-none');
            document.getElementById(`comment-edit-${commentIndex}`).classList.add('d-none');
            document.querySelector(`.save-comment[data-index="${commentIndex}"]`).classList.add('d-none');
            document.querySelector(`.edit-comment[data-index="${commentIndex}"]`).classList.remove('d-none'); // ✅ 編集ボタンを再表示
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endpush

@endsection
