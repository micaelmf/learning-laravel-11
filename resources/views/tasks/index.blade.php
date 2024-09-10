@extends('layout')

@section('title', 'Minhas tarefas')

@section('content')
    <div class="submenu">
        <div class="submenu-left">
            <h2>Lista</h2>
        </div>
        <div class="submenu-right">
            <form class="filter-form" action="" method="GET">
                <input type="text" name="search" placeholder="Buscar">
                <button class="btn btn-outline-primary" type="submit"><i class="uil uil-search"></i></button>
            </form>
            <a class="btn btn-primary" href="{{ route('tasks.create') }}"><i class="uil uil-plus text-center"></i></a>
        </div>
    </div>

    <div class="task-list">
        @foreach ($tasks ?? [] as $task)
            <div class="task" id="{{ $task->id }}">
                <div class="task-info">
                    <span class="task-id">{{ $task->id }}</span>
                    <span class="task-name">{{ $task->name }}</span>
                    <span class="task-date">{{ $task->date }}</span>
                    <span class="task-status">{{ $task->status }}</span>
                </div>
                <div class="task-actions">
                    <button type="button" href="{{ route() }}" class="btn btn-danger"><i
                            class="uil uil-trash"></i></button>
                    <a href="{{ route('tasks.edit') }}" class="btn btn-secondary"><i class="uil uil-pen"></i></a>
                    <button type="button" class="btn btn-primary"><i class="uil uil-check"></i></button>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        $(document).ready(function() {
            $('.complete-task').on('click', function() {
                let taskId = $(this).data('id');
                const data = {
                    _token: '{{ csrf_token() }}',
                    status: 'completed'
                };

                $.ajax({
                    url: '/tasks/' + taskId + '/edit',
                    type: 'PATCH',
                    data: data,
                    beforeSend: function() {
                        $('#task-' + taskId + ' .task-status').text('loading...');
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#task-' + taskId + ' .task-status').text('completed');
                            alert(response.message);
                        }
                    },
                    afterSend: function() {
                        $('#task-' + taskId + ' .task-status').text('completed');
                    },
                    error: function() {
                        $('#task-' + taskId + ' .task-status').text('error');
                    }
                });
            });
        });
    </script>
@endsection
