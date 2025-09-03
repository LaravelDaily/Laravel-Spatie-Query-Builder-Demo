@props(['field', 'currentSort' => null])

@php
    $isActive = str_starts_with($currentSort, $field) || str_starts_with($currentSort, '-' . $field);
    $isDesc = str_starts_with($currentSort, '-' . $field);
    $nextSort = $isActive && !$isDesc ? '-' . $field : $field;
@endphp

<a href="{{ request()->fullUrlWithQuery(['sort' => $nextSort]) }}" 
   class="group inline-flex items-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
    {{ $slot }}
    <span class="ml-2 flex-none rounded text-gray-400 group-hover:text-gray-500">
        @if($isActive)
            @if($isDesc)
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            @else
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            @endif
        @else
            <svg class="h-4 w-4 opacity-0 group-hover:opacity-100" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        @endif
    </span>
</a>
