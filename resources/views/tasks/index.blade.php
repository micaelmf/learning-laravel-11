@extends('layout')

@section('title', 'Minhas tarefas')

@section('content')
    <div class="submenu">
        <div class="submenu-left">
            <h2>Lista</h2>
        </div>
        <div class="submenu-right">
            <form action="{{ route('tasks.index') }}" method="GET" class="filter-form">
                <input type="text" name="query" placeholder="Buscar tarefas..." value="{{ request('query') }}">
                <button type="submit">Buscar</button>
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
                    <span class="task-date">{{ $task->due_date }}</span>
                    <span class="task-status">{{ $task->status }}</span>
                </div>
                <div class="task-actions">
                    <button type="button" class="btn btn-danger delete"><i class="uil uil-trash"></i></button>
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-secondary edit">
                        <i class="uil uil-pen"></i>
                    </a>
                    <button type="button" class="btn btn-primary complete"><i class="uil uil-check"></i></button>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        $(document).ready(function() {
            $('.btn.delete').on('click', function() {
                const $button = $(this);
                const $icon = $button.find('i');
                const taskId = $(this).closest('.task').attr('id');
                const data = {
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '/tasks/' + taskId,
                    type: 'DELETE',
                    data: data,
                    beforeSend: function() {
                        $button.find('i').remove();
                        $button.attr('disabled', true);
                        $button.append('<i class="uil uil-spinner"></i>');
                    },
                    success: function(response) {
                        const $task = $button.closest('.task');
                        $task.hide(300, function() {
                            $task.remove();
                        });
                    },
                    complete: function() {
                        $button.attr('disabled', false);
                        $button.find('.uil-spinner').remove();
                        $button.append($icon);
                    },
                    error: function() {
                        console.log('error');
                        $('#task-' + taskId + ' .task-status').text('error');
                    }
                });
            });

            $('.btn.complete').on('click', function() {
                const $button = $(this);
                const $icon = $button.find('i');
                const taskId = $(this).closest('.task').attr('id');
                const data = {
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: `/tasks/${taskId}/complete`,
                    type: 'PUT',
                    data: data,
                    beforeSend: function() {
                        $button.find('i').remove();
                        $button.attr('disabled', true);
                        $button.append('<i class="uil uil-spinner"></i>');
                    },
                    success: function(response) {
                        $button.closest('.task').find('.task-status').text('completed');
                    },
                    complete: function() {
                        $button.attr('disabled', false);
                        $button.find('.uil-spinner').remove();
                        $button.append($icon);
                    },
                    error: function() {
                        console.log('error');
                        $('#task-' + taskId + ' .task-status').text('error');
                    }
                });
            });
        });
    </script>
@endsection
