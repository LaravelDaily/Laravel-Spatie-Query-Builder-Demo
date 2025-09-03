@props(['filters' => []])

@if(count($filters) > 0)
<div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-700">Active filters:</span>
            @foreach($filters as $label => $value)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    {{ $label }}: {{ $value }}
                    <a href="{{ request()->fullUrlWithQuery([str_replace(' ', '_', strtolower($label)) => null]) }}" 
                       class="ml-1.5 text-indigo-600 hover:text-indigo-500">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </span>
            @endforeach
        </div>
        <a href="{{ url()->current() }}" class="text-sm text-gray-500 hover:text-gray-700">
            Clear all filters
        </a>
    </div>
</div>
@endif
