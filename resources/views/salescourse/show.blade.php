@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sales Course Details</h1>
    <table class="table">
        <tr>
            <th>Course</th>
            <td>{{ $salesCourse->Course }}</td>
        </tr>
        <tr>
            <th>Manager</th>
            <td>{{ $salesCourse->Manager }}</td>
        </tr>
        <tr>
            <th>Executive Officer</th>
            <td>{{ $salesCourse->ExecutiveOfficer }}</td>
        </tr>
        <tr>
            <th>Department</th>
            <td>{{ $salesCourse->Department }}</td>
        </tr>
    </table>
    <a href="{{ route('salescourses.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
