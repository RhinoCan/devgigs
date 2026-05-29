<x-layout>

    @include('partials._hero_gigs')
    @include('partials._search_gigs')
    @include('partials._controls_gigs')

    <div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">

        @unless (count($gigs) == 0)
          <div id="gridView">
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
    <div class="mt-6 p-4">
        {{ $gigs->appends(['search' => request('search'), 'tags' => request('tags'), 'sort' => request('sort')])->links() }}
    </div>

    <x-fab bgColor='bg-gigs' />
    <x-footer />
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
                gridView.style.display = 'block';
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
                gridView.style.display = 'block';
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
