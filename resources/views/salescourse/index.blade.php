@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">営業コース</h1>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
            <tr>

                <th class="px-2">営業コース</th>
                <th class="px-2">担当コード</th>
                <th class="px-2">担当者名</th>
                <th class="px-2">責任者コード</th>
                <th class="px-2">責任者者名</th>
                <th class="px-2">部署</th>
                <th class="px-2"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($salesCourses as $course)
            <tr>

                <td>{{ $course->Course }}</td>
                <td>{{ $course->ManagerCode }}</td>
                <td>{{ $course->Manager }}</td>
                <td>{{ $course->ExecutiveOfficerCode }}</td>
                <td>{{ $course->ExecutiveOfficer }}</td>
                <td>{{ $course->Department }}</td>
                <td>
                    <a href="{{ route('salescourses.edit', $course->id) }}" class="btn btn-warning btn-sm me-2">編集する</a>
                    <form action="{{ route('salescourses.destroy', $course->id) }}" method="POST"
                          style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm me-2"
                                onclick="return confirm('Are you sure you want to delete this course?');">
                            削除する
                        </button>
                    </form>

                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('salescourses.create') }}" class="btn btn-primary mb-3">コースを追加する</a>
</div>
@endsection
