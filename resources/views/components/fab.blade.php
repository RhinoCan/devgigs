@props([
    'bgColor' => 'bg-gray-800',
])

@auth
    <a href="/create"
        class="fixed bottom-6 right-6 {{ $bgColor }} text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg hover:bg-black text-2xl z-50">
        <i class="fa-solid fa-plus"></i>
    </a>
@endauth
