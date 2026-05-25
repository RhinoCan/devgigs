<x-layout>
    @include('partials._search_gigs')
    <a href="/" class="inline-block text-black ml-4 mb-4"><i class="fa-solid fa-arrow-left"></i> Back
    </a>
    <div class="mx-4">
        <x-card class="p10">
            <div class="flex flex-col items-center justify-center text-center">
                <img class="w-48 mr-6 mb-6" src="{{ $gig->logo ?? asset('/images/No_Image_Available.jpg') }}"
                    alt="logo" />

                <h3 class="text-2xl mb-2">{{ $gig->title }}</h3>
                <div class="text-xl font-bold mb-4">{{ $gig->company }}</div>
                <x-gig-tags :tagsCsv="$gig->tags" />
                <div class="text-lg my-4">
                    <i class="fa-solid fa-location-dot"></i> {{ $gig->location }}
                </div>
                <div class="border border-gray-200 w-full mb-6"></div>
                <div>
                    <h3 class="text-3xl font-bold mb-4">
                        Gig Description
                    </h3>
                    <div class="text-lg space-y-6">
                        <p>
                            {{ $gig->description }}
                        </p>


                        <a href="mailto:{{ $gig->email }}"
                            class="block bg-gigs text-white mt-6 py-2 rounded-xl hover:opacity-80"><i
                                class="fa-solid fa-envelope"></i>
                            Contact Employer</a>

                        <a href="{{ $gig->website }}" target="_blank"
                            class="block bg-black text-white py-2 rounded-xl hover:opacity-80"><i
                                class="fa-solid fa-globe"></i> Visit
                            Website</a>
                    </div>
                </div>
            </div>
        </x-card>
        @auth
            @if (auth()->id() === $gig->user_id)
                {
                <x-card class="mt-4 p-2 flex space-x-6">
                    <a href="/{{ $gig->id }}/edit?source=show">
                        <i class="fa-solid fa-pencil"></i> Edit
                    </a>
                    <a href="/{{ $gig->id }}/delete-confirm?source=show" class="text-red-600">
                        <i class="fa-solid fa-trash-can"></i> Delete
                    </a>
                </x-card>
                }
            @endif
        @endauth
    </div>
    <x-fab bgColor='bg-gigs' />

</x-layout>
