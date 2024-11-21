<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Confirm Payment') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10">
        <div class="bg-white shadow-md rounded p-6">
            <h3 class="text-lg font-medium text-gray-900">Confirm Payment for Task</h3>
            <p><strong>Title:</strong> {{ $task->title }}</p>
            <p><strong>Description:</strong> {{ $task->description }}</p>
            <p><strong>Priority:</strong> {{ $task->priority }}</p>
            <p><strong>Due Date:</strong> {{ $task->due_date }}</p>

            <form action="{{ route('tasks.pay.confirm', $task->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success mt-4">Confirm Payment</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary mt-4">Cancel</a>
            </form>
        </div>
    </div>
</x-app-layout>
