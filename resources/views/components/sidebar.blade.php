@props([
    'collapsed' => false,
    'overlay' => false,
    'position' => 'right', // right for RTL, left for LTR
    'color' => 'dark',
    'class' => ''
])

@php
    $sidebarClasses = [
        'sidebar',
        'sidebar-' . $position,
        'sidebar-' . $color,
        $collapsed ? 'sidebar-collapsed' : '',
        $overlay ? 'sidebar-overlay' : '',
        $class
    ];
@endphp

<aside class="{{ implode(' ', array_filter($sidebarClasses)) }}" id="sidebar">
    <!-- Sidebar Header -->
    @isset($header)
        <div class="sidebar-header">
            {{ $header }}
        </div>
    @endisset

    <!-- Sidebar Body -->
    <div class="sidebar-body">
        @isset($menu)
            <nav class="sidebar-nav">
                {{ $menu }}
            </nav>
        @else
            {{ $slot }}
        @endisset
    </div>

    <!-- Sidebar Footer -->
    @isset($footer)
        <div class="sidebar-footer">
            {{ $footer }}
        </div>
    @endisset

    <!-- Collapse Toggle -->
    <button class="sidebar-toggle" type="button" data-toggle="sidebar-collapse">
        <i class="material-icons">chevron_left</i>
    </button>
</aside>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-backdrop" data-dismiss="sidebar"></div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.querySelector('[data-toggle="sidebar-collapse"]');
    const backdrop = document.querySelector('.sidebar-backdrop');
    
    // Toggle sidebar collapse
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-collapsed');
            
            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
        });
    }
    
    // Close sidebar on backdrop click (mobile)
    if (backdrop) {
        backdrop.addEventListener('click', function() {
            sidebar.classList.remove('sidebar-open');
        });
    }
    
    // Restore sidebar state
    const savedState = localStorage.getItem('sidebar-collapsed');
    if (savedState === 'true') {
        sidebar.classList.add('sidebar-collapsed');
    }
    
    // Handle mobile sidebar
    const mobileToggle = document.querySelector('[data-toggle="sidebar-mobile"]');
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-open');
        });
    }
});
</script>
@endpush
