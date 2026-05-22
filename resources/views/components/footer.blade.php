@props([
    'bgColor' => 'bg-gray-800',
    'buttonText' => null,
    'buttonHref' => null,
    'showButton' => false,
])

<footer
    class="fixed bottom-0 left-0 w-full flex items-center justify-start font-bold {{ $bgColor }} text-black h-12 mt-24 opacity-90 md:justify-center">
    <p class="ml-2">Copyright &copy; {{ date('Y') }}. All Rights reserved.</p>
    @if ($showButton)
        <a href="{{ $buttonHref }}"
            class="absolute top-1/2 -translate-y-1/2 right-10 bg-black text-white py-2 px-5">{{ $buttonText }}</a>
    @endif
</footer>
