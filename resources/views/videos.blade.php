@extends('layouts.app')

@section('title', 'المحاضرات المرئية - Arab-Med')

@section('meta')
    <meta name="description" content="تصفح مكتبة المحاضرات المرئية في منصة Arab-Med للتعليم الطبي">
@endsection

@section('styles')
    @vite(['resources/css/pages/videos.css'])
@endsection

@section('content')
<div class="videos-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">
                    <i class="material-icons">video_library</i>
                    المحاضرات المرئية
                </h1>
                <p class="page-subtitle">تصفح واستكشف مكتبة المحاضرات المرئية الشاملة</p>
            </div>
            
            <div class="page-actions">
                <button class="btn btn-outline" onclick="toggleViewMode()">
                    <i class="material-icons" id="viewModeIcon">grid_view</i>
                    <span id="viewModeText">عرض شبكي</span>
                </button>
                
                @can('create', App\Models\Video::class)
                <button class="btn btn-primary" onclick="openVideoModal()">
                    <i class="material-icons">add</i>
                    إضافة محاضرة
                </button>
                @endcan
            </div>
        </div>
        
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="{{ route('dashboard') }}" class="breadcrumb-item">
                <i class="material-icons">home</i>
                الرئيسية
            </a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-item active">المحاضرات المرئية</span>
        </nav>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
        <div class="filters-container">
            <!-- Search Bar -->
            <div class="search-container">
                <div class="search-input-wrapper">
                    <i class="material-icons search-icon">search</i>
                    <input 
                        type="text" 
                        class="search-input" 
                        placeholder="البحث في المحاضرات..."
                        id="videoSearch"
                        autocomplete="off"
                    >
                    <button class="search-clear" onclick="clearSearch()" style="display: none;">
                        <i class="material-icons">close</i>
                    </button>
                </div>
                <div class="search-suggestions" id="searchSuggestions"></div>
            </div>

            <!-- Filter Toggles -->
            <div class="filter-toggles">
                <button class="filter-toggle" onclick="toggleFilters()">
                    <i class="material-icons">tune</i>
                    تصفية
                    <span class="filter-count" id="filterCount" style="display: none;">0</span>
                </button>
                
                <button class="filter-toggle" onclick="toggleSortMenu()">
                    <i class="material-icons">sort</i>
                    ترتيب
                </button>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="advanced-filters" id="advancedFilters" style="display: none;">
            <div class="filters-grid">
                <!-- Category Filter -->
                <div class="filter-group">
                    <label class="filter-label">التخصص</label>
                    <select class="filter-select" id="categoryFilter">
                        <option value="">جميع التخصصات</option>
                        <option value="cardiology">أمراض القلب</option>
                        <option value="neurology">الأعصاب</option>
                        <option value="surgery">الجراحة</option>
                        <option value="pediatrics">طب الأطفال</option>
                        <option value="internal">الباطنة</option>
                        <option value="emergency">الطوارئ</option>
                    </select>
                </div>

                <!-- Duration Filter -->
                <div class="filter-group">
                    <label class="filter-label">المدة</label>
                    <select class="filter-select" id="durationFilter">
                        <option value="">جميع المدد</option>
                        <option value="short">قصيرة (أقل من 30 دقيقة)</option>
                        <option value="medium">متوسطة (30-60 دقيقة)</option>
                        <option value="long">طويلة (أكثر من 60 دقيقة)</option>
                    </select>
                </div>

                <!-- Level Filter -->
                <div class="filter-group">
                    <label class="filter-label">المستوى</label>
                    <select class="filter-select" id="levelFilter">
                        <option value="">جميع المستويات</option>
                        <option value="beginner">مبتدئ</option>
                        <option value="intermediate">متوسط</option>
                        <option value="advanced">متقدم</option>
                    </select>
                </div>

                <!-- Language Filter -->
                <div class="filter-group">
                    <label class="filter-label">اللغة</label>
                    <select class="filter-select" id="languageFilter">
                        <option value="">جميع اللغات</option>
                        <option value="ar">العربية</option>
                        <option value="en">الإنجليزية</option>
                        <option value="fr">الفرنسية</option>
                    </select>
                </div>

                <!-- Date Range Filter -->
                <div class="filter-group">
                    <label class="filter-label">تاريخ النشر</label>
                    <div class="date-range">
                        <input type="date" class="filter-input" id="dateFrom" placeholder="من">
                        <span class="date-separator">إلى</span>
                        <input type="date" class="filter-input" id="dateTo" placeholder="إلى">
                    </div>
                </div>

                <!-- Rating Filter -->
                <div class="filter-group">
                    <label class="filter-label">التقييم</label>
                    <div class="rating-filter">
                        <div class="rating-option" data-rating="5">
                            <div class="stars">★★★★★</div>
                            <span>5 نجوم فأكثر</span>
                        </div>
                        <div class="rating-option" data-rating="4">
                            <div class="stars">★★★★☆</div>
                            <span>4 نجوم فأكثر</span>
                        </div>
                        <div class="rating-option" data-rating="3">
                            <div class="stars">★★★☆☆</div>
                            <span>3 نجوم فأكثر</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="filter-actions">
                <button class="btn btn-outline btn-sm" onclick="clearAllFilters()">
                    <i class="material-icons">clear_all</i>
                    مسح الكل
                </button>
                <button class="btn btn-primary btn-sm" onclick="applyFilters()">
                    <i class="material-icons">check</i>
                    تطبيق التصفية
                </button>
            </div>
        </div>

        <!-- Sort Menu -->
        <div class="sort-menu" id="sortMenu" style="display: none;">
            <div class="sort-option" data-sort="newest">
                <i class="material-icons">schedule</i>
                الأحدث أولاً
            </div>
            <div class="sort-option" data-sort="oldest">
                <i class="material-icons">history</i>
                الأقدم أولاً
            </div>
            <div class="sort-option" data-sort="popular">
                <i class="material-icons">trending_up</i>
                الأكثر شعبية
            </div>
            <div class="sort-option" data-sort="rating">
                <i class="material-icons">star</i>
                الأعلى تقييماً
            </div>
            <div class="sort-option" data-sort="duration">
                <i class="material-icons">timer</i>
                حسب المدة
            </div>
            <div class="sort-option" data-sort="alphabetical">
                <i class="material-icons">sort_by_alpha</i>
                أبجدياً
            </div>
        </div>
    </div>

    <!-- Active Filters -->
    <div class="active-filters" id="activeFilters" style="display: none;">
        <div class="active-filters-content">
            <span class="active-filters-label">التصفية النشطة:</span>
            <div class="active-filters-list" id="activeFiltersList"></div>
            <button class="clear-all-filters" onclick="clearAllFilters()">
                <i class="material-icons">close</i>
                مسح الكل
            </button>
        </div>
    </div>

    <!-- Results Summary -->
    <div class="results-summary">
        <div class="results-info">
            <span class="results-count" id="resultsCount">عرض 1-12 من 156 محاضرة</span>
            <span class="results-time" id="resultsTime">تم العثور على النتائج في 0.05 ثانية</span>
        </div>
        
        <div class="view-options">
            <div class="items-per-page">
                <label>عرض:</label>
                <select id="itemsPerPage" onchange="changeItemsPerPage()">
                    <option value="12">12</option>
                    <option value="24">24</option>
                    <option value="48">48</option>
                    <option value="96">96</option>
                </select>
                <span>لكل صفحة</span>
            </div>
        </div>
    </div>

    <!-- Videos Grid/List -->
    <div class="videos-container" id="videosContainer">
        <div class="videos-grid" id="videosGrid">
            <!-- Video Card 1 -->
            <div class="video-card" data-category="cardiology" data-level="intermediate" data-duration="45" data-rating="4.8">
                <div class="video-thumbnail">
                    <img src="{{ asset('images/videos/cardiology-basics.jpg') }}" alt="أساسيات أمراض القلب" class="thumbnail-image">
                    <div class="video-overlay">
                        <button class="play-button" onclick="playVideo(1)">
                            <i class="material-icons">play_arrow</i>
                        </button>
                        <div class="video-duration">45:30</div>
                    </div>
                    <div class="video-progress">
                        <div class="progress-bar" style="width: 65%"></div>
                    </div>
                </div>
                
                <div class="video-content">
                    <div class="video-header">
                        <h3 class="video-title">أساسيات أمراض القلب والأوعية الدموية</h3>
                        <div class="video-actions">
                            <button class="action-btn" onclick="toggleBookmark(1)" title="إضافة للمفضلة">
                                <i class="material-icons">bookmark_border</i>
                            </button>
                            <button class="action-btn" onclick="shareVideo(1)" title="مشاركة">
                                <i class="material-icons">share</i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="video-meta">
                        <div class="video-instructor">
                            <img src="{{ asset('images/instructors/dr-ahmed.jpg') }}" alt="د. أحمد محمد" class="instructor-avatar">
                            <span class="instructor-name">د. أحمد محمد</span>
                        </div>
                        
                        <div class="video-stats">
                            <div class="stat-item">
                                <i class="material-icons">visibility</i>
                                <span>1,234 مشاهدة</span>
                            </div>
                            <div class="stat-item">
                                <i class="material-icons">schedule</i>
                                <span>منذ أسبوع</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="video-description">
                        محاضرة شاملة تغطي الأساسيات المهمة في أمراض القلب والأوعية الدموية، تشمل التشخيص والعلاج والوقاية.
                    </div>
                    
                    <div class="video-footer">
                        <div class="video-rating">
                            <div class="stars">
                                <i class="material-icons star filled">star</i>
                                <i class="material-icons star filled">star</i>
                                <i class="material-icons star filled">star</i>
                                <i class="material-icons star filled">star</i>
                                <i class="material-icons star half">star_half</i>
                            </div>
                            <span class="rating-value">4.8</span>
                            <span class="rating-count">(156 تقييم)</span>
                        </div>
                        
                        <div class="video-tags">
                            <span class="tag">أمراض القلب</span>
                            <span class="tag">متوسط</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Video Card 2 -->
            <div class="video-card" data-category="surgery" data-level="advanced" data-duration="75" data-rating="4.9">
                <div class="video-thumbnail">
                    <img src="{{ asset('images/videos/surgical-techniques.jpg') }}" alt="تقنيات جراحية متقدمة" class="thumbnail-image">
                    <div class="video-overlay">
                        <button class="play-button" onclick="playVideo(2)">
                            <i class="material-icons">play_arrow</i>
                        </button>
                        <div class="video-duration">1:15:20</div>
                    </div>
                    <div class="video-badge premium">مميز</div>
                </div>
                
                <div class="video-content">
                    <div class="video-header">
                        <h3 class="video-title">تقنيات جراحية متقدمة في الجراحة العامة</h3>
                        <div class="video-actions">
                            <button class="action-btn bookmarked" onclick="toggleBookmark(2)" title="محفوظ في المفضلة">
                                <i class="material-icons">bookmark</i>
                            </button>
                            <button class="action-btn" onclick="shareVideo(2)" title="مشاركة">
                                <i class="material-icons">share</i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="video-meta">
                        <div class="video-instructor">
                            <img src="{{ asset('images/instructors/dr-sara.jpg') }}" alt="د. سارة أحمد" class="instructor-avatar">
                            <span class="instructor-name">د. سارة أحمد</span>
                        </div>
                        
                        <div class="video-stats">
                            <div class="stat-item">
                                <i class="material-icons">visibility</i>
                                <span>2,567 مشاهدة</span>
                            </div>
                            <div class="stat-item">
                                <i class="material-icons">schedule</i>
                                <span>منذ 3 أيام</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="video-description">
                        شرح مفصل للتقنيات الجراحية المتقدمة مع عرض حالات عملية وتطبيقات سريرية متنوعة.
                    </div>
                    
                    <div class="video-footer">
                        <div class="video-rating">
                            <div class="stars">
                                <i class="material-icons star filled">star</i>
                                <i class="material-icons star filled">star</i>
                                <i class="material-icons star filled">star</i>
                                <i class="material-icons star filled">star</i>
                                <i class="material-icons star filled">star</i>
                            </div>
                            <span class="rating-value">4.9</span>
                            <span class="rating-count">(89 تقييم)</span>
                        </div>
                        
                        <div class="video-tags">
                            <span class="tag">جراحة</span>
                            <span class="tag">متقدم</span>
                            <span class="tag premium">مميز</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- More video cards would be dynamically loaded here -->
            <div class="video-card skeleton" style="display: none;">
                <div class="video-thumbnail skeleton-item"></div>
                <div class="video-content">
                    <div class="skeleton-item skeleton-title"></div>
                    <div class="skeleton-item skeleton-text"></div>
                    <div class="skeleton-item skeleton-text short"></div>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div class="videos-list" id="videosList" style="display: none;">
            <div class="video-list-item">
                <div class="list-thumbnail">
                    <img src="{{ asset('images/videos/cardiology-basics.jpg') }}" alt="أساسيات أمراض القلب">
                    <div class="play-overlay">
                        <i class="material-icons">play_arrow</i>
                    </div>
                    <div class="duration">45:30</div>
                </div>
                
                <div class="list-content">
                    <h3 class="list-title">أساسيات أمراض القلب والأوعية الدموية</h3>
                    <div class="list-meta">
                        <span class="instructor">د. أحمد محمد</span>
                        <span class="separator">•</span>
                        <span class="views">1,234 مشاهدة</span>
                        <span class="separator">•</span>
                        <span class="date">منذ أسبوع</span>
                    </div>
                    <p class="list-description">
                        محاضرة شاملة تغطي الأساسيات المهمة في أمراض القلب والأوعية الدموية...
                    </p>
                    <div class="list-footer">
                        <div class="rating">
                            <div class="stars">★★★★☆</div>
                            <span>4.8 (156)</span>
                        </div>
                        <div class="tags">
                            <span class="tag">أمراض القلب</span>
                            <span class="tag">متوسط</span>
                        </div>
                    </div>
                </div>
                
                <div class="list-actions">
                    <button class="action-btn" title="تشغيل">
                        <i class="material-icons">play_arrow</i>
                    </button>
                    <button class="action-btn" title="إضافة للمفضلة">
                        <i class="material-icons">bookmark_border</i>
                    </button>
                    <button class="action-btn" title="مشاركة">
                        <i class="material-icons">share</i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        <nav class="pagination">
            <button class="pagination-btn prev" disabled>
                <i class="material-icons">chevron_right</i>
                السابق
            </button>
            
            <div class="pagination-numbers">
                <button class="pagination-number active">1</button>
                <button class="pagination-number">2</button>
                <button class="pagination-number">3</button>
                <span class="pagination-dots">...</span>
                <button class="pagination-number">13</button>
            </div>
            
            <button class="pagination-btn next">
                التالي
                <i class="material-icons">chevron_left</i>
            </button>
        </nav>
        
        <div class="pagination-info">
            صفحة 1 من 13 (156 محاضرة)
        </div>
    </div>

    <!-- Loading State -->
    <div class="loading-container" id="loadingContainer" style="display: none;">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>جاري تحميل المحاضرات...</p>
        </div>
    </div>

    <!-- Empty State -->
    <div class="empty-state" id="emptyState" style="display: none;">
        <div class="empty-state-content">
            <i class="material-icons empty-icon">video_library</i>
            <h3 class="empty-title">لا توجد محاضرات</h3>
            <p class="empty-description">لم يتم العثور على محاضرات تطابق معايير البحث الخاصة بك.</p>
            <button class="btn btn-primary" onclick="clearAllFilters()">
                <i class="material-icons">refresh</i>
                مسح التصفية
            </button>
        </div>
    </div>
</div>

<!-- Video Modal -->
<div class="modal" id="videoModal" style="display: none;">
    <div class="modal-backdrop" onclick="closeVideoModal()"></div>
    <div class="modal-content video-modal-content">
        <div class="modal-header">
            <h3 class="modal-title">تشغيل المحاضرة</h3>
            <button class="modal-close" onclick="closeVideoModal()">
                <i class="material-icons">close</i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="video-player">
                <video id="videoPlayer" controls width="100%" height="400">
                    <source src="" type="video/mp4">
                    متصفحك لا يدعم تشغيل الفيديو.
                </video>
            </div>
            
            <div class="video-info">
                <h4 id="modalVideoTitle">عنوان المحاضرة</h4>
                <div class="video-details">
                    <span id="modalInstructor">المحاضر</span>
                    <span class="separator">•</span>
                    <span id="modalDuration">المدة</span>
                    <span class="separator">•</span>
                    <span id="modalViews">المشاهدات</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @vite(['resources/js/pages/videos.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeVideosPage();
        });
    </script>
@endpush
