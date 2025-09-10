/**
 * Arab-Med Guest Layout JavaScript
 * Handles all interactive functionality for public-facing pages
 */

class ArabMedGuestLayout {
    constructor() {
        this.navbar = document.querySelector('.guest-navbar');
        this.mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        this.mobileMenu = document.querySelector('.navbar-nav');
        this.languageToggle = document.querySelector('.language-toggle');
        this.languageDropdown = document.querySelector('.language-dropdown');
        this.backToTopBtn = document.querySelector('.back-to-top');
        this.cookieConsent = document.querySelector('.cookie-consent');
        this.newsletterForm = document.querySelector('.newsletter-form');
        this.searchInput = document.querySelector('.search-input');
        
        this.isMenuOpen = false;
        this.isLanguageOpen = false;
        this.scrollThreshold = 100;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.handleNavbarScroll();
        this.showCookieConsent();
        this.initializeBackToTop();
    }

    setupEventListeners() {
        // Navbar scroll effect
        window.addEventListener('scroll', this.handleNavbarScroll.bind(this));
        
        // Mobile menu toggle
        if (this.mobileMenuToggle && this.mobileMenu) {
            this.mobileMenuToggle.addEventListener('click', this.toggleMobileMenu.bind(this));
        }
        
        // Language switcher
        if (this.languageToggle && this.languageDropdown) {
            this.languageToggle.addEventListener('click', this.toggleLanguageDropdown.bind(this));
        }
        
        // Back to top button
        if (this.backToTopBtn) {
            this.backToTopBtn.addEventListener('click', this.scrollToTop.bind(this));
            window.addEventListener('scroll', this.toggleBackToTop.bind(this));
        }
        
        // Cookie consent
        this.setupCookieConsent();
        
        // Newsletter subscription
        if (this.newsletterForm) {
            this.newsletterForm.addEventListener('submit', this.handleNewsletterSubmit.bind(this));
        }
        
        // Global search
        if (this.searchInput) {
            this.setupGlobalSearch();
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', this.handleOutsideClick.bind(this));
        
        // Keyboard shortcuts
        document.addEventListener('keydown', this.handleKeyboardShortcuts.bind(this));
        
        // Window resize handler
        window.addEventListener('resize', this.handleWindowResize.bind(this));
    }

    handleNavbarScroll() {
        if (!this.navbar) return;
        
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > this.scrollThreshold) {
            this.navbar.classList.add('scrolled');
        } else {
            this.navbar.classList.remove('scrolled');
        }
    }

    toggleMobileMenu() {
        this.isMenuOpen = !this.isMenuOpen;
        
        if (this.isMenuOpen) {
            this.mobileMenu.classList.add('show');
            this.mobileMenuToggle.innerHTML = '<i class="material-icons">close</i>';
            document.body.style.overflow = 'hidden';
        } else {
            this.mobileMenu.classList.remove('show');
            this.mobileMenuToggle.innerHTML = '<i class="material-icons">menu</i>';
            document.body.style.overflow = '';
        }
    }

    toggleLanguageDropdown(e) {
        e.stopPropagation();
        this.isLanguageOpen = !this.isLanguageOpen;
        
        if (this.isLanguageOpen) {
            this.languageDropdown.classList.add('show');
        } else {
            this.languageDropdown.classList.remove('show');
        }
    }

    handleOutsideClick(e) {
        // Close language dropdown
        if (this.languageDropdown && 
            !this.languageToggle.contains(e.target) && 
            !this.languageDropdown.contains(e.target)) {
            this.languageDropdown.classList.remove('show');
            this.isLanguageOpen = false;
        }
        
        // Close mobile menu
        if (this.mobileMenu && 
            this.isMenuOpen && 
            !this.mobileMenuToggle.contains(e.target) && 
            !this.mobileMenu.contains(e.target)) {
            this.toggleMobileMenu();
        }
    }

    handleKeyboardShortcuts(e) {
        // Escape key closes dropdowns and mobile menu
        if (e.key === 'Escape') {
            if (this.isLanguageOpen) {
                this.languageDropdown.classList.remove('show');
                this.isLanguageOpen = false;
            }
            
            if (this.isMenuOpen) {
                this.toggleMobileMenu();
            }
        }
        
        // Ctrl/Cmd + K opens search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            if (this.searchInput) {
                this.searchInput.focus();
            }
        }
    }

    handleWindowResize() {
        // Close mobile menu on desktop
        if (window.innerWidth > 768 && this.isMenuOpen) {
            this.toggleMobileMenu();
        }
    }

    // Back to top functionality
    initializeBackToTop() {
        this.toggleBackToTop();
    }

    toggleBackToTop() {
        if (!this.backToTopBtn) return;
        
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 300) {
            this.backToTopBtn.classList.add('show');
        } else {
            this.backToTopBtn.classList.remove('show');
        }
    }

    scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // Cookie consent functionality
    setupCookieConsent() {
        const acceptBtn = document.querySelector('.cookie-btn-accept');
        const settingsBtn = document.querySelector('.cookie-btn-settings');
        
        if (acceptBtn) {
            acceptBtn.addEventListener('click', this.acceptCookies.bind(this));
        }
        
        if (settingsBtn) {
            settingsBtn.addEventListener('click', this.showCookieSettings.bind(this));
        }
    }

    showCookieConsent() {
        if (!this.cookieConsent) return;
        
        // Check if user has already made a choice
        const cookieChoice = localStorage.getItem('cookie_consent');
        
        if (!cookieChoice) {
            setTimeout(() => {
                this.cookieConsent.classList.add('show');
            }, 2000); // Show after 2 seconds
        }
    }

    acceptCookies() {
        localStorage.setItem('cookie_consent', 'accepted');
        localStorage.setItem('cookie_consent_date', new Date().toISOString());
        
        this.cookieConsent.classList.remove('show');
        
        // Enable analytics and other cookies here
        this.enableAnalytics();
    }

    showCookieSettings() {
        // This would typically open a modal with detailed cookie settings
        // For now, we'll just accept essential cookies
        localStorage.setItem('cookie_consent', 'essential_only');
        localStorage.setItem('cookie_consent_date', new Date().toISOString());
        
        this.cookieConsent.classList.remove('show');
    }

    enableAnalytics() {
        // Enable Google Analytics or other tracking scripts
        console.log('Analytics enabled');
        
        // Example: Load Google Analytics
        if (typeof gtag !== 'undefined') {
            gtag('consent', 'update', {
                'analytics_storage': 'granted'
            });
        }
    }

    // Newsletter subscription
    async handleNewsletterSubmit(e) {
        e.preventDefault();
        
        const emailInput = this.newsletterForm.querySelector('.newsletter-input');
        const submitBtn = this.newsletterForm.querySelector('.newsletter-btn');
        const email = emailInput.value.trim();
        
        if (!email) {
            this.showAlert('يرجى إدخال عنوان بريد إلكتروني صحيح', 'error');
            return;
        }
        
        if (!this.isValidEmail(email)) {
            this.showAlert('يرجى إدخال عنوان بريد إلكتروني صحيح', 'error');
            return;
        }
        
        // Show loading state
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'جاري الإرسال...';
        submitBtn.disabled = true;
        
        try {
            const response = await fetch('/api/newsletter/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ email })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                this.showAlert('تم الاشتراك بنجاح! شكراً لك', 'success');
                emailInput.value = '';
            } else {
                this.showAlert(data.message || 'حدث خطأ أثناء الاشتراك', 'error');
            }
        } catch (error) {
            console.error('Newsletter subscription error:', error);
            this.showAlert('حدث خطأ أثناء الاشتراك', 'error');
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    }

    // Global search functionality
    setupGlobalSearch() {
        let searchTimeout;
        
        this.searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            
            if (query.length < 2) {
                this.hideSearchResults();
                return;
            }
            
            searchTimeout = setTimeout(() => {
                this.performSearch(query);
            }, 300);
        });
        
        this.searchInput.addEventListener('focus', () => {
            const query = this.searchInput.value.trim();
            if (query.length >= 2) {
                this.showSearchResults();
            }
        });
        
        this.searchInput.addEventListener('blur', () => {
            // Delay hiding to allow clicking on results
            setTimeout(() => {
                this.hideSearchResults();
            }, 200);
        });
    }

    async performSearch(query) {
        try {
            const response = await fetch(`/api/search?q=${encodeURIComponent(query)}&limit=5`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                this.displaySearchResults(data.results);
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    }

    displaySearchResults(results) {
        let resultsContainer = document.querySelector('.search-results');
        
        if (!resultsContainer) {
            resultsContainer = document.createElement('div');
            resultsContainer.className = 'search-results';
            this.searchInput.parentNode.appendChild(resultsContainer);
        }
        
        if (results.length === 0) {
            resultsContainer.innerHTML = '<div class="search-no-results">لا توجد نتائج</div>';
        } else {
            resultsContainer.innerHTML = results.map(result => `
                <a href="${result.url}" class="search-result-item">
                    <div class="search-result-title">${this.escapeHtml(result.title)}</div>
                    <div class="search-result-excerpt">${this.escapeHtml(result.excerpt)}</div>
                </a>
            `).join('');
        }
        
        this.showSearchResults();
    }

    showSearchResults() {
        const resultsContainer = document.querySelector('.search-results');
        if (resultsContainer) {
            resultsContainer.style.display = 'block';
        }
    }

    hideSearchResults() {
        const resultsContainer = document.querySelector('.search-results');
        if (resultsContainer) {
            resultsContainer.style.display = 'none';
        }
    }

    // Language switching
    async switchLanguage(locale) {
        try {
            const response = await fetch('/api/locale', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ locale })
            });
            
            if (response.ok) {
                window.location.reload();
            }
        } catch (error) {
            console.error('Language switch error:', error);
        }
    }

    // Utility functions
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    showAlert(message, type = 'info') {
        // Create alert element
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible`;
        alert.innerHTML = `
            <div class="alert-content">
                <i class="material-icons alert-icon">${this.getAlertIcon(type)}</i>
                <span>${message}</span>
            </div>
            <button type="button" class="alert-close" onclick="this.parentElement.remove()">
                <i class="material-icons">close</i>
            </button>
        `;
        
        // Add to page
        let alertContainer = document.querySelector('.alert-container');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.className = 'alert-container';
            document.body.appendChild(alertContainer);
        }
        
        alertContainer.appendChild(alert);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }

    getAlertIcon(type) {
        const icons = {
            success: 'check_circle',
            error: 'error',
            warning: 'warning',
            info: 'info'
        };
        return icons[type] || 'info';
    }

    // Public API methods
    static getInstance() {
        if (!window.arabMedGuestLayout) {
            window.arabMedGuestLayout = new ArabMedGuestLayout();
        }
        return window.arabMedGuestLayout;
    }

    // Method to show alerts from outside
    static showAlert(message, type = 'info') {
        const instance = ArabMedGuestLayout.getInstance();
        instance.showAlert(message, type);
    }

    // Method to switch language from outside
    static switchLanguage(locale) {
        const instance = ArabMedGuestLayout.getInstance();
        return instance.switchLanguage(locale);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    ArabMedGuestLayout.getInstance();
});

// Add language option click handlers
document.addEventListener('click', (e) => {
    if (e.target.matches('.language-option')) {
        e.preventDefault();
        const locale = e.target.getAttribute('data-locale');
        if (locale) {
            ArabMedGuestLayout.switchLanguage(locale);
        }
    }
});

// Add search results styles dynamically
const searchResultsStyles = `
<style>
.search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.search-result-item {
    display: block;
    padding: 1rem;
    text-decoration: none;
    color: var(--text-color);
    border-bottom: 1px solid var(--border-color);
    transition: background 0.3s ease;
}

.search-result-item:last-child {
    border-bottom: none;
}

.search-result-item:hover {
    background: var(--primary-color-light);
}

.search-result-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--primary-color);
}

.search-result-excerpt {
    font-size: 0.875rem;
    color: var(--text-muted);
    line-height: 1.4;
}

.search-no-results {
    padding: 1rem;
    text-align: center;
    color: var(--text-muted);
    font-style: italic;
}

.alert-container {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 9999;
    max-width: 400px;
}

.alert-dismissible {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    animation: slideInRight 0.3s ease;
}

.alert-content {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-close {
    background: none;
    border: none;
    color: inherit;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: background 0.3s ease;
}

.alert-close:hover {
    background: rgba(0, 0, 0, 0.1);
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

[dir="rtl"] .alert-container {
    right: auto;
    left: 1rem;
}

[dir="rtl"] @keyframes slideInRight {
    from {
        transform: translateX(-100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>
`;

// Inject styles
document.head.insertAdjacentHTML('beforeend', searchResultsStyles);

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ArabMedGuestLayout;
}
