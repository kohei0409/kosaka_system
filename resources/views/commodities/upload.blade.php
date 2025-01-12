@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品データ CSVアップロード</h1>    <!-- CSVアップロードフォーム -->
    <form action="{{ route('commodities.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">CSVファイルを選択</label>
            <input type="file" name="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">アップロード</button>
    </form>
    </form>
</div>
@endsection
