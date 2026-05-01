<div class="{{ $class ?? '' }}" style="position: relative; width: {{ $size }}px; height: {{ $size }}px;">
    @if($food && $food->image_path)
        @if(str_starts_with($food->image_path, 'http'))
            <img src="{{ $food->image_path }}" 
                 alt="{{ $food->name ?? 'Food' }}" 
                 class="absolute inset-0 w-full h-full object-cover rounded">
        @else
            <img src="{{ asset('storage/'.$food->image_path) }}" 
                 alt="{{ $food->name ?? 'Food' }}" 
                 class="absolute inset-0 w-full h-full object-cover rounded">
        @endif
    @else
        <div class="absolute inset-0 w-full h-full flex items-center justify-center bg-gray-200 rounded">
            <i class="fas fa-utensils text-gray-400"></i>
        </div>
    @endif
</div>