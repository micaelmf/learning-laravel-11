<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                            {{ isset($task) ? "Edit Task #$task->id" : 'Create Task' }}</h2>
                    </div>

                    <!-- Exibir mensagens de erro -->
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600 dark:text-red-400">
                                {{ __('Whoops! Something went wrong.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600 dark:text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <?php
                    $task = $task ?? new stdClass();

                    if ($task->id ?? '') {
                        $route = route('tasks.update', $task->id ?? '');
                        $method = 'PUT';

                        $task->due_date = (new DateTimeImmutable($task->due_date))->format('Y-m-d');
                    } else {
                        $route = route('tasks.store');
                        $method = 'POST';
                    }
                    ?>

                    <form action="{{ $route }}" method="POST">
                        @csrf
                        @if ($method === 'PUT')
                            @method('PUT')
                        @endif
                        <div class="grid grid-cols-1 gap-6 mb-4">
                            <div>
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" id="name" name="name"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300"
                                    placeholder="Name" value="{{ old('name', $task->name ?? '') }}">
                            </div>
                            <div>
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300"
                                    id="description" name="description" rows="5">{{ old('description', $task->description ?? '') }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="status"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                    <select id="status" name="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300">
                                        <option value="pending"
                                            {{ old('status', $task->status ?? '' == 'pending' ? 'selected' : '') }}>
                                            Pending
                                        </option>
                                        <option value="doing"
                                            {{ old('status', $task->status ?? '' == 'doing' ? 'selected' : '') }}>Doing
                                        </option>
                                        <option value="completed"
                                            {{ old('status', $task->status ?? '' == 'completed' ? 'selected' : '') }}>
                                            Completed</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="due_date"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due
                                        date</label>
                                    <input type="date" id="due_date" name="due_date"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300"
                                        value="{{ old('due_date', $task->due_date ?? date('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Reminders</h2>
                            <button type="button" id="add_reminder"
                                class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 dark:hover:bg-green-400 active:bg-green-700 dark:active:bg-green-600 focus:outline-none focus:border-green-700 focus:ring ring-green-300 dark:focus:ring-green-600 disabled:opacity-25 transition ease-in-out duration-150">
                                Add Reminder
                            </button>
                        </div>
                        @include('tasks.reminder')

                        <div class="mt-6 flex justify-between">
                            <a class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 active:bg-gray-900 dark:active:bg-gray-800 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 dark:focus:ring-gray-600 disabled:opacity-25 transition ease-in-out duration-150"
                                href="{{ route('tasks.index') }}">Back</a>
                            <div>
                                <button type="reset"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 dark:hover:bg-red-400 active:bg-red-700 dark:active:bg-red-600 focus:outline-none focus:border-red-700 focus:ring ring-red-300 dark:focus:ring-red-600 disabled:opacity-25 transition ease-in-out duration-150">Reset</button>
                                <button type="submit"
                                    class="ml-3 inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 dark:hover:bg-indigo-400 active:bg-indigo-700 dark:active:bg-indigo-600 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 dark:focus:ring-indigo-600 disabled:opacity-25 transition ease-in-out duration-150">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
