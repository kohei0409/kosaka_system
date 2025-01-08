@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Sales Course</h1>
    <form action="{{ route('salescourses.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="Course" class="form-label">Course</label>
            <input type="text" class="form-control" id="Course" name="Course" required>
        </div>
        <div class="mb-3">
            <label for="Manager" class="form-label">Manager</label>
            <select class="form-control" id="Manager" name="ManagerCode">
                <option value="">Select Manager</option>
                @foreach($users as $user)
                @if($user->Code && $user->role_id < 4)
                <option value="{{ $user->Code }}">{{ $user->name }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="ExecutiveOfficer" class="form-label">Executive Officer</label>
            <select class="form-control" id="ExecutiveOfficer" name="ExecutiveOfficerCode">
                <option value="">Select Executive Officer</option>
                @foreach($managers as $manager)
                @if($manager->Code && $manager->role_id < 4)
                <option value="{{ $manager->Code }}">{{ $manager->name }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="Department" class="form-label">Department</label>
            <input type="text" class="form-control" id="Department" name="Department">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>

</div>
@endsection
