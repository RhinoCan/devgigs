@props(['tagsCsv'])

@php
  $tags = array_map('trim', explode(',', $tagsCsv))
@endphp

<ul class="flex">
  @foreach($tags as $tag)
  <li class="flex items-center justify-center bg-black text-white rounded-xl px-3 py-1 mr-2 ext-xs">
    <a href="/?tag={{ $tag }}">{{ $tag }}</a>
  </li>
  @endforeach
</ul>
