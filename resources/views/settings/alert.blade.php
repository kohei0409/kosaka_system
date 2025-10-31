@extends('layouts.app')

@section('title', 'アラート機能')

@section('content')

<div class="container">
    <h1>アラート設定</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('alert.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="alert_title" class="form-label">タイトル</label>
            <input type="text" class="form-control" name="alert_title" id="alert_title"
                   value="{{ old('alert_title', $setting->alert_title ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="alert_message" class="form-label">メッセージ</label>
            <textarea class="form-control" name="alert_message" id="alert_message" rows="4">{{ old('alert_message', $setting->alert_message ?? '') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">保存</button>
    </form>
</div>
@endsection
