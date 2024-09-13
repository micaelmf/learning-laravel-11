@extends('layout')

@section('title', 'Minhas tarefas')

@section('content')
    <div class="submenu">
        <div class="submenu-left">
            <h2>Tasks list</h2>
        </div>
        <div class="submenu-right">
            <form action="{{ route('tasks.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="term" class="form-control" placeholder="Search"
                        value="{{ request('term') }}">
                    <select name="status" class="form-select" id="inputGroupSelect04" aria-label="Select state">
                        <option value="">All</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="doing" {{ request('status') == 'doing' ? 'selected' : '' }}>Doing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived
                        </option>
                        <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                    <button class="btn btn-outline-primary" type="submit" title="Search" aria-label="Search"><i
                            class="uil uil-search"></i></button>
                </div>
            </form>
            <a class="btn btn-outline-secondary" href="{{ route('tasks.index') }}" title="Clear search"
                aria-label="Clear search">
                <i class="uil uil-filter-slash"></i>
            </a>
            <a class="btn btn-success" href="{{ route('tasks.create') }}" title="Add task" aria-label="Add task">
                <i class="uil uil-plus text-center"></i>
            </a>
        </div>
    </div>

    @if (count($tasks) <= 0)
        <div class="alert alert-warning" role="alert">
            No tasks found.
        </div>
    @else
        @foreach ($tasks ?? [] as $task)
            <?php
            $task->due_date = (new DateTimeImmutable($task->due_date))->format('d/m/Y');
            $textDecoration = '';

            if ($task->deleted_at) {
                $textDecoration = 'text-decoration-line-through text-muted';
            }
            ?>
            <div class="card task mb-3 {{ $textDecoration }}" id="{{ $task->id }}">
                <div class="card-header text-center d-flex bd-highlight gap-3 align-items-center">
                    <small>#{{ $task->id }}</small>
                    <span class="me-auto task-date">{{ $task->due_date }}</span>
                    <span class="task-status badge rounded-pill">{{ $task->status }}</span>
                    <!-- Example split danger button -->

                    @if (!$task->deleted_at)
                        <div class="btn-group">
                            <button type="button" title="Complete task" aria-label="Complete task"
                                class="btn btn-outline-success btn-sm change-status" data-status="completed">
                                <i class="uil uil-check"></i>
                            </button>
                            <button type="button"
                                class="btn btn-outline-success btn-sm dropdown-toggle dropdown-toggle-split"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button type="button" class="dropdown-item change-status" data-status="pending">
                                        <span class="text-warning">■</span>
                                        Pending
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item change-status" data-status="doing">
                                        <span class="text-primary">■</span>
                                        Doing
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item change-status" data-status="completed">
                                        <span class="text-success">■</span>
                                        Completed
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item change-status" data-status="archived">
                                        <span class="text-secondary">■</span>
                                        Archived
                                    </button>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item delete">
                                        <span class="uil uil-trash-alt"></span>
                                        Delete
                                    </button>
                                </li>
                                <li>
                                    <a class="dropdown-item edit" href="{{ route('tasks.edit', $task->id) }}">
                                        <i class="uil uil-edit"></i>
                                        Edit
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <h4 class="card-title">{{ $task->name }}</h4>
                    <p class="card-text">{{ $task->description }}</p>
                </div>
            </div>
        @endforeach
    @endif

    <div>{{ $tasks->links() }}</div>

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
        <div id="toastWait" class="toast hide align-items-center text-white bg-primary border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">Wait...</div>
            </div>
        </div>

        <div id="toastSuccess" class="toast hide align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">Success!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        // Inicializar o tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
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

            $('.change-status').on('click', function() {
                const $button = $(this);
                const $icon = $button.find('i');
                const taskId = $(this).closest('.task').attr('id');
                const newStatus = $button.data('status');
                const data = {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                };

                $.ajax({
                    url: `/tasks/${taskId}/status`,
                    type: 'PUT',
                    data: data,
                    beforeSend: function() {
                        toastWait.show();
                        $button.find('i').remove();
                        $button.attr('disabled', true);
                        $button.append('<i class="uil uil-spinner"></i>');
                    },
                    success: function(response) {
                        const $badge = $button.closest('.task').find('.task-status');
                        $badge.text(newStatus);
                        $badge.removeClass(
                            'bg-success bg-primary bg-warning bg-secondary bg-dark text-white text-dark'
                        );
                        $badge.addClass(`${badgeStyle(newStatus)}`);
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

            handleBadges();
        });

        function handleBadges() {
            $('.task-status').each(function() {
                const status = $(this).text();
                $(this).addClass(badgeStyle(status));
            });
        }

        function badgeStyle(status) {
            let style = '';

            if (status === 'completed') {
                style = 'bg-success text-white';
            } else if (status === 'doing') {
                style = 'bg-primary text-white';
            } else if (status === 'pending') {
                style = 'bg-warning text-dark';
            } else if (status === 'archived') {
                style = 'bg-secondary text-white';
            } else {
                style = 'bg-dark text-white';
            }

            return style;
        }
    </script>
@endsection
