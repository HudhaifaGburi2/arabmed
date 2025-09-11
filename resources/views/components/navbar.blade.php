@props([
    'brand' => 'Arab-Med',
    'brandLogo' => '',
    'brandHref' => '/',
    'fixed' => true,
    'transparent' => false,
    'color' => 'white',
    'shadow' => true,
    'class' => '',
    'autoSolidOnScroll' => true,
])

@php
    $navbarClasses = [
        'navbar',
        $fixed ? 'navbar-fixed' : '',
        $transparent ? 'navbar-transparent' : 'navbar-' . $color,
        $shadow ? 'navbar-shadow' : '',
        $class
    ];
@endphp

<nav class="{{ implode(' ', array_filter($navbarClasses)) }}">
    <div class="navbar-container">
        <!-- Brand Section -->
        <div class="navbar-brand">
            <a href="{{ $brandHref }}" class="brand-link">
                @if($brandLogo)
                    <img src="{{ $brandLogo }}" alt="{{ $brand }}" class="brand-logo">
                @endif
                <span class="brand-text">{{ $brand }}</span>
            </a>
        </div>

        <!-- Navigation Links -->
        @isset($navigation)
            <div class="navbar-nav">
                {{ $navigation }}
            </div>
        @endisset

        <!-- Right Side Actions -->
        <div class="navbar-actions">
            @isset($search)
                <div class="navbar-search">
                    {{ $search }}
                </div>
            @endisset

            @isset($notifications)
                <div class="navbar-notifications">
                    {{ $notifications }}
                </div>
            @endisset

            @isset($user)
                <div class="navbar-user">
                    {{ $user }}
                </div>
            @endisset

            @isset($actions)
                <div class="navbar-custom-actions">
                    {{ $actions }}
                </div>
            @endisset

            <!-- Mobile Menu Toggle -->
            <button class="navbar-toggle" type="button" data-toggle="navbar-mobile">
                <span class="navbar-toggle-icon"></span>
                <span class="navbar-toggle-icon"></span>
                <span class="navbar-toggle-icon"></span>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation -->
    @isset($mobileNav)
        <div class="navbar-mobile" id="navbar-mobile">
            {{ $mobileNav }}
        </div>
    @endisset
</nav>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile navbar toggle
    const toggleBtn = document.querySelector('[data-toggle="navbar-mobile"]');
    const mobileNav = document.getElementById('navbar-mobile');
    const navbar = document.querySelector('nav.navbar');
    
    if (toggleBtn && mobileNav) {
        toggleBtn.addEventListener('click', function() {
            mobileNav.classList.toggle('active');
            toggleBtn.classList.toggle('active');
        });
    }
    
    // Close mobile nav when clicking outside
    document.addEventListener('click', function(e) {
        if (mobileNav && !e.target.closest('.navbar')) {
            mobileNav.classList.remove('active');
            if (toggleBtn) toggleBtn.classList.remove('active');
        }
    });

    // Solid on scroll when transparent
    try {
        const autoSolid = @json($autoSolidOnScroll);
        if (autoSolid && navbar && navbar.classList.contains('navbar-transparent')) {
            const onScroll = () => {
                if (window.scrollY > 20) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            };
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        }
    } catch (e) { /* no-op */ }
});
</script>
@endpush
