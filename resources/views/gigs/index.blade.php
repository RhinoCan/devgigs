<x-layout>

    @include('partials._hero_gigs')
    @include('partials._search_gigs')
    @include('partials._controls_gigs')

    <div class="mx-4">
        @unless (count($gigs) == 0)
          <div id="gridView" class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0">
            @foreach ($gigs as $gig)
              <x-gig-card :gig="$gig" />
            @endforeach
          </div>
          <div id="listView" style="display: none">
            @foreach ($gigs as $gig)
              <div class="bg-gray-50 border border-gray-200 rounded p-6">
                @include('partials._gigs_list_item')
              </div>
            @endforeach
          </div>
        @else
            <p>No gigs found</p>
        @endunless
    </div>
    <div class="flex items-center justify-between mt-6 p-4">
        <div class="flex items-center gap-2">
            <span class="mr-2 text-sm">Per page:</span>
            <div class="inline-flex divide-x border border-gray-300 rounded-lg overflow-hidden">
                <a href="?perPage=10&sort={{ request('sort') }}&search={{ request('search') }}"
                    class="inline-block pt-2 h-10 px-4 {{ request('perPage', 20) == 10 ? 'bg-gray-800 text-white' : 'bg-white text-black hover:bg-gray-100' }}">10</a>
                <a href="?perPage=20&sort={{ request('sort') }}&search={{ request('search') }}"
                    class="inline-block pt-2 h-10 px-4 {{ request('perPage', 20) == 20 ? 'bg-gray-800 text-white' : 'bg-white text-black hover:bg-gray-100' }}">20</a>
                <a href="?perPage=50&sort={{ request('sort') }}&search={{ request('search') }}"
                    class="inline-block pt-2 h-10 px-4 {{ request('perPage', 20) == 50 ? 'bg-gray-800 text-white' : 'bg-white text-black hover:bg-gray-100' }}">50</a>
                <a href="?perPage=100&sort={{ request('sort') }}&search={{ request('search') }}"
                    class="inline-block pt-2 h-10 px-4 {{ request('perPage', 20) == 100 ? 'bg-gray-800 text-white' : 'bg-white text-black hover:bg-gray-100' }}">100</a>
            </div>
        </div>
        @if ($gigs->total() > $gigs->perPage())
        <div>
            {{ $gigs->appends(['search' => request('search'), 'sort' => request('sort'), 'perPage' => request('perPage', 20)])->links() }}
        </div>
        @endif
    </div>
    <x-footer />
    <x-fab bgColor='bg-gigs' />

    <script>
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        const btnGrid = document.getElementById('btnGrid');
        btnGrid.addEventListener('click', function() {
            setView('grid');
        });
        const btnList = document.getElementById('btnList');
        btnList.addEventListener('click', function() {
            setView('list');
        });
        const savedView = localStorage.getItem('gigViewPreference') || 'grid';

        function setView(view) {
            if (view === "grid") {
                gridView.style.display = '';
                listView.style.display = 'none';
                btnGrid.classList.add('bg-gigs', 'text-white');
                btnGrid.classList.remove('bg-gray-50', 'text-black');
                btnList.classList.remove('bg-gigs', 'text-white');
                btnList.classList.add('bg-gray-50', 'text-black');
            } else if (view === "list") {
                gridView.style.display = 'none';
                listView.style.display = 'block';
                btnGrid.classList.remove('bg-gigs', 'text-white');
                btnGrid.classList.add('bg-gray-50', 'text-black')
                btnList.classList.add('bg-gigs', 'text-white');
                btnList.classList.remove('bg-gray-50', 'text-black');
            } else {
                gridView.style.display = '';
                listView.style.display = 'none';
                btnGrid.classList.add('bg-gigs', 'text-white');
                btnList.classList.remove('bg-gigs', 'text-white');
                localStorage.setItem('gigViewPreference', 'grid');
            }
            localStorage.setItem('gigViewPreference', view);
        }
        setView(savedView);
    </script>

</x-layout>
