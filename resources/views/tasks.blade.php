@extends('layouts.app')

@section('content')
<h1 class="mb-4">{{ __('tasks.task_manager') }}</h1>

<!-- Task Form -->
<form id="taskForm" class="mb-4" action="{{ route('tasks.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="title" class="form-label">{{__("tasks.title")}}</label>
        <input type="text" id="title" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">{{__("tasks.description")}}</label>
        <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
    </div>
    <div class="mb-3">
        <label for="due_date" class="form-label">{{__("tasks.due_date")}}</label>
        <input type="date" id="due_date" name="due_date" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-warning text-white">{{__("tasks.add_new_task")}}</button>
</form>

<!-- Task Table -->
<table id="taskTable" class="table table-bordered ">
    <thead>
        <tr>
            <th>{{__("tasks.title")}}</th>
            <th>{{__("tasks.description")}}</th>
            <th>{{__("tasks.status")}}</th>
            <th>{{__("tasks.due_date")}}</th>
            <th>{{__("tasks.actions")}}</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
@endsection