<x-layout>
    <x-card>
        <div class="text-center">
            <h1 class="text-4xl font-bold text-red-500">500</h1>
            <h2 class="text-xl mt-4">Access Denied</h2>
            <p class="mt-2 text-gray-600">
                {{ $exception->getMessage() ?: 'You do not have permission to perform this action.' }}
            </p>
            <a href="/" class="mt-6 inline-block text-blue-500 hover:underline">Go back home</a>
        </div>
    </x-card>
</x-layout>