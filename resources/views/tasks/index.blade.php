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

    <button type="button" class="btn btn-primary" id="liveToastBtn">Show live toast</button>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="..." class="rounded me-2" alt="...">
                <strong class="me-auto">Bootstrap</strong>
                <small>11 mins ago</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Hello, world! This is a toast message.
            </div>
        </div>
    </div>

    @foreach ($tasks ?? [] as $task)
        <div class="card mb-3">
            <div class="card-header bg-transparent">
                <small>#{{ $task->id }}</small>
                <span class="task-date">{{ $task->due_date }}</span>
                <span class="task-status">{{ $task->status }}</span>
            </div>
            <div class="card-body">
                <h4 class="card-title">{{ $task->name }}</h4>
                <p class="card-text">{{ $task->description }}</p>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-danger delete"><i class="uil uil-trash"></i></button>
                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-secondary edit">
                    <i class="uil uil-pen"></i>
                </a>
                <button type="button" class="btn btn-primary complete">
                    <i class="uil uil-check"></i>
                </button>
            </div>
        </div>
    @endforeach

    <div>{{ $tasks->links() }}</div>

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
        <div id="toastWait" class="toast hide align-items-center text-white bg-primary border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">Aguarde...</div>
            </div>
        </div>

        <div id="toastSuccess" class="toast hide align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">Sucesso!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastTrigger = document.getElementById('liveToastBtn');
            var toastLiveExample = document.getElementById('liveToast');

            if (toastTrigger) {
                toastTrigger.addEventListener('click', function() {
                    var toast = new bootstrap.Toast(toastLiveExample);
                    toast.show();
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            const toastWait = new bootstrap.Toast(document.getElementById('toastWait'));
            const toastSuccess = new bootstrap.Toast(document.getElementById('toastSuccess'));

            $('.delete').on('click', function(e) {
                e.preventDefault();

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
                        toastWait.show();
                        $button.find('i').remove();
                        $button.attr('disabled', true);
                        $button.append('<i class="uil uil-spinner"></i>');
                    },
                    success: function(response) {
                        const $task = $button.closest('.task');
                        $task.hide(300, function() {
                            $task.remove();
                            toastWait.hide();
                            toastSuccess.show();
                        });
                    },
                    complete: function() {
                        $button.attr('disabled', false);
                        $button.find('.uil-spinner').remove();
                        $button.append($icon);
                        toastWait.hide();
                        toastSuccess.hide();
                    },
                    error: function() {

                        console.log('error');
                        $('#task-' + taskId + ' .task-status').text('error');
                    }
                });
            });

            $('.complete').on('click', function() {
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
                        toastWait.show();
                        $button.find('i').remove();
                        $button.attr('disabled', true);
                        $button.append('<i class="uil uil-spinner"></i>');
                    },
                    success: function(response) {
                        $button.closest('.task').find('.task-status').text('completed');
                        toastWait.hide();
                        toastSuccess.show();
                    },
                    complete: function() {
                        $button.attr('disabled', false);
                        $button.find('.uil-spinner').remove();
                        $button.append($icon);
                        toastWait.hide();
                        toastSuccess.hide();
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
