@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Upload CSV</h1>
    <form action="{{ route('salescourses.upload.post') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Choose CSV File</label>
            <input type="file" class="form-control" id="file" name="file" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
@endsection
