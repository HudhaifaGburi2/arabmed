<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Arab-Med') }} - @yield('title', 'تسجيل الدخول')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/layouts/auth.css'])
    @stack('styles')
</head>
<body class="auth-layout">
    
    <!-- Background Pattern -->
    <div class="auth-background">
        <div class="auth-pattern"></div>
        <div class="auth-overlay"></div>
    </div>
    
    <!-- Main Container -->
    <div class="auth-container">
        
        <!-- Left Side - Branding -->
        <div class="auth-brand-section">
            <div class="auth-brand-content">
                
                <!-- Logo -->
                <div class="auth-logo">
                    <img src="{{ asset('images/logo-white.png') }}" alt="Arab-Med" class="auth-logo-image">
                    <h1 class="auth-logo-text">Arab-Med</h1>
                </div>
                
                <!-- Welcome Message -->
                <div class="auth-welcome">
                    <h2 class="auth-welcome-title">@yield('welcome-title', 'مرحباً بك في منصة Arab-Med')</h2>
                    <p class="auth-welcome-subtitle">@yield('welcome-subtitle', 'منصة التعليم الطبي الرائدة في العالم العربي')</p>
                </div>
                
                <!-- Features List -->
                <div class="auth-features">
                    <div class="auth-feature">
                        <i class="material-icons">school</i>
                        <div class="auth-feature-content">
                            <h3>دورات تعليمية متخصصة</h3>
                            <p>محتوى طبي عالي الجودة من أفضل الأطباء والمتخصصين</p>
                        </div>
                    </div>
                    
                    <div class="auth-feature">
                        <i class="material-icons">quiz</i>
                        <div class="auth-feature-content">
                            <h3>امتحانات تفاعلية</h3>
                            <p>اختبر معلوماتك وتابع تقدمك مع نظام التقييم المتقدم</p>
                        </div>
                    </div>
                    
                    <div class="auth-feature">
                        <i class="material-icons">verified</i>
                        <div class="auth-feature-content">
                            <h3>شهادات معتمدة</h3>
                            <p>احصل على شهادات معتمدة تعزز مسيرتك المهنية</p>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="auth-stats">
                    <div class="auth-stat">
                        <span class="auth-stat-number">10,000+</span>
                        <span class="auth-stat-label">طالب</span>
                    </div>
                    <div class="auth-stat">
                        <span class="auth-stat-number">500+</span>
                        <span class="auth-stat-label">دورة</span>
                    </div>
                    <div class="auth-stat">
                        <span class="auth-stat-number">100+</span>
                        <span class="auth-stat-label">مدرب</span>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Right Side - Form -->
        <div class="auth-form-section">
            <div class="auth-form-container">
                
                <!-- Mobile Logo -->
                <div class="auth-mobile-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Arab-Med" class="auth-mobile-logo-image">
                    <span class="auth-mobile-logo-text">Arab-Med</span>
                </div>
                
                <!-- Form Header -->
                <div class="auth-form-header">
                    <h1 class="auth-form-title">@yield('form-title', 'تسجيل الدخول')</h1>
                    <p class="auth-form-subtitle">@yield('form-subtitle', 'أدخل بياناتك للوصول إلى حسابك')</p>
                </div>
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="material-icons">check_circle</i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="material-icons">error</i>
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="alert alert-warning">
                        <i class="material-icons">warning</i>
                        {{ session('warning') }}
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="alert alert-info">
                        <i class="material-icons">info</i>
                        {{ session('info') }}
                    </div>
                @endif
                
                <!-- Main Form Content -->
                <div class="auth-form-content">
                    @yield('content')
                </div>
                
                <!-- Form Footer -->
                <div class="auth-form-footer">
                    @yield('form-footer')
                </div>
                
                <!-- Social Login -->
                @hasSection('social-login')
                    <div class="auth-social">
                        <div class="auth-social-divider">
                            <span>أو</span>
                        </div>
                        
                        <div class="auth-social-buttons">
                            @yield('social-login')
                        </div>
                    </div>
                @endif
                
                <!-- Additional Links -->
                <div class="auth-links">
                    @yield('auth-links')
                </div>
                
            </div>
        </div>
        
    </div>
    
    <!-- Language Switcher -->
    <div class="auth-language-switcher">
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
    
    <!-- Footer -->
    <footer class="auth-footer">
        <div class="auth-footer-content">
            <div class="auth-footer-links">
                <a href="{{ route('privacy') }}">سياسة الخصوصية</a>
                <a href="{{ route('terms') }}">شروط الاستخدام</a>
                <a href="{{ route('help') }}">المساعدة</a>
                <a href="{{ route('contact') }}">اتصل بنا</a>
            </div>
            
            <div class="auth-footer-copyright">
                <p>&copy; {{ date('Y') }} Arab-Med. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    @vite(['resources/js/app.js', 'resources/js/layouts/auth.js'])
    @stack('scripts')
    
    <!-- Global JavaScript -->
    <script>
        // Global configuration
        window.ArabMed = {
            csrfToken: '{{ csrf_token() }}',
            locale: '{{ app()->getLocale() }}',
            routes: {
                home: '{{ route('welcome') }}',
                login: '{{ route('login') }}',
                register: '{{ route('register') }}'
            }
        };
        
        // Initialize auth functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Language switcher
            const languageToggle = document.getElementById('language-toggle');
            const languageDropdown = document.getElementById('language-dropdown');
            
            if (languageToggle && languageDropdown) {
                languageToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    languageDropdown.classList.toggle('show');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.auth-language-switcher')) {
                        languageDropdown.classList.remove('show');
                    }
                });
            }
            
            // Form validation enhancement
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                const inputs = form.querySelectorAll('input[required]');
                inputs.forEach(input => {
                    input.addEventListener('blur', function() {
                        validateInput(this);
                    });
                    
                    input.addEventListener('input', function() {
                        if (this.classList.contains('error')) {
                            validateInput(this);
                        }
                    });
                });
            });
            
            // Auto-dismiss alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
        
        // Input validation function
        function validateInput(input) {
            const value = input.value.trim();
            const type = input.type;
            let isValid = true;
            let errorMessage = '';
            
            // Required validation
            if (input.hasAttribute('required') && !value) {
                isValid = false;
                errorMessage = 'هذا الحقل مطلوب';
            }
            
            // Email validation
            if (type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'يرجى إدخال بريد إلكتروني صحيح';
                }
            }
            
            // Password validation
            if (type === 'password' && value) {
                if (value.length < 8) {
                    isValid = false;
                    errorMessage = 'كلمة المرور يجب أن تكون 8 أحرف على الأقل';
                }
            }
            
            // Update UI
            const formGroup = input.closest('.form-group');
            const errorElement = formGroup?.querySelector('.form-error');
            
            if (isValid) {
                input.classList.remove('error');
                if (errorElement) {
                    errorElement.textContent = '';
                    errorElement.style.display = 'none';
                }
            } else {
                input.classList.add('error');
                if (errorElement) {
                    errorElement.textContent = errorMessage;
                    errorElement.style.display = 'block';
                }
            }
            
            return isValid;
        }
        
        // Show/hide password functionality
        function togglePassword(button) {
            const input = button.previousElementSibling;
            const icon = button.querySelector('.material-icons');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }
    </script>
    
</body>
</html>
