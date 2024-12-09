@extends('layouts.app')

@section('content')
<div class="text-center mt-10">
    <h1 class="text-2xl font-bold text-red-600">Unauthorized</h1>
    <p class="mt-4 text-lg">You do not have permission to access this page.</p>
    <a href="{{ url('/') }}" class="text-blue-500 underline mt-6">Go Back to Home</a>
</div>
@endsection
