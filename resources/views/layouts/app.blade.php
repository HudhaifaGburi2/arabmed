<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Arab-Med') }} - @yield('title', 'منصة التعليم الطبي')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
    @stack('styles')
    
    <!-- Page-specific styles -->
    @hasSection('page-styles')
        @yield('page-styles')
    @endif
</head>
<body class="app-layout">
    
    <!-- Main Navigation -->
    <x-navbar 
        :brand="config('app.name', 'Arab-Med')"
        brand-logo="{{ asset('images/logo.png') }}"
        brand-href="{{ route('home') }}"
        :fixed="true"
        color="white"
        :shadow="true">
        
        <x-slot name="navigation">
            @auth
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="material-icons">dashboard</i>
                    لوحة التحكم
                </a>
                <a href="{{ route('courses.index') }}" class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                    <i class="material-icons">school</i>
                    الدورات
                </a>
                <a href="{{ route('videos.index') }}" class="nav-link {{ request()->routeIs('videos.*') ? 'active' : '' }}">
                    <i class="material-icons">play_circle</i>
                    الفيديوهات
                </a>
                <a href="{{ route('exams.index') }}" class="nav-link {{ request()->routeIs('exams.*') ? 'active' : '' }}">
                    <i class="material-icons">quiz</i>
                    الامتحانات
                </a>
            @endauth
        </x-slot>
        
        <x-slot name="search">
            <input type="text" class="search-input" placeholder="البحث..." id="global-search">
            <i class="material-icons search-icon">search</i>
        </x-slot>
        
        <x-slot name="notifications">
            @auth
                <button class="notification-btn" id="notifications-toggle">
                    <i class="material-icons">notifications</i>
                    <span class="notification-badge" id="notification-count" style="display: none;"></span>
                </button>
            @endauth
        </x-slot>
        
        <x-slot name="user">
            @auth
                <button class="user-btn" id="user-menu-toggle">
                    <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="user-avatar">
                    <div class="user-info">
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="user-role">{{ auth()->user()->role_display }}</span>
                    </div>
                    <i class="material-icons">keyboard_arrow_down</i>
                </button>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary">تسجيل الدخول</a>
                <a href="{{ route('register') }}" class="btn btn-primary">إنشاء حساب</a>
            @endauth
        </x-slot>
        
        <x-slot name="mobileNav">
            @auth
                <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="material-icons">dashboard</i>
                    لوحة التحكم
                </a>
                <a href="{{ route('courses.index') }}" class="mobile-nav-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                    <i class="material-icons">school</i>
                    الدورات
                </a>
                <a href="{{ route('videos.index') }}" class="mobile-nav-item {{ request()->routeIs('videos.*') ? 'active' : '' }}">
                    <i class="material-icons">play_circle</i>
                    الفيديوهات
                </a>
                <a href="{{ route('exams.index') }}" class="mobile-nav-item {{ request()->routeIs('exams.*') ? 'active' : '' }}">
                    <i class="material-icons">quiz</i>
                    الامتحانات
                </a>
                <div class="mobile-nav-divider"></div>
                <a href="{{ route('profile') }}" class="mobile-nav-item">
                    <i class="material-icons">person</i>
                    الملف الشخصي
                </a>
                <a href="{{ route('settings') }}" class="mobile-nav-item">
                    <i class="material-icons">settings</i>
                    الإعدادات
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mobile-nav-form">
                    @csrf
                    <button type="submit" class="mobile-nav-item mobile-nav-logout">
                        <i class="material-icons">logout</i>
                        تسجيل الخروج
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="mobile-nav-item">تسجيل الدخول</a>
                <a href="{{ route('register') }}" class="mobile-nav-item">إنشاء حساب</a>
            @endauth
        </x-slot>
    </x-navbar>
    
    <!-- Sidebar (for authenticated users) -->
    @auth
        @if(!request()->routeIs('welcome'))
            <x-sidebar 
                position="right" 
                color="white"
                :collapsed="false">
                
                <x-slot name="header">
                    <div class="sidebar-brand">
                        <img src="{{ asset('images/logo-sm.png') }}" alt="Arab-Med" class="sidebar-logo">
                        <span class="sidebar-brand-text">Arab-Med</span>
                    </div>
                </x-slot>
                
                <x-slot name="menu">
                    <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="material-icons sidebar-nav-icon">dashboard</i>
                        <span class="sidebar-nav-text">لوحة التحكم</span>
                    </a>
                    
                    <button class="sidebar-nav-item {{ request()->routeIs('courses.*') ? 'active expanded' : '' }}" data-toggle="submenu">
                        <i class="material-icons sidebar-nav-icon">school</i>
                        <span class="sidebar-nav-text">الدورات</span>
                        <i class="material-icons sidebar-nav-arrow">chevron_right</i>
                    </button>
                    <div class="sidebar-submenu">
                        <a href="{{ route('courses.index') }}" class="sidebar-submenu-item {{ request()->routeIs('courses.index') ? 'active' : '' }}">
                            جميع الدورات
                        </a>
                        <a href="{{ route('courses.my') }}" class="sidebar-submenu-item {{ request()->routeIs('courses.my') ? 'active' : '' }}">
                            دوراتي
                        </a>
                        @can('create', App\Models\Course::class)
                            <a href="{{ route('courses.create') }}" class="sidebar-submenu-item {{ request()->routeIs('courses.create') ? 'active' : '' }}">
                                إنشاء دورة
                            </a>
                        @endcan
                    </div>
                    
                    <a href="{{ route('videos.index') }}" class="sidebar-nav-item {{ request()->routeIs('videos.*') ? 'active' : '' }}">
                        <i class="material-icons sidebar-nav-icon">play_circle</i>
                        <span class="sidebar-nav-text">الفيديوهات</span>
                    </a>
                    
                    <a href="{{ route('exams.index') }}" class="sidebar-nav-item {{ request()->routeIs('exams.*') ? 'active' : '' }}">
                        <i class="material-icons sidebar-nav-icon">quiz</i>
                        <span class="sidebar-nav-text">الامتحانات</span>
                    </a>
                    
                    <a href="{{ route('progress') }}" class="sidebar-nav-item {{ request()->routeIs('progress') ? 'active' : '' }}">
                        <i class="material-icons sidebar-nav-icon">trending_up</i>
                        <span class="sidebar-nav-text">التقدم</span>
                    </a>
                    
                    @hasrole('admin')
                        <div class="sidebar-divider"></div>
                        <div class="sidebar-section-title">الإدارة</div>
                        
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <i class="material-icons sidebar-nav-icon">admin_panel_settings</i>
                            <span class="sidebar-nav-text">لوحة الإدارة</span>
                        </a>
                        
                        <a href="{{ route('admin.users') }}" class="sidebar-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="material-icons sidebar-nav-icon">people</i>
                            <span class="sidebar-nav-text">المستخدمون</span>
                        </a>
                        
                        <a href="{{ route('admin.analytics') }}" class="sidebar-nav-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                            <i class="material-icons sidebar-nav-icon">analytics</i>
                            <span class="sidebar-nav-text">التحليلات</span>
                        </a>
                    @endhasrole
                </x-slot>
                
                <x-slot name="footer">
                    <div class="sidebar-user-info">
                        <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="sidebar-user-avatar">
                        <div class="sidebar-user-details">
                            <span class="sidebar-user-name">{{ auth()->user()->name }}</span>
                            <span class="sidebar-user-role">{{ auth()->user()->role_display }}</span>
                        </div>
                    </div>
                </x-slot>
            </x-sidebar>
        @endif
    @endauth
    
    <!-- Main Content Area -->
    <main class="main-content {{ auth()->check() && !request()->routeIs('welcome') ? 'with-sidebar' : '' }}">
        
        <!-- Page Header -->
        @hasSection('page-header')
            <div class="page-header">
                <div class="container-fluid">
                    @yield('page-header')
                </div>
            </div>
        @endif
        
        <!-- Breadcrumbs -->
        @hasSection('breadcrumbs')
            <div class="breadcrumbs">
                <div class="container-fluid">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @yield('breadcrumbs')
                        </ol>
                    </nav>
                </div>
            </div>
        @endif
        
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="material-icons">check_circle</i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="material-icons">error</i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="material-icons">warning</i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="material-icons">info</i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
            </div>
        @endif
        
        <!-- Page Content -->
        <div class="page-content">
            @yield('content')
        </div>
        
    </main>
    
    <!-- User Menu Dropdown -->
    @auth
        <div class="dropdown-menu user-dropdown" id="user-dropdown">
            <div class="dropdown-header">
                <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}" 
                     alt="{{ auth()->user()->name }}" 
                     class="dropdown-avatar">
                <div class="dropdown-user-info">
                    <span class="dropdown-user-name">{{ auth()->user()->name }}</span>
                    <span class="dropdown-user-email">{{ auth()->user()->email }}</span>
                </div>
            </div>
            <div class="dropdown-divider"></div>
            <a href="{{ route('profile') }}" class="dropdown-item">
                <i class="material-icons">person</i>
                الملف الشخصي
            </a>
            <a href="{{ route('settings') }}" class="dropdown-item">
                <i class="material-icons">settings</i>
                الإعدادات
            </a>
            <a href="{{ route('help') }}" class="dropdown-item">
                <i class="material-icons">help</i>
                المساعدة
            </a>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item dropdown-logout">
                    <i class="material-icons">logout</i>
                    تسجيل الخروج
                </button>
            </form>
        </div>
    @endauth
    
    <!-- Notifications Dropdown -->
    @auth
        <div class="dropdown-menu notifications-dropdown" id="notifications-dropdown">
            <div class="dropdown-header">
                <span class="dropdown-title">الإشعارات</span>
                <button class="btn btn-sm btn-outline-primary" id="mark-all-read">
                    تحديد الكل كمقروء
                </button>
            </div>
            <div class="notifications-list" id="notifications-list">
                <!-- Notifications will be loaded via JavaScript -->
                <div class="notification-loading">
                    <div class="spinner"></div>
                    <span>جاري تحميل الإشعارات...</span>
                </div>
            </div>
            <div class="dropdown-footer">
                <a href="{{ route('notifications') }}" class="btn btn-sm btn-primary w-100">
                    عرض جميع الإشعارات
                </a>
            </div>
        </div>
    @endauth
    
    <!-- Vue.js App Mount Point -->
    <div id="app"></div>
    
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @stack('scripts')
    
    <!-- Page-specific scripts -->
    @hasSection('page-scripts')
        @yield('page-scripts')
    @endif
    
    <!-- Global JavaScript -->
    <script>
        // Global configuration
        window.ArabMed = {
            csrfToken: '{{ csrf_token() }}',
            user: @auth {!! auth()->user()->toJson() !!} @else null @endauth,
            locale: '{{ app()->getLocale() }}',
            routes: {
                api: '{{ url('/api') }}',
                notifications: '{{ route('api.notifications.index') }}',
                search: '{{ route('api.search') }}'
            }
        };
        
        // Initialize global functionality
        document.addEventListener('DOMContentLoaded', function() {
            // User menu toggle
            const userToggle = document.getElementById('user-menu-toggle');
            const userDropdown = document.getElementById('user-dropdown');
            
            if (userToggle && userDropdown) {
                userToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    userDropdown.classList.toggle('show');
                });
            }
            
            // Notifications toggle
            const notificationsToggle = document.getElementById('notifications-toggle');
            const notificationsDropdown = document.getElementById('notifications-dropdown');
            
            if (notificationsToggle && notificationsDropdown) {
                notificationsToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    notificationsDropdown.classList.toggle('show');
                    
                    // Load notifications if not already loaded
                    if (!notificationsDropdown.dataset.loaded) {
                        loadNotifications();
                        notificationsDropdown.dataset.loaded = 'true';
                    }
                });
            }
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.user-btn') && userDropdown) {
                    userDropdown.classList.remove('show');
                }
                if (!e.target.closest('.notification-btn') && notificationsDropdown) {
                    notificationsDropdown.classList.remove('show');
                }
            });
            
            // Global search
            const globalSearch = document.getElementById('global-search');
            if (globalSearch) {
                let searchTimeout;
                globalSearch.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        if (this.value.length >= 3) {
                            performGlobalSearch(this.value);
                        }
                    }, 300);
                });
            }
        });
        
        // Load notifications function
        function loadNotifications() {
            const notificationsList = document.getElementById('notifications-list');
            if (!notificationsList) return;
            
            fetch(window.ArabMed.routes.notifications, {
                headers: {
                    'X-CSRF-TOKEN': window.ArabMed.csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                notificationsList.innerHTML = '';
                
                if (data.data && data.data.length > 0) {
                    data.data.forEach(notification => {
                        const notificationElement = createNotificationElement(notification);
                        notificationsList.appendChild(notificationElement);
                    });
                } else {
                    notificationsList.innerHTML = '<div class="no-notifications">لا توجد إشعارات جديدة</div>';
                }
                
                // Update notification count
                const notificationCount = document.getElementById('notification-count');
                if (notificationCount) {
                    const unreadCount = data.unread_count || 0;
                    if (unreadCount > 0) {
                        notificationCount.textContent = unreadCount > 99 ? '99+' : unreadCount;
                        notificationCount.style.display = 'block';
                    } else {
                        notificationCount.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                notificationsList.innerHTML = '<div class="notification-error">خطأ في تحميل الإشعارات</div>';
            });
        }
        
        // Create notification element
        function createNotificationElement(notification) {
            const div = document.createElement('div');
            div.className = `notification-item ${notification.read_at ? '' : 'unread'}`;
            div.innerHTML = `
                <div class="notification-content">
                    <div class="notification-title">${notification.data.title}</div>
                    <div class="notification-message">${notification.data.message}</div>
                    <div class="notification-time">${formatNotificationTime(notification.created_at)}</div>
                </div>
                ${!notification.read_at ? '<div class="notification-unread-indicator"></div>' : ''}
            `;
            
            // Add click handler to mark as read
            if (!notification.read_at) {
                div.addEventListener('click', function() {
                    markNotificationAsRead(notification.id);
                    div.classList.remove('unread');
                });
            }
            
            return div;
        }
        
        // Format notification time
        function formatNotificationTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffInMinutes = Math.floor((now - date) / (1000 * 60));
            
            if (diffInMinutes < 1) return 'الآن';
            if (diffInMinutes < 60) return `منذ ${diffInMinutes} دقيقة`;
            if (diffInMinutes < 1440) return `منذ ${Math.floor(diffInMinutes / 60)} ساعة`;
            return `منذ ${Math.floor(diffInMinutes / 1440)} يوم`;
        }
        
        // Mark notification as read
        function markNotificationAsRead(notificationId) {
            fetch(`${window.ArabMed.routes.notifications}/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.ArabMed.csrfToken,
                    'Accept': 'application/json'
                }
            });
        }
        
        // Global search function
        function performGlobalSearch(query) {
            // Implementation for global search
            console.log('Searching for:', query);
        }
    </script>
    
</body>
</html>
