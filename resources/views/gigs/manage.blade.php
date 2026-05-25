<x-layout>
    <x-card class="p-10 rounded">
        <header>
            <h1 class="text-3xl text-center font-bold my-6 uppercase">
                Manage Gigs - <a href="/" class="bg-gigs text-white rounded py-2 px-4 hover:bg-black">View All
                    Gigs</a>
            </h1>
        </header>

        <table class="w-full table-auto rounded-sm">
            <tbody>
                @unless ($gigs->isEmpty())
                    @foreach ($gigs as $gig)
                        <tr class="border-gray-300">
                            <td class="px-4 py-8 border-t border-b border-gray-300 text-lg">
                                {{ $gig->title }} - {{ $gig->company }}
                            </td>
                            <td class="px-4 py-8 border-t border-b border-gray-300 text-lg">
                                <a href="/{{ $gig->id }}/edit?source=manage" class="text-blue-400 px-6 py-2 rounded-xl"><i
                                        class="fa-solid fa-pen-to-square"></i>
                                    Edit</a>
                            </td>
                            <td class="px-4 py-8 border-t border-b border-gray-300 text-lg">
                                <a href="/{{ $gig->id }}/delete-confirm?source=manage" class="text-red-600">
                                    <i class="fa-solid fa-trash-can"></i> Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="border-gray-300">
                        <td class="px-4 py-8 border-t border-b border-gray-300 text-lg">
                            <p class="text-center">No gigs found</p>
                        </td>
                    </tr>
                @endunless
            </tbody>
        </table>
    </x-card>
    <x-fab bgColor='bg-gigs' />
</x-layout>
