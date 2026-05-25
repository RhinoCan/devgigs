<x-layout>

    @include('partials._hero_gigs')
    @include('partials._search_gigs')

    <div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">

        @unless (count($gigs) == 0)
            @foreach ($gigs as $gig)
                <x-gig-card :gig="$gig" />
            @endforeach
        @else
            <p>No gigs found</p>
        @endunless
    </div>
    <div class="mt-6 p-4">
        {{ $gigs->links() }}
    </div>

    @auth
        <a href="/create"
            class="fixed bottom-6 right-6 bg-gigs text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg hover:bg-black text-2xl z-50">
            <i class="fa-solid fa-plus"></i>
        </a>
    @endauth

    <x-fab bgColor='bg-gigs' />
    <x-footer />

</x-layout>
