/**
 * Arab-Med Videos Page JavaScript
 * Handles video browsing, filtering, search, and playback functionality
 */

class ArabMedVideos {
    constructor() {
        this.currentView = 'grid'; // 'grid' or 'list'
        this.currentSort = 'newest';
        this.currentFilters = {};
        this.searchQuery = '';
        this.currentPage = 1;
        this.itemsPerPage = 12;
        this.totalItems = 0;
        this.videos = [];
        this.searchTimeout = null;
        
        this.init();
    }

    init() {
        this.setupSearch();
        this.setupFilters();
        this.setupSorting();
        this.setupViewToggle();
        this.setupPagination();
        this.setupVideoActions();
        this.setupKeyboardShortcuts();
        this.loadVideos();
    }

    setupSearch() {
        const searchInput = document.getElementById('videoSearch');
        const searchClear = document.querySelector('.search-clear');
        
        if (!searchInput) return;

        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            
            // Show/hide clear button
            if (query) {
                searchClear.style.display = 'block';
            } else {
                searchClear.style.display = 'none';
            }
            
            // Debounced search
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.searchQuery = query;
                this.currentPage = 1;
                this.performSearch();
            }, 300);
        });

        // Search suggestions
        searchInput.addEventListener('focus', () => {
            this.showSearchSuggestions();
        });

        searchInput.addEventListener('blur', () => {
            setTimeout(() => this.hideSearchSuggestions(), 200);
        });
    }

    setupFilters() {
        // Filter toggles
        const filterToggle = document.querySelector('.filter-toggle');
        if (filterToggle) {
            filterToggle.addEventListener('click', () => {
                this.toggleFilters();
            });
        }

        // Filter inputs
        const filterInputs = document.querySelectorAll('.filter-select, .filter-input');
        filterInputs.forEach(input => {
            input.addEventListener('change', () => {
                this.updateFilters();
            });
        });

        // Rating filter
        const ratingOptions = document.querySelectorAll('.rating-option');
        ratingOptions.forEach(option => {
            option.addEventListener('click', () => {
                this.selectRating(option);
            });
        });

        // Filter actions
        const clearFiltersBtn = document.querySelector('[onclick="clearAllFilters()"]');
        const applyFiltersBtn = document.querySelector('[onclick="applyFilters()"]');
        
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.clearAllFilters();
            });
        }
        
        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.applyFilters();
            });
        }
    }

    setupSorting() {
        const sortToggle = document.querySelector('.filter-toggle:last-child');
        if (sortToggle) {
            sortToggle.addEventListener('click', () => {
                this.toggleSortMenu();
            });
        }

        const sortOptions = document.querySelectorAll('.sort-option');
        sortOptions.forEach(option => {
            option.addEventListener('click', () => {
                this.selectSort(option);
            });
        });

        // Close sort menu when clicking outside
        document.addEventListener('click', (e) => {
            const sortMenu = document.getElementById('sortMenu');
            if (sortMenu && !e.target.closest('.filter-toggle') && !e.target.closest('.sort-menu')) {
                sortMenu.style.display = 'none';
            }
        });
    }

    setupViewToggle() {
        const viewToggleBtn = document.querySelector('[onclick="toggleViewMode()"]');
        if (viewToggleBtn) {
            viewToggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleViewMode();
            });
        }
    }

    setupPagination() {
        const itemsPerPageSelect = document.getElementById('itemsPerPage');
        if (itemsPerPageSelect) {
            itemsPerPageSelect.addEventListener('change', (e) => {
                this.changeItemsPerPage(parseInt(e.target.value));
            });
        }

        // Pagination buttons will be set up dynamically
    }

    setupVideoActions() {
        // Video cards will be set up dynamically
        this.setupVideoCardListeners();
    }

    setupVideoCardListeners() {
        // Play buttons
        document.querySelectorAll('.play-button').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const videoId = this.getVideoIdFromElement(btn);
                this.playVideo(videoId);
            });
        });

        // Bookmark buttons
        document.querySelectorAll('.action-btn[title*="مفضلة"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const videoId = this.getVideoIdFromElement(btn);
                this.toggleBookmark(videoId);
            });
        });

        // Share buttons
        document.querySelectorAll('.action-btn[title="مشاركة"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const videoId = this.getVideoIdFromElement(btn);
                this.shareVideo(videoId);
            });
        });

        // Video card clicks
        document.querySelectorAll('.video-card, .video-list-item').forEach(card => {
            card.addEventListener('click', () => {
                const videoId = this.getVideoIdFromElement(card);
                this.playVideo(videoId);
            });
        });
    }

    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Search focus (Ctrl/Cmd + K)
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('videoSearch')?.focus();
            }
            
            // Toggle view (V key)
            if (e.key === 'v' && !e.target.matches('input, textarea')) {
                e.preventDefault();
                this.toggleViewMode();
            }
            
            // Clear filters (Escape)
            if (e.key === 'Escape') {
                const modal = document.getElementById('videoModal');
                if (modal && modal.style.display !== 'none') {
                    this.closeVideoModal();
                } else {
                    this.clearAllFilters();
                }
            }
        });
    }

    // Search functionality
    performSearch() {
        this.showLoading();
        
        // Simulate API call
        setTimeout(() => {
            this.filterAndSortVideos();
            this.updateResults();
            this.hideLoading();
        }, 500);
    }

    showSearchSuggestions() {
        const suggestions = document.getElementById('searchSuggestions');
        if (!suggestions) return;

        const sampleSuggestions = [
            'أمراض القلب',
            'الجراحة العامة',
            'طب الأطفال',
            'الأعصاب',
            'الباطنة'
        ];

        suggestions.innerHTML = sampleSuggestions.map(suggestion => 
            `<div class="suggestion-item" onclick="selectSuggestion('${suggestion}')">${suggestion}</div>`
        ).join('');
        
        suggestions.style.display = 'block';
    }

    hideSearchSuggestions() {
        const suggestions = document.getElementById('searchSuggestions');
        if (suggestions) {
            suggestions.style.display = 'none';
        }
    }

    selectSuggestion(suggestion) {
        const searchInput = document.getElementById('videoSearch');
        if (searchInput) {
            searchInput.value = suggestion;
            this.searchQuery = suggestion;
            this.performSearch();
        }
        this.hideSearchSuggestions();
    }

    clearSearch() {
        const searchInput = document.getElementById('videoSearch');
        const searchClear = document.querySelector('.search-clear');
        
        if (searchInput) {
            searchInput.value = '';
            this.searchQuery = '';
        }
        
        if (searchClear) {
            searchClear.style.display = 'none';
        }
        
        this.performSearch();
    }

    // Filter functionality
    toggleFilters() {
        const advancedFilters = document.getElementById('advancedFilters');
        const filterToggle = document.querySelector('.filter-toggle');
        
        if (advancedFilters.style.display === 'none' || !advancedFilters.style.display) {
            advancedFilters.style.display = 'block';
            filterToggle.classList.add('active');
        } else {
            advancedFilters.style.display = 'none';
            filterToggle.classList.remove('active');
        }
    }

    updateFilters() {
        const filters = {};
        
        // Collect filter values
        const categoryFilter = document.getElementById('categoryFilter');
        const durationFilter = document.getElementById('durationFilter');
        const levelFilter = document.getElementById('levelFilter');
        const languageFilter = document.getElementById('languageFilter');
        const dateFrom = document.getElementById('dateFrom');
        const dateTo = document.getElementById('dateTo');
        
        if (categoryFilter?.value) filters.category = categoryFilter.value;
        if (durationFilter?.value) filters.duration = durationFilter.value;
        if (levelFilter?.value) filters.level = levelFilter.value;
        if (languageFilter?.value) filters.language = languageFilter.value;
        if (dateFrom?.value) filters.dateFrom = dateFrom.value;
        if (dateTo?.value) filters.dateTo = dateTo.value;
        
        // Get selected rating
        const selectedRating = document.querySelector('.rating-option.selected');
        if (selectedRating) {
            filters.rating = selectedRating.dataset.rating;
        }
        
        this.currentFilters = filters;
        this.updateFilterCount();
        this.updateActiveFilters();
    }

    selectRating(option) {
        // Remove previous selection
        document.querySelectorAll('.rating-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        
        // Add selection to clicked option
        option.classList.add('selected');
        
        this.updateFilters();
    }

    updateFilterCount() {
        const filterCount = document.getElementById('filterCount');
        const activeCount = Object.keys(this.currentFilters).length;
        
        if (filterCount) {
            if (activeCount > 0) {
                filterCount.textContent = activeCount;
                filterCount.style.display = 'inline';
            } else {
                filterCount.style.display = 'none';
            }
        }
    }

    updateActiveFilters() {
        const activeFilters = document.getElementById('activeFilters');
        const activeFiltersList = document.getElementById('activeFiltersList');
        
        if (!activeFilters || !activeFiltersList) return;
        
        const filterCount = Object.keys(this.currentFilters).length;
        
        if (filterCount > 0) {
            activeFilters.style.display = 'block';
            
            const filterTags = Object.entries(this.currentFilters).map(([key, value]) => {
                const label = this.getFilterLabel(key, value);
                return `
                    <div class="active-filter-tag">
                        ${label}
                        <button onclick="removeFilter('${key}')" style="background: none; border: none; color: inherit; margin-right: 0.25rem;">×</button>
                    </div>
                `;
            }).join('');
            
            activeFiltersList.innerHTML = filterTags;
        } else {
            activeFilters.style.display = 'none';
        }
    }

    getFilterLabel(key, value) {
        const labels = {
            category: {
                cardiology: 'أمراض القلب',
                neurology: 'الأعصاب',
                surgery: 'الجراحة',
                pediatrics: 'طب الأطفال',
                internal: 'الباطنة',
                emergency: 'الطوارئ'
            },
            duration: {
                short: 'قصيرة',
                medium: 'متوسطة',
                long: 'طويلة'
            },
            level: {
                beginner: 'مبتدئ',
                intermediate: 'متوسط',
                advanced: 'متقدم'
            },
            language: {
                ar: 'العربية',
                en: 'الإنجليزية',
                fr: 'الفرنسية'
            }
        };
        
        if (key === 'rating') {
            return `${value} نجوم فأكثر`;
        }
        
        return labels[key]?.[value] || value;
    }

    removeFilter(key) {
        delete this.currentFilters[key];
        
        // Clear the corresponding form field
        const element = document.getElementById(`${key}Filter`);
        if (element) {
            element.value = '';
        }
        
        // Clear rating selection
        if (key === 'rating') {
            document.querySelectorAll('.rating-option').forEach(opt => {
                opt.classList.remove('selected');
            });
        }
        
        this.updateFilterCount();
        this.updateActiveFilters();
        this.applyFilters();
    }

    clearAllFilters() {
        this.currentFilters = {};
        
        // Clear all filter inputs
        document.querySelectorAll('.filter-select, .filter-input').forEach(input => {
            input.value = '';
        });
        
        // Clear rating selection
        document.querySelectorAll('.rating-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        
        this.updateFilterCount();
        this.updateActiveFilters();
        this.applyFilters();
    }

    applyFilters() {
        this.currentPage = 1;
        this.performSearch();
    }

    // Sorting functionality
    toggleSortMenu() {
        const sortMenu = document.getElementById('sortMenu');
        if (sortMenu) {
            sortMenu.style.display = sortMenu.style.display === 'none' ? 'block' : 'none';
        }
    }

    selectSort(option) {
        const sortValue = option.dataset.sort;
        
        // Update active sort option
        document.querySelectorAll('.sort-option').forEach(opt => {
            opt.classList.remove('active');
        });
        option.classList.add('active');
        
        this.currentSort = sortValue;
        this.toggleSortMenu();
        this.performSearch();
    }

    // View mode functionality
    toggleViewMode() {
        const videosGrid = document.getElementById('videosGrid');
        const videosList = document.getElementById('videosList');
        const viewModeIcon = document.getElementById('viewModeIcon');
        const viewModeText = document.getElementById('viewModeText');
        
        if (this.currentView === 'grid') {
            this.currentView = 'list';
            videosGrid.style.display = 'none';
            videosList.style.display = 'block';
            viewModeIcon.textContent = 'view_list';
            viewModeText.textContent = 'عرض قائمة';
        } else {
            this.currentView = 'grid';
            videosGrid.style.display = 'grid';
            videosList.style.display = 'none';
            viewModeIcon.textContent = 'grid_view';
            viewModeText.textContent = 'عرض شبكي';
        }
        
        // Save preference
        localStorage.setItem('videosViewMode', this.currentView);
    }

    // Pagination functionality
    changeItemsPerPage(newCount) {
        this.itemsPerPage = newCount;
        this.currentPage = 1;
        this.performSearch();
    }

    setupPaginationButtons() {
        const totalPages = Math.ceil(this.totalItems / this.itemsPerPage);
        
        // Update pagination buttons (simplified implementation)
        const prevBtn = document.querySelector('.pagination-btn.prev');
        const nextBtn = document.querySelector('.pagination-btn.next');
        
        if (prevBtn) {
            prevBtn.disabled = this.currentPage === 1;
            prevBtn.onclick = () => this.goToPage(this.currentPage - 1);
        }
        
        if (nextBtn) {
            nextBtn.disabled = this.currentPage === totalPages;
            nextBtn.onclick = () => this.goToPage(this.currentPage + 1);
        }
        
        // Update pagination info
        const paginationInfo = document.querySelector('.pagination-info');
        if (paginationInfo) {
            paginationInfo.textContent = `صفحة ${this.currentPage} من ${totalPages} (${this.totalItems} محاضرة)`;
        }
    }

    goToPage(page) {
        const totalPages = Math.ceil(this.totalItems / this.itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            this.currentPage = page;
            this.performSearch();
        }
    }

    // Video actions
    playVideo(videoId) {
        const modal = document.getElementById('videoModal');
        const videoPlayer = document.getElementById('videoPlayer');
        const modalTitle = document.getElementById('modalVideoTitle');
        
        if (modal && videoPlayer) {
            // Set video source (would be dynamic in real implementation)
            videoPlayer.src = `/videos/${videoId}.mp4`;
            
            // Set modal title
            if (modalTitle) {
                modalTitle.textContent = `محاضرة رقم ${videoId}`;
            }
            
            modal.style.display = 'flex';
            
            // Track video view
            this.trackVideoView(videoId);
        }
    }

    closeVideoModal() {
        const modal = document.getElementById('videoModal');
        const videoPlayer = document.getElementById('videoPlayer');
        
        if (modal) {
            modal.style.display = 'none';
        }
        
        if (videoPlayer) {
            videoPlayer.pause();
            videoPlayer.src = '';
        }
    }

    toggleBookmark(videoId) {
        const bookmarkBtn = document.querySelector(`[onclick="toggleBookmark(${videoId})"]`);
        if (!bookmarkBtn) return;
        
        const icon = bookmarkBtn.querySelector('.material-icons');
        const isBookmarked = bookmarkBtn.classList.contains('bookmarked');
        
        if (isBookmarked) {
            bookmarkBtn.classList.remove('bookmarked');
            icon.textContent = 'bookmark_border';
            bookmarkBtn.title = 'إضافة للمفضلة';
            this.showNotification('تم إزالة المحاضرة من المفضلة', 'info');
        } else {
            bookmarkBtn.classList.add('bookmarked');
            icon.textContent = 'bookmark';
            bookmarkBtn.title = 'محفوظ في المفضلة';
            this.showNotification('تم إضافة المحاضرة للمفضلة', 'success');
        }
        
        // Save to backend
        this.saveBookmark(videoId, !isBookmarked);
    }

    shareVideo(videoId) {
        const videoUrl = `${window.location.origin}/videos/${videoId}`;
        
        if (navigator.share) {
            navigator.share({
                title: 'محاضرة طبية - Arab-Med',
                text: 'شاهد هذه المحاضرة الطبية المفيدة',
                url: videoUrl
            });
        } else {
            // Fallback to clipboard
            navigator.clipboard.writeText(videoUrl).then(() => {
                this.showNotification('تم نسخ رابط المحاضرة', 'success');
            });
        }
    }

    // Data management
    loadVideos() {
        this.showLoading();
        
        // Simulate API call
        setTimeout(() => {
            this.videos = this.generateSampleVideos();
            this.filterAndSortVideos();
            this.updateResults();
            this.hideLoading();
        }, 1000);
    }

    generateSampleVideos() {
        // Sample video data
        return Array.from({ length: 156 }, (_, i) => ({
            id: i + 1,
            title: `محاضرة طبية رقم ${i + 1}`,
            instructor: `د. محاضر ${i + 1}`,
            category: ['cardiology', 'surgery', 'neurology', 'pediatrics'][i % 4],
            level: ['beginner', 'intermediate', 'advanced'][i % 3],
            duration: 30 + (i % 90),
            rating: 3 + (i % 3),
            views: 100 + (i * 50),
            date: new Date(Date.now() - (i * 86400000)),
            language: 'ar',
            thumbnail: `/images/videos/video-${i + 1}.jpg`
        }));
    }

    filterAndSortVideos() {
        let filteredVideos = [...this.videos];
        
        // Apply search filter
        if (this.searchQuery) {
            filteredVideos = filteredVideos.filter(video =>
                video.title.includes(this.searchQuery) ||
                video.instructor.includes(this.searchQuery)
            );
        }
        
        // Apply filters
        Object.entries(this.currentFilters).forEach(([key, value]) => {
            filteredVideos = filteredVideos.filter(video => {
                switch (key) {
                    case 'category':
                        return video.category === value;
                    case 'level':
                        return video.level === value;
                    case 'duration':
                        if (value === 'short') return video.duration < 30;
                        if (value === 'medium') return video.duration >= 30 && video.duration <= 60;
                        if (value === 'long') return video.duration > 60;
                        return true;
                    case 'rating':
                        return video.rating >= parseInt(value);
                    default:
                        return true;
                }
            });
        });
        
        // Apply sorting
        filteredVideos.sort((a, b) => {
            switch (this.currentSort) {
                case 'newest':
                    return b.date - a.date;
                case 'oldest':
                    return a.date - b.date;
                case 'popular':
                    return b.views - a.views;
                case 'rating':
                    return b.rating - a.rating;
                case 'duration':
                    return a.duration - b.duration;
                case 'alphabetical':
                    return a.title.localeCompare(b.title);
                default:
                    return 0;
            }
        });
        
        this.totalItems = filteredVideos.length;
        
        // Apply pagination
        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
        const endIndex = startIndex + this.itemsPerPage;
        this.currentVideos = filteredVideos.slice(startIndex, endIndex);
    }

    updateResults() {
        this.updateResultsSummary();
        this.renderVideos();
        this.setupPaginationButtons();
        this.setupVideoCardListeners();
        
        // Show empty state if no results
        if (this.totalItems === 0) {
            this.showEmptyState();
        } else {
            this.hideEmptyState();
        }
    }

    updateResultsSummary() {
        const resultsCount = document.getElementById('resultsCount');
        const resultsTime = document.getElementById('resultsTime');
        
        if (resultsCount) {
            const start = (this.currentPage - 1) * this.itemsPerPage + 1;
            const end = Math.min(start + this.itemsPerPage - 1, this.totalItems);
            resultsCount.textContent = `عرض ${start}-${end} من ${this.totalItems} محاضرة`;
        }
        
        if (resultsTime) {
            resultsTime.textContent = 'تم العثور على النتائج في 0.05 ثانية';
        }
    }

    renderVideos() {
        // This would render the filtered videos
        // For now, we'll just update the existing cards
        console.log(`Rendering ${this.currentVideos.length} videos`);
    }

    // UI state management
    showLoading() {
        const loadingContainer = document.getElementById('loadingContainer');
        const videosContainer = document.getElementById('videosContainer');
        
        if (loadingContainer) loadingContainer.style.display = 'flex';
        if (videosContainer) videosContainer.style.opacity = '0.5';
    }

    hideLoading() {
        const loadingContainer = document.getElementById('loadingContainer');
        const videosContainer = document.getElementById('videosContainer');
        
        if (loadingContainer) loadingContainer.style.display = 'none';
        if (videosContainer) videosContainer.style.opacity = '1';
    }

    showEmptyState() {
        const emptyState = document.getElementById('emptyState');
        const videosContainer = document.getElementById('videosContainer');
        
        if (emptyState) emptyState.style.display = 'flex';
        if (videosContainer) videosContainer.style.display = 'none';
    }

    hideEmptyState() {
        const emptyState = document.getElementById('emptyState');
        const videosContainer = document.getElementById('videosContainer');
        
        if (emptyState) emptyState.style.display = 'none';
        if (videosContainer) videosContainer.style.display = 'block';
    }

    showNotification(message, type = 'info') {
        // Create notification (simplified)
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-md);
            padding: 1rem;
            box-shadow: var(--shadow-lg);
            z-index: 1000;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Utility methods
    getVideoIdFromElement(element) {
        // Extract video ID from element (simplified)
        const card = element.closest('.video-card, .video-list-item');
        return card ? Array.from(card.parentNode.children).indexOf(card) + 1 : 1;
    }

    trackVideoView(videoId) {
        // Track video view analytics
        console.log(`Video ${videoId} viewed`);
    }

    saveBookmark(videoId, isBookmarked) {
        // Save bookmark to backend
        console.log(`Video ${videoId} bookmark: ${isBookmarked}`);
    }
}

// Global functions for backward compatibility
window.toggleViewMode = function() {
    if (window.videosPage) {
        window.videosPage.toggleViewMode();
    }
};

window.toggleFilters = function() {
    if (window.videosPage) {
        window.videosPage.toggleFilters();
    }
};

window.toggleSortMenu = function() {
    if (window.videosPage) {
        window.videosPage.toggleSortMenu();
    }
};

window.clearSearch = function() {
    if (window.videosPage) {
        window.videosPage.clearSearch();
    }
};

window.clearAllFilters = function() {
    if (window.videosPage) {
        window.videosPage.clearAllFilters();
    }
};

window.applyFilters = function() {
    if (window.videosPage) {
        window.videosPage.applyFilters();
    }
};

window.changeItemsPerPage = function() {
    if (window.videosPage) {
        const select = document.getElementById('itemsPerPage');
        window.videosPage.changeItemsPerPage(parseInt(select.value));
    }
};

window.playVideo = function(videoId) {
    if (window.videosPage) {
        window.videosPage.playVideo(videoId);
    }
};

window.closeVideoModal = function() {
    if (window.videosPage) {
        window.videosPage.closeVideoModal();
    }
};

window.toggleBookmark = function(videoId) {
    if (window.videosPage) {
        window.videosPage.toggleBookmark(videoId);
    }
};

window.shareVideo = function(videoId) {
    if (window.videosPage) {
        window.videosPage.shareVideo(videoId);
    }
};

window.selectSuggestion = function(suggestion) {
    if (window.videosPage) {
        window.videosPage.selectSuggestion(suggestion);
    }
};

window.removeFilter = function(key) {
    if (window.videosPage) {
        window.videosPage.removeFilter(key);
    }
};

// Initialize when DOM is ready
function initializeVideosPage() {
    window.videosPage = new ArabMedVideos();
    
    // Load saved view mode
    const savedViewMode = localStorage.getItem('videosViewMode');
    if (savedViewMode && savedViewMode !== window.videosPage.currentView) {
        window.videosPage.toggleViewMode();
    }
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ArabMedVideos;
}
