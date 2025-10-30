<div class="card">
    <div class="card-header">
        <strong>日報詳細</strong>
    </div>
    <div class="card-body">
        <p><strong>日付:</strong> {{ $report->report_date }}</p>
        <p><strong>コース:</strong> {{ $report->Course }}</p>
        <p><strong>担当者:</strong> {{ $report->manager_name }}</p>
        <p><strong>顧客名:</strong> {{ $report->customer_name }}</p>
        <p><strong>業務内容:</strong> {!! nl2br(e($report->content)) !!}</p>
        <p><strong>次回予定:</strong>
            @if(!empty($report->next_schedule))
            {{ $report->next_schedule }}
            @else
            <span class="text-muted">未定</span>
            @endif
        </p>
    </div>
</div>

<!-- コメント一覧 -->
<div class="card mt-3">
    <div class="card-header">
        <strong>コメント</strong>
    </div>
    <div class="card-body">
        <ul id="comment-list" class="list-group">
@forelse ($report->comments ?? [] as $index => $comment)
    <li class="list-group-item d-flex flex-column" id="comment-{{ $index }}">
        <div>
            <span class="comment-text" id="comment-text-{{ $index }}" data-index="{{ $index }}">
                <strong>{{ $comment['user_name'] ?? '不明' }}</strong>:
                <span id="comment-content-{{ $index }}">{{ $comment['comment'] ?? '' }}</span>
            </span>
            <textarea class="comment-edit form-control d-none mt-2" id="comment-edit-{{ $index }}" data-index="{{ $index }}"
                      rows="3">{{ $comment['comment'] ?? '' }}</textarea>
        </div>
        <div class="mt-2">
            <button class="btn btn-sm btn-warning edit-comment" data-index="{{ $index }}">編集</button>
            <button class="btn btn-sm btn-primary save-comment d-none" data-report-id="{{ $report->id }}" data-index="{{ $index }}">保存</button>
        </div>
    </li>
@empty
    <p class="text-muted">まだコメントがありません。</p>
@endforelse

        </ul>
    </div>
</div>

<!-- コメント投稿フォーム -->
<div class="card mt-3">
    <div class="card-header">
        <strong>コメントを追加</strong>
    </div>
    <div class="card-body">
        <form id="comment-form">
            @csrf
            <input type="hidden" id="report_id" value="{{ $report->id }}">
            <div class="form-group">
                <textarea id="comment" class="form-control" rows="3"
                          placeholder="コメントを入力してください"></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-2">送信</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        console.log("スクリプトが読み込まれました");

        document.querySelectorAll('.edit-comment').forEach(button => {
            button.addEventListener('click', function () {
                console.log("編集ボタンクリック:", this.dataset.index);
                editComment(this.dataset.index);
            });
        });

        document.querySelectorAll('.save-comment').forEach(button => {
            button.addEventListener('click', function () {
                console.log("保存ボタンクリック:", this.dataset.index);
                saveComment(this.dataset.reportId, this.dataset.index);
            });
        });

        document.querySelectorAll('.cancel-edit').forEach(button => {
            button.addEventListener('click', function () {
                console.log("キャンセルボタンクリック:", this.dataset.index);
                cancelEdit(this.dataset.index);
            });
        });
    });

    function editComment(commentIndex) {
        console.log("editComment関数:", commentIndex);
        document.getElementById(`comment-text-${commentIndex}`).classList.add('d-none');
        document.getElementById(`comment-edit-${commentIndex}`).classList.remove('d-none');
        document.querySelector(`.save-comment[data-index="${commentIndex}"]`).classList.remove('d-none');
        document.querySelector(`.cancel-edit[data-index="${commentIndex}"]`).classList.remove('d-none');
    }

    function saveComment(reportId, commentIndex) {
        let newComment = document.getElementById(`comment-edit-${commentIndex}`).value;
        fetch(`/daily_reports/${reportId}/comment/${commentIndex}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({comment: newComment})
        })
            .then(response => response.json())
            .then(data => {
                console.log("保存成功:", data);
                document.getElementById(`comment-content-${commentIndex}`).innerText = newComment;
                cancelEdit(commentIndex);
            })
            .catch(error => console.error('Error:', error));
    }

    function cancelEdit(commentIndex) {
        console.log("cancelEdit関数:", commentIndex);
        document.getElementById(`comment-text-${commentIndex}`).classList.remove('d-none');
        document.getElementById(`comment-edit-${commentIndex}`).classList.add('d-none');
        document.querySelector(`.save-comment[data-index="${commentIndex}"]`).classList.add('d-none');
        document.querySelector(`.cancel-edit[data-index="${commentIndex}"]`).classList.add('d-none');
    }
</script>
@endpush
