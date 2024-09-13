@extends('layout')

@section('title', 'Minhas tarefas')

@section('content')
    <div class="submenu">
        <div class="submenu-left ">
            <h2>Edit task</h2>
        </div>
    </div>
    <!-- Exibir mensagens de erro -->
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <form class="task-form" action="{{ route('tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')
            <fieldset>
                <div class="row">
                    <div class="col mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Name"
                            value="{{ $task->name }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5">{{ $task->description }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="doing" {{ $task->status == 'doing' ? 'selected' : '' }}>Doing</option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                        </select>
                    </div>
                    <div class="col mb-3">
                        <label for="date" class="form-label">Due date
                        </label>
                        <input type="date" id="due_date" name="due_date" class="form-control"
                            value="{{ (new DateTimeImmutable($task->due_date))->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <a class="btn btn-secondary" href="{{ route('tasks.index') }}">Back</a>
                    </div>
                    <div class="col text-end">
                        <button type="reset" class="btn btn-danger">Reset</button>
                        <button type="submit" id="create" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>


@endsection
