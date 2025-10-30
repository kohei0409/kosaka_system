@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-6 text-stat"><h1 class="">営業コース</h1></div>
        <div class="col-6 text-end"><a href="{{ route('salescourses.index') }}"
                                       class="btn btn-sm btn-success">一覧に戻る</a></div>
    </div>

    <div class="row">
        <div class="col-12 mt-4  p-3">
            <form action="{{ route('salescourses.upload.post') }}" method="POST" enctype="multipart/form-data">
                @csrf


                <div class="my-3">
                    <label for="file" class="form-label mb-3">CSVファイルを選択してアップロードしてください</label>
                    <input type="file" class="form-control" id="file" name="file" required>
                </div>
                <button type="submit" class="btn btn-primary">アップロードする</button>
            </form>
        </div>
    </div>
</div>
@endsection
