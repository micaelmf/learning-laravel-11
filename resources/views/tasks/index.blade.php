<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div class="flex justify-between items-center mb-4 ">
                    <form action="" method="GET" class="flex items-center gap-2">
                        <input type="text" name="term" placeholder="Search tasks..."
                            class="form-input rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <select name="status"
                            class="form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="doing">Doing</option>
                            <option value="completed">Completed</option>
                            <option value="archived">Archived</option>
                        </select>
                        <button type="button"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 disabled:opacity-25 transition"
                            onclick="window.location.href = '{{ route('tasks.index') }}'">Clear</button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">Search</button>
                    </form>
                    <a href="{{ route('tasks.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 disabled:opacity-25 transition">Add
                        Task</a>
                </div>
                <ul role="list" class="divide-y divide-gray-500">
                    @if (count($tasks) <= 0)
                        <div class="flex justify-center items-center py-6">
                            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded"
                                role="alert">
                                <p class="font-bold">No tasks found</p>
                                <p>There are currently no tasks to display. Please add a new task.</p>
                            </div>
                        </div>
                    @else
                        @foreach ($tasks ?? [] as $task)
                            <?php
                            $task->due_date = (new DateTimeImmutable($task->due_date))->format('d/m/Y');
                            $textDecoration = $task->deleted_at ? 'line-through' : '';
                            ?>

                            <li id="{{ $task->id }}"
                                class="task flex flex-col sm:flex-row justify-between gap-x-6 py-5">
                                <div class="flex-grow min-w-0 gap-x-4">
                                    <div class="min-w-0 flex-auto">
                                        <p class="text-md font-semibold leading-6 text-gray-300">{{ $task->name }}</p>

                                        @if ($task->description)
                                            <p class="mt-1 truncate text-sm leading-5 text-gray-400">
                                                {{ $task->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-shrink-0 sm:flex sm:flex-col sm:items-end mt-4 sm:mt-0">
                                    <span
                                        class="task-status inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-black {{ $textDecoration }}">
                                        {{ $task->status }}
                                    </span>
                                    <p class="text-sm font-semibold leading-6 text-gray-300">{{ $task->due_date }}</p>
                                </div>
                                <div class="flex-shrink-0 flex items-center mt-4 sm:mt-0">
                                    <div class="flex items-center space-x-2">
                                        <button
                                            class="change-status text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-green-600 p-2"
                                            data-status="completed">
                                            <i class="uil uil-check-circle"></i>
                                        </button>
                                        <div class="relative">
                                            <button
                                                class="dropdown-toggle text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-600 p-2">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <div
                                                class="dropdown-menu absolute hidden shadow-lg rounded-md mt-2 right-0 w-48 bg-white dark:bg-gray-800 z-50">
                                                <a href="#" data-status="pending"
                                                    class="change-status flex gap-3 block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                                    <i
                                                        class="uil uil-clock text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-600"></i>
                                                    <span>Mark as Pending</span>
                                                </a>
                                                <a href="#" data-status="doing"
                                                    class="change-status flex gap-3 block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                                    <i
                                                        class="uil uil-play text-sky-600 hover:text-sky-900 dark:text-sky-400 dark:hover:text-sky-600"></i>
                                                    <span>Mark as Doing</span>
                                                </a>
                                                <a href="#" data-status="completed"
                                                    class="change-status flex gap-3 block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                                    <i
                                                        class="uil uil-check-circle text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-600"></i>
                                                    <span>Mark as Completed</span>
                                                </a>
                                                <a href="#" data-status="archived"
                                                    class="change-status flex gap-3 block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                                    <i
                                                        class="uil uil-archive text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-600"></i>
                                                    <span>Mark as Archived</span>
                                                </a>

                                                <div class="border-t border-gray-200 dark:border-gray-700"></div>

                                                <a href="{{ route('tasks.edit', $task->id) }}"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                                    <i class="uil uil-edit mr-2"></i> Edit
                                                </a>

                                                <a href="#"
                                                    class="delete block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:text-red-400 dark:hover:bg-gray-700">
                                                    <i class="uil uil-trash mr-2"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
                <div>{{ $tasks->links() }}</div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            handleBadges();
        });

        // Dropdown visibility toggle
        $(document).on('click', function(e) {
            const $dropdowns = $('.dropdown-menu');
            const $toggles = $('.dropdown-toggle');

            $toggles.each(function() {
                const $toggle = $(this);
                const $dropdown = $toggle.next('.dropdown-menu');

                if ($toggle.is(e.target) || $toggle.has(e.target).length) {
                    $dropdown.toggleClass('hidden');
                } else if (!$dropdown.is(e.target) && $dropdown.has(e.target).length === 0) {
                    $dropdown.addClass('hidden');
                }
            });
        });

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
                    showToast('Wait...', 'bg-sky-500');
                    $button.find('i').remove();
                    $button.attr('disabled', true);
                    $button.prepend('<i class="uil uil-spinner"></i>');
                },
                success: function(response) {
                    const $task = $button.closest('.task');
                    $task.hide(300, function() {
                        $task.remove();
                        showToast('Success!', 'bg-green-500');
                    });
                },
                complete: function() {
                    $button.attr('disabled', false);
                    $button.find('.uil-spinner').remove();
                    $button.prepend($icon);
                },
                error: function() {
                    console.log('error');
                    showToast('Error!', 'bg-red-500');
                }
            });
        });

        $('.change-status').on('click', function(e) {
            e.preventDefault();

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
                    showToast('Wait...', 'bg-sky-500');
                    $button.find('i').remove();
                    $button.attr('disabled', true);
                    $button.prepend('<i class="uil uil-spinner"></i>');
                },
                success: function(response) {
                    const $badge = $button.closest('.task').find('.task-status');
                    $badge.text(newStatus);
                    $badge.removeClass(
                        'line-through text-black text-white text-gray-100 bg-gray-900 bg-gray-500 bg-yellow-500 bg-sky-500 bg-green-500 '
                    );
                    $badge.addClass(`${badgeStyle(newStatus)}`);
                    showToast('Success!', 'bg-green-500');
                },
                complete: function() {
                    $button.attr('disabled', false);
                    $button.find('.uil-spinner').remove();
                    $button.prepend($icon);
                },
                error: function() {
                    console.log('error');
                    showToast('Error!', 'bg-red-500');
                }
            });
        });

        function handleBadges() {
            $('.task-status').each(function() {
                const status = $(this).text();
                $(this).addClass(badgeStyle(status));
            });
        }

        function badgeStyle(status) {
            let style = '';
            status = status.toLowerCase().trim();

            if (status === 'completed') {
                style = 'bg-green-500 text-white';
            } else if (status === 'doing') {
                style = 'bg-sky-500 text-white';
            } else if (status === 'pending') {
                style = 'bg-yellow-500 text-black';
            } else if (status === 'archived') {
                style = 'bg-gray-500 text-white';
            } else {
                style = 'bg-black text-white';
            }

            return style;
        }

        function showToast(message, bgColor) {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 p-4 rounded shadow-lg text-white ${bgColor}`;
            toast.innerText = message;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
</x-app-layout>
