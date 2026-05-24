<x-layout>
    <x-card class="p-10 max-w-lg mx-auto mt-24">
        <header class="text-center mb-8">
            <h2 class="text-2xl font-bold uppercase mb-1 text-red-600">Confirm Delete</h2>
            <p class="text-gray-600">Are you sure you want to delete this gig posting? This cannot be undone.</p>
        </header>

        {{-- Gig details --}}
        <div class="mb-8">
            @if ($gig->logo)
                <img src="{{ $gig->logo }}" alt="logo" class="w-24 mx-auto mb-4" />
            @endif
            <table class="w-full text-sm">
                <tr class="border-b">
                    <td class="py-2 font-bold w-1/3">Title</td>
                    <td class="py-2">{{ $gig->title }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-bold">Company</td>
                    <td class="py-2">{{ $gig->company }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-bold">Location</td>
                    <td class="py-2">{{ $gig->location }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-bold">Email</td>
                    <td class="py-2">{{ $gig->email }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-bold">Website</td>
                    <td class="py-2">{{ $gig->website }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-bold">Tags</td>
                    <td class="py-2">{{ $gig->tags }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Description</td>
                    <td class="py-2">{{ $gig->description }}</td>
                </tr>
            </table>
        </div>

        {{-- Action buttons --}}
        <div class="flex justify-between">
            <a href="/manage" class="bg-gray-200 text-gray-800 rounded py-2 px-4 hover:bg-gray-300">
                Cancel
            </a>
            <form method="POST" action="/{{ $gig->id }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="source" value="{{ request('source') }}">
                <button class="bg-red-600 text-white rounded py-2 px-4 hover:bg-black">
                    <i class="fa-solid fa-trash-can"></i> Confirm Delete
                </button>
            </form>
        </div>
    </x-card>
    <x-footer bgColor='bg-gigs' buttonText="Post a gig" buttonHref="/create" :showButton="true" />
</x-layout>
