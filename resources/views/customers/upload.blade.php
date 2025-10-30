@extends('layouts.app')

@section('content')
<div class="container">


    <div class="row">
        <div class="col-6 text-stat"><h1 class="">得意先データ</h1></div>
        <div class="col-6 text-end"><a href="{{ route('customers.index') }}"
                                       class="btn btn-sm btn-success">一覧に戻る</a></div>
    </div>
    <div class="row">
        <div class="col-12 mt-4  p-3">
            <form id="csv-upload-form" action="{{ route('customers.upload.post') }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">CSVファイルを選択してください</label>
                    <input type="file" class="form-control" id="file" name="file" required>
                </div>
                <button id="upload-button" type="submit" class="btn btn-primary">アップロード</button>
            </form>
        </div>
    </div>


    <!-- スピナー -->
    <div id="loading-spinner" style="display: none;">
        <div class="spinner"></div>
        <p>アップロード中...</p>
    </div>
</div>

<style>
    #loading-spinner {
        position: fixed;
        top: 50%;
        left: calc(50% + 128px); /* サイドバーの幅分右に移動 */
        transform: translate(-50%, -50%);
        z-index: 9999;
        text-align: center;
        display: none;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(0, 0, 0, 0.1);
        border-top-color: #007bff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* このスタイルでテキストを左に移動 */
    #loading-spinner p {
        margin-left: -30px; /* 左に50px動かす */
        margin-top: 10px;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // フォーム送信時にスピナーを表示
        $('#csv-upload-form').on('submit', function (e) {
            e.preventDefault(); // デフォルトのフォーム送信を無効化

            // スピナーを表示
            $('#loading-spinner').css('display', 'block');

            // フォームデータを取得
            let formData = new FormData(this);

            // AJAXリクエストを送信
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // スピナーを非表示にする
                    $('#loading-spinner').css('display', 'none');
                    alert('CSVアップロードが成功しました');

                    // indexページにリダイレクト
                    window.location.href = "{{ route('customers.index') }}";
                },
                error: function (xhr, status, error) {
                    // スピナーを非表示にする
                    $('#loading-spinner').css('display', 'none');
                    alert('エラーが発生しました: ' + error);
                }
            });
        });
    });
</script>

@endsection
