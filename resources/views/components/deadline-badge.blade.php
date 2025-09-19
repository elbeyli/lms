@php
$deadline = $attributes->get('deadline');
$showIcon = $attributes->get('showIcon', true);
$size = $attributes->get('size', 'md');

if (!$deadline) return;

$deadline = Carbon\Carbon::parse($deadline);
$daysLeft = $deadline->diffInDays(now(), false);

$color = match (true) {
    $daysLeft < 0 => 'text-red-500',
    $daysLeft <= 3 => 'text-orange-500',
    $daysLeft <= 7 => 'text-yellow-500',
    default => 'text-green-500'
};

$iconSize = match ($size) {
    'sm' => 'w-4 h-4',
    'md' => 'w-5 h-5',
    'lg' => 'w-6 h-6',
    default => 'w-5 h-5'
};

$text = match (true) {
    $daysLeft < 0 => 'Overdue by ' . abs($daysLeft) . ' ' . str('day')->plural(abs($daysLeft)),
    $daysLeft === 0 => 'Due today',
    $daysLeft === 1 => 'Due tomorrow',
    default => 'Due in ' . $daysLeft . ' ' . str('day')->plural($daysLeft)
};
@endphp

<div class="inline-flex items-center gap-1.5 {{ $color }}">
    @if($showIcon)
        <svg class="{{ $iconSize }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
    @endif
    <span class="text-sm font-medium">{{ $text }}</span>
</div>