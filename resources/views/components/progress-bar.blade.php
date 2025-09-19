@php
$value = $attributes->get('value', 0);
$color = match (true) {
    $value >= 75 => 'bg-green-500',
    $value >= 50 => 'bg-blue-500',
    $value >= 25 => 'bg-yellow-500',
    default => 'bg-gray-500'
};
$size = $attributes->get('size', 'md');
$sizeClasses = match ($size) {
    'sm' => 'h-1',
    'md' => 'h-2',
    'lg' => 'h-3',
    default => 'h-2'
};
@endphp

<div class="w-full bg-gray-200 rounded-full {{ $sizeClasses }}">
    <div
        class="{{ $color }} rounded-full {{ $sizeClasses }}"
        style="width: {{ $value }}%"
        role="progressbar"
        aria-valuenow="{{ $value }}"
        aria-valuemin="0"
        aria-valuemax="100"
    ></div>
</div>

@if($attributes->get('showLabel', false))
    <p class="text-sm text-gray-600 mt-1">{{ $value }}% Complete</p>
@endif