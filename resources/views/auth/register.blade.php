@extends('layouts.auth')

@section('title', 'إنشاء حساب جديد - Arab-Med')

@section('meta')
    <meta name="description" content="إنشاء حساب جديد في منصة Arab-Med للتعليم الطبي">
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
                <h2 class="auth-title">إنشاء حساب جديد</h2>
                <p class="auth-subtitle">انضم إلى مجتمع التعليم الطبي</p>
            </div>
        </div>

        <!-- Registration Form -->
        <form class="auth-form" method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf
            
            <!-- Name Fields -->
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name" class="form-label">
                        <i class="material-icons">person</i>
                        الاسم الأول
                    </label>
                    <input 
                        type="text" 
                        id="first_name" 
                        name="first_name" 
                        class="form-input @error('first_name') error @enderror" 
                        value="{{ old('first_name') }}" 
                        required 
                        autocomplete="given-name"
                        placeholder="الاسم الأول"
                    >
                    @error('first_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="last_name" class="form-label">
                        <i class="material-icons">person</i>
                        الاسم الأخير
                    </label>
                    <input 
                        type="text" 
                        id="last_name" 
                        name="last_name" 
                        class="form-input @error('last_name') error @enderror" 
                        value="{{ old('last_name') }}" 
                        required 
                        autocomplete="family-name"
                        placeholder="الاسم الأخير"
                    >
                    @error('last_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

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

            <!-- Phone Field -->
            <div class="form-group">
                <label for="phone" class="form-label">
                    <i class="material-icons">phone</i>
                    رقم الهاتف
                </label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    class="form-input @error('phone') error @enderror" 
                    value="{{ old('phone') }}" 
                    autocomplete="tel"
                    placeholder="رقم الهاتف (اختياري)"
                >
                @error('phone')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Role Selection -->
            <div class="form-group">
                <label class="form-label">
                    <i class="material-icons">school</i>
                    نوع الحساب
                </label>
                <div class="role-selection">
                    <label class="role-option">
                        <input type="radio" name="role" value="student" {{ old('role', 'student') == 'student' ? 'checked' : '' }}>
                        <div class="role-card">
                            <div class="role-icon">
                                <i class="material-icons">school</i>
                            </div>
                            <div class="role-info">
                                <h4 class="role-title">طالب</h4>
                                <p class="role-description">للطلاب الراغبين في التعلم</p>
                            </div>
                        </div>
                    </label>
                    
                    <label class="role-option">
                        <input type="radio" name="role" value="doctor" {{ old('role') == 'doctor' ? 'checked' : '' }}>
                        <div class="role-card">
                            <div class="role-icon">
                                <i class="material-icons">local_hospital</i>
                            </div>
                            <div class="role-info">
                                <h4 class="role-title">طبيب</h4>
                                <p class="role-description">للأطباء والمختصين</p>
                            </div>
                        </div>
                    </label>
                </div>
                @error('role')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Fields -->
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
                        autocomplete="new-password"
                        placeholder="أدخل كلمة المرور"
                        minlength="8"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="material-icons">visibility</i>
                    </button>
                </div>
                <div class="password-strength" id="passwordStrength">
                    <div class="strength-bar">
                        <div class="strength-fill"></div>
                    </div>
                    <div class="strength-text">قوة كلمة المرور</div>
                </div>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">
                    <i class="material-icons">lock</i>
                    تأكيد كلمة المرور
                </label>
                <div class="password-input-wrapper">
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-input" 
                        required 
                        autocomplete="new-password"
                        placeholder="أعد إدخال كلمة المرور"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="material-icons">visibility</i>
                    </button>
                </div>
            </div>

            <!-- Terms and Privacy -->
            <div class="form-group">
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="terms" required {{ old('terms') ? 'checked' : '' }}>
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-label">
                        أوافق على 
                        <a href="{{ route('terms') }}" target="_blank" class="terms-link">شروط الاستخدام</a>
                        و
                        <a href="{{ route('privacy') }}" target="_blank" class="terms-link">سياسة الخصوصية</a>
                    </span>
                </label>
                @error('terms')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Newsletter Subscription -->
            <div class="form-group">
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="newsletter" {{ old('newsletter') ? 'checked' : '' }}>
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-label">أرغب في تلقي النشرة الإخبارية والتحديثات</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-full" id="registerButton">
                <span class="btn-text">إنشاء الحساب</span>
                <span class="btn-loading" style="display: none;">
                    <i class="material-icons spinning">refresh</i>
                    جاري الإنشاء...
                </span>
            </button>

            <!-- Divider -->
            <div class="auth-divider">
                <span class="divider-text">أو</span>
            </div>

            <!-- Social Registration -->
            <div class="social-login">
                <button type="button" class="btn btn-social btn-google" onclick="registerWithGoogle()">
                    <img src="{{ asset('images/icons/google.svg') }}" alt="Google" class="social-icon">
                    التسجيل بـ Google
                </button>
                
                <button type="button" class="btn btn-social btn-facebook" onclick="registerWithFacebook()">
                    <img src="{{ asset('images/icons/facebook.svg') }}" alt="Facebook" class="social-icon">
                    التسجيل بـ Facebook
                </button>
            </div>

            <!-- Login Link -->
            <div class="auth-footer">
                <p class="auth-footer-text">
                    لديك حساب بالفعل؟
                    <a href="{{ route('login') }}" class="auth-link">تسجيل الدخول</a>
                </p>
            </div>
        </form>
    </div>

    <!-- Benefits Sidebar -->
    <div class="auth-sidebar">
        <div class="sidebar-content">
            <div class="sidebar-hero">
                <img src="{{ asset('images/auth/medical-education.svg') }}" alt="التعليم الطبي" class="sidebar-image">
                <h3 class="sidebar-title">ابدأ رحلتك التعليمية</h3>
                <p class="sidebar-description">
                    احصل على شهادات معتمدة وطور مهاراتك الطبية مع خبراء المجال
                </p>
            </div>

            <div class="sidebar-benefits">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="material-icons">verified</i>
                    </div>
                    <div class="benefit-content">
                        <h4 class="benefit-title">شهادات معتمدة</h4>
                        <p class="benefit-description">احصل على شهادات معترف بها دولياً</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="material-icons">support_agent</i>
                    </div>
                    <div class="benefit-content">
                        <h4 class="benefit-title">دعم فني 24/7</h4>
                        <p class="benefit-description">فريق دعم متاح لمساعدتك في أي وقت</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="material-icons">groups</i>
                    </div>
                    <div class="benefit-content">
                        <h4 class="benefit-title">مجتمع تفاعلي</h4>
                        <p class="benefit-description">تواصل مع زملائك وشارك الخبرات</p>
                    </div>
                </div>
            </div>

            <div class="sidebar-testimonial">
                <div class="testimonial-content">
                    <div class="testimonial-quote">
                        <i class="material-icons">format_quote</i>
                    </div>
                    <p class="testimonial-text">
                        "منصة رائعة ساعدتني في تطوير مهاراتي الطبية بشكل كبير"
                    </p>
                    <div class="testimonial-author">
                        <img src="{{ asset('images/testimonials/doctor1.jpg') }}" alt="د. أحمد محمد" class="author-avatar">
                        <div class="author-info">
                            <div class="author-name">د. أحمد محمد</div>
                            <div class="author-title">طبيب قلب</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/auth.js'])
    <script>
        // Initialize registration form
        document.addEventListener('DOMContentLoaded', function() {
            initializeRegisterForm();
        });

        function initializeRegisterForm() {
            const form = document.getElementById('registerForm');
            const button = document.getElementById('registerButton');
            const passwordInput = document.getElementById('password');
            
            form.addEventListener('submit', function(e) {
                showLoading(button);
            });

            // Password strength checker
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });

            // Password confirmation validation
            const confirmInput = document.getElementById('password_confirmation');
            confirmInput.addEventListener('input', function() {
                validatePasswordMatch();
            });
        }

        function checkPasswordStrength(password) {
            const strengthBar = document.querySelector('.strength-fill');
            const strengthText = document.querySelector('.strength-text');
            
            let strength = 0;
            let text = 'ضعيفة';
            let color = '#f44336';
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            switch (strength) {
                case 0:
                case 1:
                    text = 'ضعيفة جداً';
                    color = '#f44336';
                    break;
                case 2:
                    text = 'ضعيفة';
                    color = '#ff9800';
                    break;
                case 3:
                    text = 'متوسطة';
                    color = '#ffeb3b';
                    break;
                case 4:
                    text = 'قوية';
                    color = '#8bc34a';
                    break;
                case 5:
                    text = 'قوية جداً';
                    color = '#4caf50';
                    break;
            }
            
            strengthBar.style.width = (strength * 20) + '%';
            strengthBar.style.backgroundColor = color;
            strengthText.textContent = text;
            strengthText.style.color = color;
        }

        function validatePasswordMatch() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const confirmInput = document.getElementById('password_confirmation');
            
            if (confirm && password !== confirm) {
                confirmInput.classList.add('error');
                if (!confirmInput.nextElementSibling || !confirmInput.nextElementSibling.classList.contains('form-error')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'form-error';
                    errorDiv.textContent = 'كلمات المرور غير متطابقة';
                    confirmInput.parentNode.insertBefore(errorDiv, confirmInput.nextSibling);
                }
            } else {
                confirmInput.classList.remove('error');
                const errorDiv = confirmInput.parentNode.querySelector('.form-error');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }
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

        function registerWithGoogle() {
            window.location.href = '{{ route("auth.google") }}';
        }

        function registerWithFacebook() {
            window.location.href = '{{ route("auth.facebook") }}';
        }
    </script>
@endsection
