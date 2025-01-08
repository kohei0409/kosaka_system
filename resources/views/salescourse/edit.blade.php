@extends('layouts.app')

@section('content')
<form action="{{ route('salescourses.update', $salesCourse->id) }}" method="POST">
    @csrf
    @method('PUT')

    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
        <tr>
            <th class="px-2">営業コース</th>
            <th class="px-2">担当コード</th>
            <th class="px-2">担当者名</th>
            <th class="px-2">責任者名</th>
            <th class="px-2">部署</th>
            <th class="px-2"></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="text" class="form-control" id="Course" name="Course" value="{{ $salesCourse->Course }}" required>
            </td>
            <td>{{ $salesCourse->ManagerCode }}</td>
            <td>{{ $salesCourse->Manager }}</td>
            <td>
                <select class="form-control" id="ExecutiveOfficer" name="ExecutiveOfficerCode">
                    <option value="">責任者を選択してください</option>
                    @foreach($users as $user)
                        @if($user->Code && $user->role_id < 4)
                            <option value="{{ $user->Code }}" {{ $salesCourse->ExecutiveOfficerCode == $user->Code ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" class="form-control" id="Department" name="Department" value="{{ $salesCourse->Department }}">
            </td>
            <td>
                <button type="submit" class="btn btn-primary">更新する</button>
            </td>
        </tr>
        </tbody>
    </table>

    <input type="hidden" class="form-control" id="ManagerCode" name="ManagerCode" value="{{ $salesCourse->ManagerCode }}">
    <input type="hidden" class="form-control" id="Manager" name="Manager" value="{{ $salesCourse->Manager }}">
</form>
@endsection
