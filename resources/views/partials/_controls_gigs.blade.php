<span class="ml-4">Sort by:&nbsp;</span>
<div class="inline-flex divide-x border border-gigs rounded-lg overflow-hidden mx-4">
    <a href="/?sort=title&search={{ request('search') }}&tags={{ request('tags') }}"
        class="inline-block pt-2 h-10 px-4
      {{ request('sort') === 'title'
          ? 'bg-gigs text-white hover:bg-red-900'
          : 'bg-gray-50 text-black hover:bg-red-50' }}">Title</a>
    <a href="/?sort=company&search={{ request('search') }}&tags={{ request('tags') }}"
        class="inline-block pt-2 h-10 px-4
      {{ request('sort') === 'company'
          ? 'bg-gigs text-white hover:bg-red-900'
          : 'bg-gray-50 text-black hover:bg-red-50' }}">Company</a>
    <a href="/?sort=location&search={{ request('search') }}&tags={{ request('tags') }}"
        class="inline-block pt-2 h-10 px-4
      {{ request('sort') === 'location'
          ? 'bg-gigs text-white hover:bg-red-900'
          : 'bg-gray-50 text-black hover:bg-red-50' }}">Location</a>
</div>
<span class="ml-4">View:&nbsp;</span>
<div class="inline-flex divide-x border border-gigs rounded-lg overflow-hidden mx-4">
    <a id="btnGrid" href="#" class="inline-block pt-2 h-10 px-4">Grid</a>
    <a id="btnList" href="#" class="inline-block pt-2 h-10 px-4">List</a>
</div>
