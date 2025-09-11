@extends('layouts.app')

@section('title', 'لوحة التحكم - الطالب')

@section('meta')
    <meta name="description" content="لوحة تحكم الطالب - تتبع تقدمك الأكاديمي ومشاهدة الإحصائيات والأنشطة الحديثة">
@endsection

@section('styles')
    @vite(['resources/css/pages/dashboard.css'])
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">مرحباً، {{ auth()->user()->name }}</h1>
                <p class="page-subtitle">تتبع تقدمك الأكاديمي وإنجازاتك</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-outline-primary" onclick="refreshDashboard()">
                    <i class="material-icons">refresh</i>
                    تحديث البيانات
                </button>
                <a href="{{ route('courses.index') }}" class="btn btn-primary">
                    <i class="material-icons">school</i>
                    تصفح الدورات
                </a>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="kpi-section">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Enrolled Courses -->
            <div class="kpi-card">
                <div class="kpi-header">
                    <div class="kpi-icon kpi-icon-primary">
                        <i class="material-icons">school</i>
                    </div>
                    <div class="kpi-trend kpi-trend-up">
                        <i class="material-icons">trending_up</i>
                        <span>+12%</span>
                    </div>
                </div>
                <div class="kpi-content">
                    <div class="kpi-value">{{ $enrolledCourses ?? 8 }}</div>
                    <div class="kpi-label">الدورات المسجلة</div>
                </div>
            </div>

            <!-- Completed Courses -->
            <div class="kpi-card">
                <div class="kpi-header">
                    <div class="kpi-icon kpi-icon-success">
                        <i class="material-icons">check_circle</i>
                    </div>
                    <div class="kpi-trend kpi-trend-up">
                        <i class="material-icons">trending_up</i>
                        <span>+25%</span>
                    </div>
                </div>
                <div class="kpi-content">
                    <div class="kpi-value">{{ $completedCourses ?? 5 }}</div>
                    <div class="kpi-label">الدورات المكتملة</div>
                </div>
            </div>

            <!-- Study Hours -->
            <div class="kpi-card">
                <div class="kpi-header">
                    <div class="kpi-icon kpi-icon-warning">
                        <i class="material-icons">schedule</i>
                    </div>
                    <div class="kpi-trend kpi-trend-up">
                        <i class="material-icons">trending_up</i>
                        <span>+8%</span>
                    </div>
                </div>
                <div class="kpi-content">
                    <div class="kpi-value">{{ $studyHours ?? 124 }}</div>
                    <div class="kpi-label">ساعات الدراسة</div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="kpi-card">
                <div class="kpi-header">
                    <div class="kpi-icon kpi-icon-info">
                        <i class="material-icons">grade</i>
                    </div>
                    <div class="kpi-trend kpi-trend-up">
                        <i class="material-icons">trending_up</i>
                        <span>+5%</span>
                    </div>
                </div>
                <div class="kpi-content">
                    <div class="kpi-value">{{ $averageScore ?? 87 }}%</div>
                    <div class="kpi-label">المعدل العام</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="dashboard-content">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Progress Overview -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">نظرة عامة على التقدم</h3>
                        <div class="card-actions">
                            <button class="btn btn-sm btn-ghost" onclick="toggleProgressView()">
                                <i class="material-icons">view_module</i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="progress-overview">
                            @forelse($currentCourses ?? [] as $course)
                                <div class="progress-item">
                                    <div class="progress-header">
                                        <div class="course-info">
                                            <h4 class="course-title">{{ $course['title'] ?? 'علم التشريح' }}</h4>
                                            <span class="course-instructor">د. {{ $course['instructor'] ?? 'أحمد محمد' }}</span>
                                        </div>
                                        <div class="progress-percentage">{{ $course['progress'] ?? 75 }}%</div>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $course['progress'] ?? 75 }}%"></div>
                                    </div>
                                    <div class="progress-meta">
                                        <span class="lessons-completed">{{ $course['completed_lessons'] ?? 12 }}/{{ $course['total_lessons'] ?? 16 }} درس</span>
                                        <span class="next-deadline">الموعد النهائي: {{ $course['deadline'] ?? '15 ديسمبر' }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="progress-item">
                                    <div class="progress-header">
                                        <div class="course-info">
                                            <h4 class="course-title">علم التشريح</h4>
                                            <span class="course-instructor">د. أحمد محمد</span>
                                        </div>
                                        <div class="progress-percentage">75%</div>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 75%"></div>
                                    </div>
                                    <div class="progress-meta">
                                        <span class="lessons-completed">12/16 درس</span>
                                        <span class="next-deadline">الموعد النهائي: 15 ديسمبر</span>
                                    </div>
                                </div>

                                <div class="progress-item">
                                    <div class="progress-header">
                                        <div class="course-info">
                                            <h4 class="course-title">علم وظائف الأعضاء</h4>
                                            <span class="course-instructor">د. فاطمة علي</span>
                                        </div>
                                        <div class="progress-percentage">60%</div>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 60%"></div>
                                    </div>
                                    <div class="progress-meta">
                                        <span class="lessons-completed">9/15 درس</span>
                                        <span class="next-deadline">الموعد النهائي: 20 ديسمبر</span>
                                    </div>
                                </div>

                                <div class="progress-item">
                                    <div class="progress-header">
                                        <div class="course-info">
                                            <h4 class="course-title">علم الأمراض</h4>
                                            <span class="course-instructor">د. محمد حسن</span>
                                        </div>
                                        <div class="progress-percentage">45%</div>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 45%"></div>
                                    </div>
                                    <div class="progress-meta">
                                        <span class="lessons-completed">7/18 درس</span>
                                        <span class="next-deadline">الموعد النهائي: 25 ديسمبر</span>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Performance Chart -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">تحليل الأداء</h3>
                        <div class="card-actions">
                            <select class="form-select form-select-sm" onchange="updateChart(this.value)">
                                <option value="week">هذا الأسبوع</option>
                                <option value="month" selected>هذا الشهر</option>
                                <option value="quarter">هذا الربع</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="performanceChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Exams -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">الاختبارات القادمة</h3>
                        <a href="{{ route('exams.index') }}" class="btn btn-sm btn-outline-primary">
                            عرض الكل
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="exam-list">
                            @forelse($upcomingExams ?? [] as $exam)
                                <div class="exam-item">
                                    <div class="exam-info">
                                        <h4 class="exam-title">{{ $exam['title'] }}</h4>
                                        <p class="exam-course">{{ $exam['course'] }}</p>
                                    </div>
                                    <div class="exam-meta">
                                        <div class="exam-date">{{ $exam['date'] }}</div>
                                        <div class="exam-duration">{{ $exam['duration'] }} دقيقة</div>
                                    </div>
                                    <div class="exam-actions">
                                        <button class="btn btn-sm btn-primary">ابدأ الاختبار</button>
                                    </div>
                                </div>
                            @empty
                                <div class="exam-item">
                                    <div class="exam-info">
                                        <h4 class="exam-title">اختبار علم التشريح - الفصل الثالث</h4>
                                        <p class="exam-course">علم التشريح</p>
                                    </div>
                                    <div class="exam-meta">
                                        <div class="exam-date">غداً، 2:00 م</div>
                                        <div class="exam-duration">90 دقيقة</div>
                                    </div>
                                    <div class="exam-actions">
                                        <button class="btn btn-sm btn-primary">ابدأ الاختبار</button>
                                    </div>
                                </div>

                                <div class="exam-item">
                                    <div class="exam-info">
                                        <h4 class="exam-title">اختبار علم وظائف الأعضاء - النهائي</h4>
                                        <p class="exam-course">علم وظائف الأعضاء</p>
                                    </div>
                                    <div class="exam-meta">
                                        <div class="exam-date">الأحد، 10:00 ص</div>
                                        <div class="exam-duration">120 دقيقة</div>
                                    </div>
                                    <div class="exam-actions">
                                        <button class="btn btn-sm btn-outline-primary">مراجعة</button>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Recent Activity -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">النشاط الأخير</h3>
                        <button class="btn btn-sm btn-ghost" onclick="refreshActivity()">
                            <i class="material-icons">refresh</i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="activity-feed">
                            @forelse($recentActivities ?? [] as $activity)
                                <div class="activity-item">
                                    <div class="activity-icon activity-icon-{{ $activity['type'] }}">
                                        <i class="material-icons">{{ $activity['icon'] }}</i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">{{ $activity['title'] }}</div>
                                        <div class="activity-description">{{ $activity['description'] }}</div>
                                        <div class="activity-time">{{ $activity['time'] }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="activity-item">
                                    <div class="activity-icon activity-icon-video">
                                        <i class="material-icons">play_circle</i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">مشاهدة محاضرة</div>
                                        <div class="activity-description">الجهاز العصبي - الجزء الثاني</div>
                                        <div class="activity-time">منذ ساعتين</div>
                                    </div>
                                </div>

                                <div class="activity-item">
                                    <div class="activity-icon activity-icon-exam">
                                        <i class="material-icons">quiz</i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">إكمال اختبار</div>
                                        <div class="activity-description">اختبار علم التشريح - النتيجة: 85%</div>
                                        <div class="activity-time">أمس</div>
                                    </div>
                                </div>

                                <div class="activity-item">
                                    <div class="activity-icon activity-icon-achievement">
                                        <i class="material-icons">emoji_events</i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">إنجاز جديد</div>
                                        <div class="activity-description">إكمال 10 دروس متتالية</div>
                                        <div class="activity-time">منذ 3 أيام</div>
                                    </div>
                                </div>

                                <div class="activity-item">
                                    <div class="activity-icon activity-icon-course">
                                        <i class="material-icons">school</i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">تسجيل في دورة</div>
                                        <div class="activity-description">علم الأمراض المتقدم</div>
                                        <div class="activity-time">منذ أسبوع</div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Study Calendar -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">جدول الدراسة</h3>
                        <button class="btn btn-sm btn-outline-primary" onclick="openCalendarModal()">
                            <i class="material-icons">event</i>
                            عرض التقويم
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="calendar-widget">
                            <div class="calendar-header">
                                <button class="calendar-nav" onclick="previousMonth()">
                                    <i class="material-icons">chevron_left</i>
                                </button>
                                <h4 class="calendar-title">ديسمبر 2024</h4>
                                <button class="calendar-nav" onclick="nextMonth()">
                                    <i class="material-icons">chevron_right</i>
                                </button>
                            </div>
                            <div class="calendar-grid">
                                <div class="calendar-day-header">ح</div>
                                <div class="calendar-day-header">ن</div>
                                <div class="calendar-day-header">ث</div>
                                <div class="calendar-day-header">ر</div>
                                <div class="calendar-day-header">خ</div>
                                <div class="calendar-day-header">ج</div>
                                <div class="calendar-day-header">س</div>
                                
                                @for($i = 1; $i <= 31; $i++)
                                    <div class="calendar-day {{ $i == 15 ? 'has-event' : '' }} {{ $i == date('d') ? 'today' : '' }}">
                                        {{ $i }}
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">إجراءات سريعة</h3>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <a href="{{ route('courses.index') }}" class="quick-action-item">
                                <div class="quick-action-icon">
                                    <i class="material-icons">school</i>
                                </div>
                                <div class="quick-action-text">تصفح الدورات</div>
                            </a>

                            <a href="{{ route('videos.index') }}" class="quick-action-item">
                                <div class="quick-action-icon">
                                    <i class="material-icons">video_library</i>
                                </div>
                                <div class="quick-action-text">مكتبة الفيديو</div>
                            </a>

                            <a href="{{ route('exams.index') }}" class="quick-action-item">
                                <div class="quick-action-icon">
                                    <i class="material-icons">quiz</i>
                                </div>
                                <div class="quick-action-text">الاختبارات</div>
                            </a>

                            <a href="{{ route('profile.index') }}" class="quick-action-item">
                                <div class="quick-action-icon">
                                    <i class="material-icons">person</i>
                                </div>
                                <div class="quick-action-text">الملف الشخصي</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vue.js Dashboard Components -->
    <div id="dashboard-app"></div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
    <script>
        // Initialize dashboard charts and interactions
        document.addEventListener('DOMContentLoaded', function() {
            initializePerformanceChart();
            initializeDashboardInteractions();
        });

        function initializePerformanceChart() {
            const ctx = document.getElementById('performanceChart');
            if (ctx && typeof Chart !== 'undefined') {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['الأسبوع 1', 'الأسبوع 2', 'الأسبوع 3', 'الأسبوع 4'],
                        datasets: [{
                            label: 'نقاط الأداء',
                            data: [65, 70, 80, 85],
                            borderColor: 'var(--primary)',
                            backgroundColor: 'var(--primary-50)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'ساعات الدراسة',
                            data: [20, 25, 30, 35],
                            borderColor: 'var(--secondary)',
                            backgroundColor: 'var(--secondary-50)',
                            tension: 0.4,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }

        function initializeDashboardInteractions() {
            // Add dashboard-specific interactions
            console.log('Dashboard interactions initialized');
        }

        function refreshDashboard() {
            // Refresh dashboard data
            location.reload();
        }

        function refreshActivity() {
            // Refresh activity feed
            console.log('Refreshing activity feed...');
        }

        function updateChart(period) {
            // Update chart based on selected period
            console.log('Updating chart for period:', period);
        }

        function toggleProgressView() {
            // Toggle between different progress views
            console.log('Toggling progress view');
        }

        function openCalendarModal() {
            // Open full calendar modal
            console.log('Opening calendar modal');
        }

        function previousMonth() {
            // Navigate to previous month
            console.log('Previous month');
        }

        function nextMonth() {
            // Navigate to next month
            console.log('Next month');
        }
    </script>
@endsection
