@if (session()->has('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
        class="fixed top-0 left-0 right-0 text-black bg-yellow-300 text-center py-3">
        <p>{{ session('message') }}</p>
    </div>
@endif
