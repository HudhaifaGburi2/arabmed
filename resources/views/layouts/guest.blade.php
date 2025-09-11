<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Arab-Med') }} - @yield('title', 'منصة التعليم الطبي')</title>
    <meta name="description" content="@yield('description', 'منصة Arab-Med للتعليم الطبي - دورات طبية متخصصة وشهادات معتمدة')">
    <meta name="keywords" content="@yield('keywords', 'تعليم طبي, دورات طبية, شهادات معتمدة, طب, تدريب طبي')">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ config('app.name', 'Arab-Med') }} - @yield('title', 'منصة التعليم الطبي')">
    <meta property="og:description" content="@yield('description', 'منصة Arab-Med للتعليم الطبي - دورات طبية متخصصة وشهادات معتمدة')">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ config('app.name', 'Arab-Med') }} - @yield('title', 'منصة التعليم الطبي')">
    <meta name="twitter:description" content="@yield('description', 'منصة Arab-Med للتعليم الطبي - دورات طبية متخصصة وشهادات معتمدة')">
    <meta name="twitter:image" content="{{ asset('images/og-image.jpg') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
    @stack('styles')
    
    <!-- Page-specific styles -->
    @hasSection('page-styles')
        @yield('page-styles')
    @endif
    
    <!-- Schema.org structured data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "Arab-Med",
        "description": "منصة تعليم الطب العربي",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "sameAs": [
            "https://facebook.com/arabmed",
            "https://twitter.com/arabmed",
            "https://linkedin.com/company/arabmed"
        ]
    }
    </script>
</head>
<body class="guest-layout">
    
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="sr-only sr-only-focusable">تخطي إلى المحتوى الرئيسي</a>
    
    <!-- Top Navigation Bar -->
    <x-navbar 
        :brand="config('app.name', 'Arab-Med')"
        brand-logo="{{ asset('images/logo.png') }}"
        brand-href="{{ route('welcome') }}"
        :fixed="true"
        color="white"
        :shadow="true"
        :transparent="request()->routeIs('welcome')">
        
        <x-slot name="navigation">
            <a href="{{ route('welcome') }}" class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}">
                <i class="material-icons">home</i>
                الرئيسية
            </a>
            <a href="{{ route('courses.public') }}" class="nav-link {{ request()->routeIs('courses.public') ? 'active' : '' }}">
                <i class="material-icons">school</i>
                الدورات
            </a>
            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                <i class="material-icons">info</i>
                من نحن
            </a>
            <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                <i class="material-icons">contact_mail</i>
                اتصل بنا
            </a>
        </x-slot>
        
        <x-slot name="search">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="البحث في الدورات..." id="global-search">
                <i class="material-icons search-icon">search</i>
                <div class="search-results" id="search-results"></div>
            </div>
        </x-slot>
        
        <x-slot name="user">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                    <i class="material-icons">dashboard</i>
                    لوحة التحكم
                </a>
                <div class="user-menu">
                    <button class="user-btn" id="user-menu-toggle">
                        <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="user-avatar">
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <i class="material-icons">keyboard_arrow_down</i>
                    </button>
                    
                    <div class="user-dropdown" id="user-dropdown">
                        <a href="{{ route('profile') }}" class="dropdown-item">
                            <i class="material-icons">person</i>
                            الملف الشخصي
                        </a>
                        <a href="{{ route('settings') }}" class="dropdown-item">
                            <i class="material-icons">settings</i>
                            الإعدادات
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
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                    <i class="material-icons">login</i>
                    تسجيل الدخول
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary">
                    <i class="material-icons">person_add</i>
                    إنشاء حساب
                </a>
            @endauth
        </x-slot>
        
        <x-slot name="mobileNav">
            <a href="{{ route('welcome') }}" class="mobile-nav-item {{ request()->routeIs('welcome') ? 'active' : '' }}">
                <i class="material-icons">home</i>
                الرئيسية
            </a>
            <a href="{{ route('courses.public') }}" class="mobile-nav-item {{ request()->routeIs('courses.public') ? 'active' : '' }}">
                <i class="material-icons">school</i>
                الدورات
            </a>
            <a href="{{ route('about') }}" class="mobile-nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                <i class="material-icons">info</i>
                من نحن
            </a>
            <a href="{{ route('contact') }}" class="mobile-nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                <i class="material-icons">contact_mail</i>
                اتصل بنا
            </a>
            
            @auth
                <div class="mobile-nav-divider"></div>
                <a href="{{ route('dashboard') }}" class="mobile-nav-item">
                    <i class="material-icons">dashboard</i>
                    لوحة التحكم
                </a>
                <a href="{{ route('profile') }}" class="mobile-nav-item">
                    <i class="material-icons">person</i>
                    الملف الشخصي
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mobile-nav-form">
                    @csrf
                    <button type="submit" class="mobile-nav-item mobile-nav-logout">
                        <i class="material-icons">logout</i>
                        تسجيل الخروج
                    </button>
                </form>
            @else
                <div class="mobile-nav-divider"></div>
                <a href="{{ route('login') }}" class="mobile-nav-item">
                    <i class="material-icons">login</i>
                    تسجيل الدخول
                </a>
                <a href="{{ route('register') }}" class="mobile-nav-item">
                    <i class="material-icons">person_add</i>
                    إنشاء حساب
                </a>
            @endauth
        </x-slot>
    </x-navbar>
    
    <!-- Main Content -->
    <main id="main-content" class="main-content">
        
        <!-- Page Header -->
        @hasSection('page-header')
            <section class="page-header">
                <div class="container">
                    @yield('page-header')
                </div>
            </section>
        @endif
        
        <!-- Breadcrumbs -->
        @hasSection('breadcrumbs')
            <nav class="breadcrumbs" aria-label="breadcrumb">
                <div class="container">
                    <ol class="breadcrumb">
                        @yield('breadcrumbs')
                    </ol>
                </div>
            </nav>
        @endif
        
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="container">
                    <i class="material-icons">check_circle</i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="container">
                    <i class="material-icons">error</i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="container">
                    <i class="material-icons">warning</i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <div class="container">
                    <i class="material-icons">info</i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            </div>
        @endif
        
        <!-- Page Content -->
        @yield('content')
        
    </main>
    
    <!-- Footer -->
    <footer class="guest-footer">
        <div class="footer-main">
            <div class="container">
                <div class="footer-content">
                    
                    <!-- Company Info -->
                    <div class="footer-section">
                        <div class="footer-brand">
                            <img src="{{ asset('images/logo-white.png') }}" alt="Arab-Med" class="footer-logo">
                            <h3 class="footer-brand-name">Arab-Med</h3>
                        </div>
                        <p class="footer-description">
                            منصة التعليم الطبي الرائدة في العالم العربي. نقدم دورات طبية متخصصة وشهادات معتمدة لتطوير المهارات الطبية.
                        </p>
                        <div class="footer-social">
                            <a href="#" class="social-link" aria-label="فيسبوك">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="تويتر">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="لينكد إن">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="يوتيوب">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="إنستغرام">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="footer-section">
                        <h4 class="footer-title">روابط سريعة</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('welcome') }}">الرئيسية</a></li>
                            <li><a href="{{ route('courses.public') }}">الدورات</a></li>
                            <li><a href="{{ route('about') }}">من نحن</a></li>
                            <li><a href="{{ route('contact') }}">اتصل بنا</a></li>
                            <li><a href="{{ route('blog') }}">المدونة</a></li>
                            <li><a href="{{ route('careers') }}">الوظائف</a></li>
                        </ul>
                    </div>
                    
                    <!-- Categories -->
                    <div class="footer-section">
                        <h4 class="footer-title">التخصصات</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('courses.category', 'cardiology') }}">أمراض القلب</a></li>
                            <li><a href="{{ route('courses.category', 'neurology') }}">الأعصاب</a></li>
                            <li><a href="{{ route('courses.category', 'surgery') }}">الجراحة</a></li>
                            <li><a href="{{ route('courses.category', 'pediatrics') }}">طب الأطفال</a></li>
                            <li><a href="{{ route('courses.category', 'radiology') }}">الأشعة</a></li>
                            <li><a href="{{ route('courses.category', 'emergency') }}">الطوارئ</a></li>
                        </ul>
                    </div>
                    
                    <!-- Support -->
                    <div class="footer-section">
                        <h4 class="footer-title">الدعم</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('help') }}">مركز المساعدة</a></li>
                            <li><a href="{{ route('faq') }}">الأسئلة الشائعة</a></li>
                            <li><a href="{{ route('support') }}">الدعم الفني</a></li>
                            <li><a href="{{ route('privacy') }}">سياسة الخصوصية</a></li>
                            <li><a href="{{ route('terms') }}">شروط الاستخدام</a></li>
                            <li><a href="{{ route('refund') }}">سياسة الاسترداد</a></li>
                        </ul>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="footer-section">
                        <h4 class="footer-title">تواصل معنا</h4>
                        <div class="footer-contact">
                            <div class="contact-item">
                                <i class="material-icons">location_on</i>
                                <span>الرياض، المملكة العربية السعودية</span>
                            </div>
                            <div class="contact-item">
                                <i class="material-icons">phone</i>
                                <a href="tel:+966123456789">+966 12 345 6789</a>
                            </div>
                            <div class="contact-item">
                                <i class="material-icons">email</i>
                                <a href="mailto:info@arabmed.com">info@arabmed.com</a>
                            </div>
                        </div>
                        
                        <!-- Newsletter -->
                        <div class="footer-newsletter">
                            <h5>اشترك في النشرة الإخبارية</h5>
                            <form class="newsletter-form" id="newsletter-form">
                                @csrf
                                <div class="newsletter-input-group">
                                    <input type="email" name="email" placeholder="البريد الإلكتروني" required>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="material-icons">send</i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-content">
                    <div class="footer-copyright">
                        <p>&copy; {{ date('Y') }} Arab-Med. جميع الحقوق محفوظة.</p>
                    </div>
                    <div class="footer-certifications">
                        <img src="{{ asset('images/cert-iso.png') }}" alt="ISO Certified" class="cert-badge">
                        <img src="{{ asset('images/cert-medical.png') }}" alt="Medical Certification" class="cert-badge">
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top Button -->
    <button class="back-to-top" id="back-to-top" aria-label="العودة إلى الأعلى">
        <i class="material-icons">keyboard_arrow_up</i>
    </button>
    
    <!-- Cookie Consent Banner -->
    @if(!request()->cookie('cookie_consent'))
        <div class="cookie-banner" id="cookie-banner">
            <div class="cookie-content">
                <div class="cookie-text">
                    <i class="material-icons">cookie</i>
                    <span>نستخدم ملفات تعريف الارتباط لتحسين تجربتك على موقعنا.</span>
                </div>
                <div class="cookie-actions">
                    <button class="btn btn-outline-primary btn-sm" id="cookie-settings">الإعدادات</button>
                    <button class="btn btn-primary btn-sm" id="cookie-accept">موافق</button>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Language Switcher -->
    <div class="language-switcher" id="language-switcher">
        <button class="language-btn" id="language-toggle">
            <i class="material-icons">language</i>
            <span>{{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}</span>
            <i class="material-icons">keyboard_arrow_down</i>
        </button>
        
        <div class="language-dropdown" id="language-dropdown">
            <a href="{{ route('locale.switch', 'ar') }}" class="language-option {{ app()->getLocale() === 'ar' ? 'active' : '' }}">
                <span class="language-flag">🇸🇦</span>
                <span>العربية</span>
            </a>
            <a href="{{ route('locale.switch', 'en') }}" class="language-option {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                <span class="language-flag">🇺🇸</span>
                <span>English</span>
            </a>
        </div>
    </div>
    
    <!-- Scripts -->
    @vite(['resources/js/app.js', 'resources/js/layouts/guest.js'])
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
                home: '{{ route('welcome') }}',
                search: '{{ route('search') }}',
                newsletter: '{{ route('newsletter.subscribe') }}',
                contact: '{{ route('contact.store') }}'
            }
        };
        
        // Initialize guest functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Back to top button
            const backToTop = document.getElementById('back-to-top');
            if (backToTop) {
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        backToTop.classList.add('show');
                    } else {
                        backToTop.classList.remove('show');
                    }
                });
                
                backToTop.addEventListener('click', function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
            
            // Cookie consent
            const cookieBanner = document.getElementById('cookie-banner');
            const cookieAccept = document.getElementById('cookie-accept');
            const cookieSettings = document.getElementById('cookie-settings');
            
            if (cookieAccept) {
                cookieAccept.addEventListener('click', function() {
                    document.cookie = 'cookie_consent=accepted; path=/; max-age=31536000';
                    cookieBanner.style.display = 'none';
                });
            }
            
            if (cookieSettings) {
                cookieSettings.addEventListener('click', function() {
                    // Open cookie settings modal
                    console.log('Open cookie settings');
                });
            }
            
            // Newsletter subscription
            const newsletterForm = document.getElementById('newsletter-form');
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    
                    fetch(window.ArabMed.routes.newsletter, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': window.ArabMed.csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('تم الاشتراك بنجاح!', 'success');
                            this.reset();
                        } else {
                            showAlert('حدث خطأ، يرجى المحاولة مرة أخرى', 'error');
                        }
                    })
                    .catch(error => {
                        showAlert('حدث خطأ، يرجى المحاولة مرة أخرى', 'error');
                    });
                });
            }
            
            // Language switcher
            const languageToggle = document.getElementById('language-toggle');
            const languageDropdown = document.getElementById('language-dropdown');
            
            if (languageToggle && languageDropdown) {
                languageToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    languageDropdown.classList.toggle('show');
                });
                
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.language-switcher')) {
                        languageDropdown.classList.remove('show');
                    }
                });
            }
            
            // Global search
            const globalSearch = document.getElementById('global-search');
            const searchResults = document.getElementById('search-results');
            
            if (globalSearch && searchResults) {
                let searchTimeout;
                
                globalSearch.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();
                    
                    if (query.length >= 3) {
                        searchTimeout = setTimeout(() => {
                            performSearch(query);
                        }, 300);
                    } else {
                        searchResults.style.display = 'none';
                    }
                });
                
                globalSearch.addEventListener('focus', function() {
                    if (this.value.trim().length >= 3) {
                        searchResults.style.display = 'block';
                    }
                });
                
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.search-container')) {
                        searchResults.style.display = 'none';
                    }
                });
            }
        });
        
        // Search function
        function performSearch(query) {
            const searchResults = document.getElementById('search-results');
            
            fetch(`${window.ArabMed.routes.search}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data.results || []);
                })
                .catch(error => {
                    console.error('Search error:', error);
                });
        }
        
        // Display search results
        function displaySearchResults(results) {
            const searchResults = document.getElementById('search-results');
            
            if (results.length === 0) {
                searchResults.innerHTML = '<div class="search-no-results">لا توجد نتائج</div>';
            } else {
                searchResults.innerHTML = results.map(result => `
                    <a href="${result.url}" class="search-result-item">
                        <div class="search-result-title">${result.title}</div>
                        <div class="search-result-description">${result.description}</div>
                    </a>
                `).join('');
            }
            
            searchResults.style.display = 'block';
        }
        
        // Show alert function
        function showAlert(message, type = 'info') {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                <div class="container">
                    <i class="material-icons">${getAlertIcon(type)}</i>
                    ${message}
                    <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            
            document.body.insertBefore(alert, document.body.firstChild);
            
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }
        
        // Get alert icon
        function getAlertIcon(type) {
            const icons = {
                success: 'check_circle',
                error: 'error',
                warning: 'warning',
                info: 'info'
            };
            return icons[type] || 'info';
        }
    </script>
    
</body>
</html>
