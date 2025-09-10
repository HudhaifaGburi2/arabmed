@props([
    'title' => '',
    'subtitle' => '',
    'icon' => '',
    'color' => 'white',
    'shadow' => 'md',
    'padding' => 'default',
    'header' => true,
    'footer' => false,
    'hover' => false,
    'clickable' => false,
    'href' => '',
    'class' => ''
])

@php
    $cardClasses = [
        'card',
        'card-' . $color,
        'shadow-' . $shadow,
        'padding-' . $padding,
        $hover ? 'card-hover' : '',
        $clickable ? 'card-clickable' : '',
        $class
    ];
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ implode(' ', array_filter($cardClasses)) }}">
@else
    <div class="{{ implode(' ', array_filter($cardClasses)) }}">
@endif

    @if($header && ($title || $subtitle || $icon || isset($headerActions)))
        <div class="card-header">
            <div class="card-header-content">
                @if($icon)
                    <div class="card-icon">
                        <i class="material-icons">{{ $icon }}</i>
                    </div>
                @endif
                
                @if($title || $subtitle)
                    <div class="card-title-section">
                        @if($title)
                            <h3 class="card-title">{{ $title }}</h3>
                        @endif
                        @if($subtitle)
                            <p class="card-subtitle">{{ $subtitle }}</p>
                        @endif
                    </div>
                @endif
            </div>
            
            @isset($headerActions)
                <div class="card-header-actions">
                    {{ $headerActions }}
                </div>
            @endisset
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>

    @if($footer || isset($footerActions))
        <div class="card-footer">
            @isset($footerActions)
                {{ $footerActions }}
            @else
                {{ $footer }}
            @endisset
        </div>
    @endif

@if($href)
    </a>
@else
    </div>
@endif
