@props([
    'href' => null,
    'icon' => 'fas fa-chart-simple',
    'value' => null,
    'label' => '',
    'iconOnly' => false,
    'delay' => 0,
])

@php
    $tag = $href ? 'a' : 'div';
    $attributes = $href ? $attributes->merge(['href' => $href]) : $attributes;
    $animationStyle = $delay ? "animation-delay: {$delay}s;" : '';
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => 'stat-card', 'style' => $animationStyle]) }}>
    <div class="stat-card__icon">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="stat-card__content">
        @if(!$iconOnly)
            <div class="stat-card__value">{{ $value ?? '—' }}</div>
        @endif
        <div class="stat-card__label">{{ $label }}</div>
    </div>
</{{ $tag }}>