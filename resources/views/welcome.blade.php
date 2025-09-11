<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Arab-Med') }} - منصة التعليم الطبي العربي</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/css/pages/welcome.css', 'resources/js/pages/welcome.js'])
</head>
<body>
    <!-- Navigation -->
    <x-navbar 
        brand-logo="{{ asset('images/logo.png') }}"
        :brand="config('app.name','Arab-Med')"
        brand-href="{{ url('/') }}"
        :fixed="true"
        color="white"
        :shadow="true"
        :transparent="true"
        :autoSolidOnScroll="true">
        <x-slot name="navigation">
            <div class="nav-item">
                <a href="#features" class="nav-link" aria-label="المميزات">
                    <i class="material-icons">star</i>
                    المميزات
                </a>
            </div>

            <div class="nav-item">
                <a href="#demo" class="nav-link" aria-label="العرض التوضيحي">
                    <i class="material-icons">dashboard</i>
                    العرض التوضيحي
                </a>
            </div>

            <div class="nav-item">
                <a href="#" class="nav-link dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">menu_book</i>
                    الدورات
                </a>
                <div class="dropdown-menu" role="menu">
                    <a href="{{ url('/student') }}" class="dropdown-item">
                        <i class="material-icons">school</i>
                        جميع الدورات
                    </a>
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-submenu">
                        <a href="#" class="dropdown-item" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">category</i>
                            التخصصات
                        </a>
                        <div class="dropdown-menu" role="menu">
                            <a href="{{ url('/student') }}?category=cardiology" class="dropdown-item">أمراض القلب</a>
                            <a href="{{ url('/student') }}?category=neurology" class="dropdown-item">الأعصاب</a>
                            <a href="{{ url('/student') }}?category=surgery" class="dropdown-item">الجراحة</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nav-item">
                <a href="#contact" class="nav-link" aria-label="تواصل معنا">
                    <i class="material-icons">contact_mail</i>
                    تواصل معنا
                </a>
            </div>
        </x-slot>
        <x-slot name="user">
            <a href="/student" class="btn btn-outline-primary">
                <i class="material-icons">login</i>
                دخول الطلاب
            </a>
            <a href="/admin" class="btn btn-primary">
                <i class="material-icons">admin_panel_settings</i>
                لوحة الإدارة
            </a>
        </x-slot>
    </x-navbar>

    <!-- Hero Section -->
    <section class="hero-section" aria-labelledby="hero-title">
        <div class="hero-background" aria-hidden="true"></div>
        <!-- Animated medical iconography -->
        <div class="hero-icons" aria-hidden="true">
            <!-- Medical cross -->
            <svg class="hero-icon hero-icon-cross" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="26" y="8" width="12" height="48" rx="6" fill="rgba(180, 79, 79, 0.6)"/>
                <rect x="8" y="26" width="48" height="12" rx="6" fill="rgba(255,255,255,0.6)"/>
            </svg>
            <!-- Heartbeat line -->
            <svg class="hero-icon hero-icon-ekg" width="140" height="60" viewBox="0 0 140 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 30 H30 L40 10 L52 46 L64 30 H88 L98 20 L108 44 L118 30 H138" stroke="rgba(255,255,255,0.8)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <!-- Pill / capsule -->
            <svg class="hero-icon hero-icon-pill" width="84" height="84" viewBox="0 0 84 84" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="pillg" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#ffffff" stop-opacity="0.7"/>
                        <stop offset="100%" stop-color="#ffffff" stop-opacity="0.4"/>
                    </linearGradient>
                </defs>
                <rect x="10" y="30" width="64" height="24" rx="12" fill="url(#pillg)"/>
                <rect x="10" y="30" width="32" height="24" rx="12" fill="rgba(255,255,255,0.65)"/>
            </svg>
        </div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 id="hero-title" class="hero-title">منصة تعلم الطب العربي</h1>
                    <p class="hero-subtitle">
                        منصة تعلم الطب العربي على طريقة الحكماء
                    </p>

                    <div class="hero-badges" role="list" aria-label="مزايا رئيسية">
                        <span class="badge badge-glass" role="listitem">
                            <i class="material-icons" aria-hidden="true">local_hospital</i>
                            محتوى طبي موثوق
                        </span>
                        <span class="badge badge-glass" role="listitem">
                            <i class="material-icons" aria-hidden="true">auto_graph</i>
                            تتبع تقدم ذكي
                        </span>
                        <span class="badge badge-glass" role="listitem">
                            <i class="material-icons" aria-hidden="true">quiz</i>
                            اختبارات تفاعلية
                        </span>
                    </div>

                    <div class="hero-actions">
                        <a href="/student/register" class="btn btn-primary btn-xl">ابدأ التعلم الآن</a>
                        <a href="#demo" class="btn btn-outline-primary btn-xl">شاهد العرض التوضيحي</a>
                    </div>
                </div>

                <div class="hero-image" aria-hidden="true">
                    <img class="hero-img" alt="توضيح تعليمي طبي" src='data:image/svg+xml;utf8,
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480">
                      <defs>
                        <linearGradient id="g1" x1="0" y1="0" x2="1" y2="1">
                          <stop offset="0%" stop-color="%23ffffff" stop-opacity="0.9"/>
                          <stop offset="100%" stop-color="%23ffffff" stop-opacity="0.6"/>
                        </linearGradient>
                        <linearGradient id="g2" x1="0" y1="0" x2="1" y2="0">
                          <stop offset="0%" stop-color="%23197ab3"/>
                          <stop offset="100%" stop-color="%2314a37f"/>
                        </linearGradient>
                      </defs>
                      <rect width="640" height="480" fill="url(%23g1)" rx="24"/>
                      <g opacity="0.08">
                        <circle cx="520" cy="80" r="60" fill="#197ab3"/>
                        <circle cx="120" cy="380" r="80" fill="#14a37f"/>
                        <circle cx="80" cy="120" r="40" fill="#0ea5a0"/>
                      </g>
                      <g transform="translate(120,80)">
                        <rect x="0" y="0" width="400" height="280" rx="16" fill="#ffffff" stroke="#dbe5ef"/>
                        <rect x="24" y="24" width="200" height="16" rx="8" fill="#0f766e" opacity="0.2"/>
                        <rect x="24" y="56" width="320" height="10" rx="5" fill="#197ab3" opacity="0.25"/>
                        <rect x="24" y="76" width="280" height="10" rx="5" fill="#197ab3" opacity="0.2"/>
                        <rect x="24" y="96" width="300" height="10" rx="5" fill="#197ab3" opacity="0.2"/>
                        <path d="M24 160 Q80 120 120 160 T216 160 T312 160" fill="none" stroke="url(%23g2)" stroke-width="6" stroke-linecap="round"/>
                        <g transform="translate(300,24)">
                          <rect x="0" y="0" width="76" height="76" rx="12" fill="#e8f5ff"/>
                          <path d="M38 12 v52 M12 38 h52" stroke="#197ab3" stroke-width="8" stroke-linecap="round"/>
                        </g>
                      </g>
                    </svg>' />
                </div>
            </div>
        </div>
        <div class="scroll-indicator" aria-hidden="true">
            <div class="mouse"></div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-primary">
                        <i class="material-icons">school</i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">15,000+</div>
                        <div class="stat-label">طالب مسجل</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon stat-icon-secondary">
                        <i class="material-icons">video_library</i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">محاضرة فيديو</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon stat-icon-warning">
                        <i class="material-icons">quiz</i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">200+</div>
                        <div class="stat-label">اختبار تفاعلي</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon stat-icon-success">
                        <i class="material-icons">verified</i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">معدل النجاح</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">مميزات المنصة</h2>
                <p class="section-subtitle">تقنيات متطورة لتجربة تعليمية استثنائية</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="material-icons">play_circle</i>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title">محاضرات فيديو تفاعلية</h3>
                        <p class="feature-description">
                            محتوى فيديو عالي الجودة مع إمكانية التفاعل والتحكم في سرعة التشغيل
                        </p>
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="material-icons">assessment</i>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title">اختبارات ذكية</h3>
                        <p class="feature-description">
                            نظام اختبارات متطور مع تقييم فوري وتحليل مفصل للأداء
                        </p>
                    </div>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="material-icons">track_changes</i>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-title">تتبع التقدم</h3>
                        <p class="feature-description">
                            متابعة مستمرة لتقدمك الأكاديمي مع تقارير مفصلة وإحصائيات دقيقة
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Dashboard Section -->
    <section id="demo" class="demo-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">لوحة التحكم التفاعلية</h2>
                <p class="section-subtitle">استكشف واجهة المنصة والأدوات المتاحة</p>
            </div>
            
            <!-- Demo Dashboard Cards -->
            <div class="demo-dashboard">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- KPI Cards -->
                    <div class="demo-card">
                        <div class="card-header">
                            <h3 class="card-title">إحصائيات سريعة</h3>
                        </div>
                        <div class="card-body">
                            <div class="kpi-grid">
                                <div class="kpi-item">
                                    <div class="kpi-value">24</div>
                                    <div class="kpi-label">دورة مكتملة</div>
                                </div>
                                <div class="kpi-item">
                                    <div class="kpi-value">89%</div>
                                    <div class="kpi-label">معدل النجاح</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Card -->
                    <div class="demo-card">
                        <div class="card-header">
                            <h3 class="card-title">التقدم الحالي</h3>
                        </div>
                        <div class="card-body">
                            <div class="progress-item">
                                <div class="progress-info">
                                    <span class="progress-label">علم التشريح</span>
                                    <span class="progress-value">75%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 75%"></div>
                                </div>
                            </div>
                            <div class="progress-item">
                                <div class="progress-info">
                                    <span class="progress-label">علم وظائف الأعضاء</span>
                                    <span class="progress-value">60%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 60%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="demo-card">
                        <div class="card-header">
                            <h3 class="card-title">النشاط الأخير</h3>
                        </div>
                        <div class="card-body">
                            <div class="activity-list">
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="material-icons">play_circle</i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">مشاهدة محاضرة</div>
                                        <div class="activity-time">منذ ساعتين</div>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="material-icons">quiz</i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">إكمال اختبار</div>
                                        <div class="activity-time">أمس</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Demo -->
                <div class="demo-card chart-demo">
                    <div class="card-header">
                        <h3 class="card-title">تحليل الأداء</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="performanceChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vue.js Landing App Component -->
            <div id="landing-app"></div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">آراء الطلاب</h2>
                <p class="section-subtitle">ماذا يقول طلابنا عن تجربتهم</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="testimonial-rating">
                            <i class="material-icons">star</i>
                            <i class="material-icons">star</i>
                            <i class="material-icons">star</i>
                            <i class="material-icons">star</i>
                            <i class="material-icons">star</i>
                        </div>
                        <p class="testimonial-text">
                            "منصة رائعة ساعدتني كثيراً في فهم المواد الطبية المعقدة. المحتوى عالي الجودة والتفاعل ممتاز."
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="/images/avatars/student1.jpg" alt="أحمد محمد">
                        </div>
                        <div class="author-info">
                            <div class="author-name">أحمد محمد</div>
                            <div class="author-title">طالب طب - السنة الثالثة</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="testimonial-rating">
                            <i class="material-icons">star</i>
                            <i class="material-icons">star</i>
                            <i class="material-icons">star</i>
                            <i class="material-icons">star</i>
                            <i class="material-icons">star</i>
                        </div>
                        <p class="testimonial-text">
                            "الاختبارات التفاعلية والتقييم الفوري ساعداني في تحسين أدائي بشكل كبير. أنصح بها بشدة."
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="/images/avatars/student2.jpg" alt="فاطمة علي">
                        </div>
                        <div class="author-info">
                            <div class="author-name">فاطمة علي</div>
                            <div class="author-title">طالبة طب - السنة الثانية</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="testimonial-rating">
                            <i class="material-icons">star</i>
                            <i class="material-icons">star</i>
                            <i class="material-icons">star</i>
                            <i class="material-icons">star</i>
                            <i class="material-icons">star</i>
                        </div>
                        <p class="testimonial-text">
                            "واجهة سهلة الاستخدام ومحتوى شامل. المنصة غيرت طريقة دراستي للأفضل."
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="/images/avatars/student3.jpg" alt="محمد حسن">
                        </div>
                        <div class="author-info">
                            <div class="author-name">محمد حسن</div>
                            <div class="author-title">طالب طب - السنة الرابعة</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">ابدأ رحلتك التعليمية اليوم</h2>
                <p class="cta-subtitle">انضم إلى آلاف الطلاب الذين يطورون مهاراتهم الطبية معنا</p>
                <div class="cta-actions">
                    <a href="/student/register" class="btn btn-primary btn-xl">إنشاء حساب جديد</a>
                    <a href="/student/login" class="btn btn-outline-primary btn-xl">تسجيل الدخول</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Arab-Med</h4>
                    <p>منصة التعليم الطبي العربي الرائدة</p>
                </div>
                
                <div class="footer-section">
                    <h4>روابط سريعة</h4>
                    <ul>
                        <li><a href="/about">عن المنصة</a></li>
                        <li><a href="/courses">الدورات</a></li>
                        <li><a href="/contact">تواصل معنا</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>الدعم</h4>
                    <ul>
                        <li><a href="/help">مركز المساعدة</a></li>
                        <li><a href="/privacy">سياسة الخصوصية</a></li>
                        <li><a href="/terms">شروط الاستخدام</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 Arab-Med. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>
</body>
</html>