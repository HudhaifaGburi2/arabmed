@extends('layouts.auth')

@section('title', 'تسجيل الدخول - Arab-Med')

@section('meta')
    <meta name="description" content="تسجيل الدخول إلى منصة Arab-Med للتعليم الطبي">
@endsection

@section('styles')
    @vite(['resources/css/pages/auth.css'])
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <!-- Header -->
        <div class="auth-header">
            <div class="auth-logo">
                <img src="{{ asset('images/logos/logo.svg') }}" alt="Arab-Med" class="logo-image">
                <h1 class="logo-text">Arab-Med</h1>
            </div>
            <div class="auth-title-section">
                <h2 class="auth-title">مرحباً بعودتك</h2>
                <p class="auth-subtitle">سجل دخولك للوصول إلى حسابك</p>
            </div>
        </div>

        <!-- Login Form -->
        <form class="auth-form" method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            
            <!-- Email Field -->
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="material-icons">email</i>
                    البريد الإلكتروني
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input @error('email') error @enderror" 
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="email"
                    placeholder="أدخل بريدك الإلكتروني"
                >
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="material-icons">lock</i>
                    كلمة المرور
                </label>
                <div class="password-input-wrapper">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input @error('password') error @enderror" 
                        required 
                        autocomplete="current-password"
                        placeholder="أدخل كلمة المرور"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="material-icons">visibility</i>
                    </button>
                </div>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="form-options">
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-label">تذكرني</span>
                </label>
                
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-password-link">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-full" id="loginButton">
                <span class="btn-text">تسجيل الدخول</span>
                <span class="btn-loading" style="display: none;">
                    <i class="material-icons spinning">refresh</i>
                    جاري التحميل...
                </span>
            </button>

            <!-- Divider -->
            <div class="auth-divider">
                <span class="divider-text">أو</span>
            </div>

            <!-- Social Login -->
            <div class="social-login">
                <button type="button" class="btn btn-social btn-google" onclick="loginWithGoogle()">
                    <img src="{{ asset('images/icons/google.svg') }}" alt="Google" class="social-icon">
                    تسجيل الدخول بـ Google
                </button>
                
                <button type="button" class="btn btn-social btn-facebook" onclick="loginWithFacebook()">
                    <img src="{{ asset('images/icons/facebook.svg') }}" alt="Facebook" class="social-icon">
                    تسجيل الدخول بـ Facebook
                </button>
            </div>

            <!-- Register Link -->
            <div class="auth-footer">
                <p class="auth-footer-text">
                    ليس لديك حساب؟
                    <a href="{{ route('register') }}" class="auth-link">إنشاء حساب جديد</a>
                </p>
            </div>
        </form>
    </div>

    <!-- Features Sidebar -->
    <div class="auth-sidebar">
        <div class="sidebar-content">
            <div class="sidebar-hero">
                <img src="{{ asset('images/auth/medical-learning.svg') }}" alt="التعليم الطبي" class="sidebar-image">
                <h3 class="sidebar-title">منصة التعليم الطبي الرائدة</h3>
                <p class="sidebar-description">
                    انضم إلى آلاف الطلاب والأطباء الذين يطورون مهاراتهم الطبية معنا
                </p>
            </div>

            <div class="sidebar-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="material-icons">video_library</i>
                    </div>
                    <div class="feature-content">
                        <h4 class="feature-title">محاضرات فيديو عالية الجودة</h4>
                        <p class="feature-description">محتوى تعليمي متميز من أفضل الأطباء والمختصين</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="material-icons">quiz</i>
                    </div>
                    <div class="feature-content">
                        <h4 class="feature-title">اختبارات تفاعلية</h4>
                        <p class="feature-description">قيم معرفتك مع اختبارات شاملة ومتنوعة</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="material-icons">track_changes</i>
                    </div>
                    <div class="feature-content">
                        <h4 class="feature-title">تتبع التقدم</h4>
                        <p class="feature-description">راقب تطورك الأكاديمي مع تقارير مفصلة</p>
                    </div>
                </div>
            </div>

            <div class="sidebar-stats">
                <div class="stat-item">
                    <div class="stat-number">15,000+</div>
                    <div class="stat-label">طالب مسجل</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">محاضرة</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">معدل الرضا</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/auth.js'])
    <script>
        // Initialize login form
        document.addEventListener('DOMContentLoaded', function() {
            initializeLoginForm();
        });

        function initializeLoginForm() {
            const form = document.getElementById('loginForm');
            const button = document.getElementById('loginButton');
            
            form.addEventListener('submit', function(e) {
                showLoading(button);
            });
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const toggle = input.nextElementSibling;
            const icon = toggle.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        function showLoading(button) {
            const text = button.querySelector('.btn-text');
            const loading = button.querySelector('.btn-loading');
            
            text.style.display = 'none';
            loading.style.display = 'flex';
            button.disabled = true;
        }

        function hideLoading(button) {
            const text = button.querySelector('.btn-text');
            const loading = button.querySelector('.btn-loading');
            
            text.style.display = 'block';
            loading.style.display = 'none';
            button.disabled = false;
        }

        function loginWithGoogle() {
            window.location.href = '{{ route("auth.google") }}';
        }

        function loginWithFacebook() {
            window.location.href = '{{ route("auth.facebook") }}';
        }

        // Auto-hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
@endsection
