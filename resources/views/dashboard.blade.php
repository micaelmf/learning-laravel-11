<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 p-4">
        <div>
            <h2 class="text-xl mb-4 font-semibold text-gray-800 dark:text-gray-200">Today's tasks</h2>
            <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-lg border border-yellow-500">
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center justify-between w-full">
                            <i
                                class="uil uil-clock text-3xl text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-600">
                            </i>
                            <div class="ml-4 text-right">
                                <p class="text-g">Pending</p>
                                <p class="text-3xl text-gray-500 dark:text-gray-300">{{ $countTasksToday['pending'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-700 rounded-b-lg p-3">
                        <a href="{{ route('tasks.index', ['status' => 'pending']) }}"
                            class="text-sm font-bold text-blue-400 hover:underline">
                            View all
                        </a>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-lg border border-sky-500">
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center justify-between w-full">
                            <i
                                class="uil uil-play text-3xl text-sky-600 hover:text-sky-900 dark:text-sky-400 dark:hover:text-sky-600">
                            </i>
                            <div class="ml-4 text-right">
                                <p class="text-g">Doing</p>
                                <p class="text-3xl text-gray-500 dark:text-gray-300">{{ $countTasksToday['doing'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-700 rounded-b-lg p-3">
                        <a href="{{ route('tasks.index', ['status' => 'doing']) }}"
                            class="text-sm font-bold text-blue-400 hover:underline">
                            View all
                        </a>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-lg border border-green-500">
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center justify-between w-full">
                            <i
                                class="uil uil-check-circle text-3xl text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-600">
                            </i>
                            <div class="ml-4 text-right">
                                <p class="text-g">Completed</p>
                                <p class="text-3xl text-gray-500 dark:text-gray-300">{{ $countTasksToday['completed'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-700 rounded-b-lg p-3">
                        <a href="{{ route('tasks.index', ['status' => 'completed']) }}"
                            class="text-sm font-bold text-blue-400 hover:underline">
                            View all
                        </a>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-lg border border-gray-500">
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center justify-between w-full">
                            <i
                                class="uil uil-clipboard-notes text-3xl text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-600">
                            </i>
                            <div class="ml-4 text-right">
                                <p class="text-g">Total</p>
                                <p class="text-3xl text-gray-500 dark:text-gray-300">
                                    {{ $countTasksToday['allExceptArchived'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-700 rounded-b-lg p-3">
                        <a href="{{ route('tasks.index') }}" class="text-sm font-bold text-blue-400 hover:underline">
                            View all
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="mt-8 pb-12">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Today's reminders</h2>
            @if ($remindersToday->isEmpty())
                <p class="text-gray-500 dark:text-gray-300">No reminders for today</p>
            @endif
            @foreach ($remindersToday as $reminder)
                <div class="mt-4 reminder" data-id="{{ $reminder->id }}" data-status="visualized">
                    <div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-lg">
                        <div class="p-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="uil uil-bell text-2xl text-gray-600 dark:text-gray-400"></i>
                                    <p class="ml-4 text-gray-500 dark:text-gray-300">{{ $reminder->task->name }}</p>
                                </div>
                                <p class="text-gray-500 dark:text-gray-300">
                                    {{ (new DateTimeImmutable($reminder->reminder_time))->format('H:i') }}
                                    <button
                                        class="change-status text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-green-600 p-2 text-lg"
                                        data-status="visualized" title="Mark as visualized">
                                        <i class="uil uil-eye"></i>
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('.change-status').on('click', function(e) {
                e.preventDefault();

                const $button = $(this);
                const $icon = $button.find('i');
                const reminderId = $(this).closest('.reminder').data('id');
                const newStatus = $(this).data('status');
                const data = {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                };

                console.log(reminderId);
                $.ajax({
                    url: `/reminders/${reminderId}/status`,
                    type: 'PUT',
                    data: data,
                    beforeSend: function() {
                        showToast('Wait...', 'bg-sky-500');
                        $button.find('i').remove();
                        $button.attr('disabled', true);
                        $button.append('<i class="uil uil-spinner"></i>');
                    },
                    success: function(response) {
                        const $reminder = $button.closest('.reminder');
                        $reminder.hide(300, function() {
                            $reminder.remove();
                            showToast('Success!', 'bg-green-500');
                        });
                    },
                    complete: function() {
                        $button.find('i').remove();
                        $button.attr('disabled', false);
                        $button.append($icon);
                    },
                    error: function(error) {
                        console.log(error);
                        showToast('Error!', 'bg-red-500');
                    }
                });
            });
        });

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
