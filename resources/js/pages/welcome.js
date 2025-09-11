/**
 * Welcome Page JavaScript
 * Handles interactive features for the Arab-Med landing page
 */

class WelcomePage {
    constructor() {
        this.isRTL = document.documentElement.dir === 'rtl' || document.documentElement.lang === 'ar';
        this.scrollThreshold = 100;
        this.animationObserver = null;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeAnimations();
        this.initializeCounters();
        this.setupSmoothScrolling();
        this.initializeIntersectionObserver();
        this.setupFormHandlers();
        this.initializeTooltips();
        this.setupKeyboardNavigation();
    }

    setupEventListeners() {
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    this.smoothScrollTo(target);
                }
            });
        });

        // Hero CTA buttons
        document.querySelectorAll('.hero-actions .btn').forEach(btn => {
            btn.addEventListener('click', this.handleCTAClick.bind(this));
        });

        // Feature cards hover effects
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', this.handleCardHover.bind(this));
            card.addEventListener('mouseleave', this.handleCardLeave.bind(this));
        });

        // Testimonial cards
        document.querySelectorAll('.testimonial-card').forEach(card => {
            card.addEventListener('click', this.handleTestimonialClick.bind(this));
        });

        // Newsletter form
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', this.handleNewsletterSubmit.bind(this));
        }

        // Window events
        window.addEventListener('scroll', this.handleScroll.bind(this));
        window.addEventListener('resize', this.handleResize.bind(this));
        
        // Keyboard shortcuts
        document.addEventListener('keydown', this.handleKeyboardShortcuts.bind(this));
    }

    initializeAnimations() {
        // Add animation classes to elements
        const animatedElements = [
            { selector: '.hero-title', animation: 'fadeInUp', delay: 0 },
            { selector: '.hero-subtitle', animation: 'fadeInUp', delay: 200 },
            { selector: '.hero-actions', animation: 'fadeInUp', delay: 400 },
            { selector: '.hero-image', animation: 'fadeInRight', delay: 600 },
            { selector: '.stat-card', animation: 'fadeInUp', delay: 0 },
            { selector: '.feature-card', animation: 'fadeInUp', delay: 0 },
            { selector: '.demo-card', animation: 'fadeInUp', delay: 0 },
            { selector: '.testimonial-card', animation: 'fadeInUp', delay: 0 }
        ];

        animatedElements.forEach(({ selector, animation, delay }) => {
            document.querySelectorAll(selector).forEach((element, index) => {
                element.classList.add('animate-on-scroll');
                element.dataset.animation = animation;
                element.dataset.delay = delay + (index * 100);
            });
        });
    }

    initializeCounters() {
        const counters = document.querySelectorAll('.stat-number');
        counters.forEach(counter => {
            const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
            counter.dataset.target = target;
            counter.textContent = '0';
        });
    }

    setupSmoothScrolling() {
        // Enable smooth scrolling behavior
        document.documentElement.style.scrollBehavior = 'smooth';
    }

    initializeIntersectionObserver() {
        const options = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        this.animationObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateElement(entry.target);
                    
                    // Animate counters if it's a stat card
                    if (entry.target.classList.contains('stat-card')) {
                        this.animateCounter(entry.target.querySelector('.stat-number'));
                    }
                }
            });
        }, options);

        // Observe all animated elements
        document.querySelectorAll('.animate-on-scroll').forEach(element => {
            this.animationObserver.observe(element);
        });

        // Observe stat cards for counter animation
        document.querySelectorAll('.stat-card').forEach(card => {
            this.animationObserver.observe(card);
        });
    }

    animateElement(element) {
        const animation = element.dataset.animation || 'fadeInUp';
        const delay = parseInt(element.dataset.delay) || 0;

        setTimeout(() => {
            element.classList.add('animate', animation);
        }, delay);

        // Remove observer after animation
        this.animationObserver.unobserve(element);
    }

    animateCounter(counter) {
        if (!counter || counter.classList.contains('animated')) return;
        
        counter.classList.add('animated');
        const target = parseInt(counter.dataset.target);
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                counter.textContent = Math.floor(current).toLocaleString('ar-SA');
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString('ar-SA') + (counter.textContent.includes('%') ? '%' : '+');
            }
        };

        updateCounter();
    }

    setupFormHandlers() {
        // Contact form validation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', this.validateForm.bind(this));
            
            // Real-time validation
            const inputs = form.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', () => this.clearFieldError(input));
            });
        });
    }

    initializeTooltips() {
        // Add tooltips to interactive elements
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        tooltipElements.forEach(element => {
            element.addEventListener('mouseenter', this.showTooltip.bind(this));
            element.addEventListener('mouseleave', this.hideTooltip.bind(this));
        });
    }

    setupKeyboardNavigation() {
        // Improve keyboard accessibility
        const focusableElements = document.querySelectorAll(
            'a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
        );

        focusableElements.forEach(element => {
            element.addEventListener('focus', this.handleFocus.bind(this));
            element.addEventListener('blur', this.handleBlur.bind(this));
        });
    }

    // Event Handlers
    handleCTAClick(event) {
        const button = event.currentTarget;
        const href = button.getAttribute('href');
        
        // Add click animation
        button.classList.add('btn-clicked');
        setTimeout(() => button.classList.remove('btn-clicked'), 200);

        // Track analytics
        this.trackEvent('cta_click', {
            button_text: button.textContent.trim(),
            button_href: href,
            section: 'hero'
        });
    }

    handleCardHover(event) {
        const card = event.currentTarget;
        card.classList.add('card-hovered');
        
        // Add subtle animation to icon
        const icon = card.querySelector('.feature-icon, .stat-icon');
        if (icon) {
            icon.style.transform = 'scale(1.1) rotate(5deg)';
        }
    }

    handleCardLeave(event) {
        const card = event.currentTarget;
        card.classList.remove('card-hovered');
        
        // Reset icon animation
        const icon = card.querySelector('.feature-icon, .stat-icon');
        if (icon) {
            icon.style.transform = '';
        }
    }

    handleTestimonialClick(event) {
        const card = event.currentTarget;
        const authorName = card.querySelector('.author-name')?.textContent;
        
        // Show expanded testimonial modal (if implemented)
        this.showTestimonialModal(card);
        
        this.trackEvent('testimonial_click', {
            author: authorName
        });
    }

    handleNewsletterSubmit(event) {
        event.preventDefault();
        const form = event.target;
        const email = form.querySelector('input[type="email"]').value;
        
        if (this.validateEmail(email)) {
            this.submitNewsletter(email);
        }
    }

    handleScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Parallax effect for hero background
        const heroBackground = document.querySelector('.hero-background');
        if (heroBackground) {
            const parallaxSpeed = 0.5;
            heroBackground.style.transform = `translateY(${scrollTop * parallaxSpeed}px)`;
        }

        // Update navigation active state
        this.updateActiveNavigation();
    }

    handleResize() {
        // Recalculate animations and layouts
        this.debounce(() => {
            this.updateResponsiveElements();
        }, 250)();
    }

    handleKeyboardShortcuts(event) {
        // Keyboard shortcuts for better UX
        if (event.ctrlKey || event.metaKey) {
            switch (event.key) {
                case 'k':
                    event.preventDefault();
                    this.focusSearch();
                    break;
                case 'h':
                    event.preventDefault();
                    this.scrollToTop();
                    break;
            }
        }

        // Escape key handlers
        if (event.key === 'Escape') {
            this.closeModals();
        }
    }

    handleFocus(event) {
        const element = event.target;
        element.classList.add('keyboard-focused');
    }

    handleBlur(event) {
        const element = event.target;
        element.classList.remove('keyboard-focused');
    }

    // Utility Methods
    smoothScrollTo(target) {
        const targetPosition = target.offsetTop - 80; // Account for fixed header
        const startPosition = window.pageYOffset;
        const distance = targetPosition - startPosition;
        const duration = 800;
        let start = null;

        const animation = (currentTime) => {
            if (start === null) start = currentTime;
            const timeElapsed = currentTime - start;
            const run = this.easeInOutQuad(timeElapsed, startPosition, distance, duration);
            window.scrollTo(0, run);
            if (timeElapsed < duration) requestAnimationFrame(animation);
        };

        requestAnimationFrame(animation);
    }

    easeInOutQuad(t, b, c, d) {
        t /= d / 2;
        if (t < 1) return c / 2 * t * t + b;
        t--;
        return -c / 2 * (t * (t - 2) - 1) + b;
    }

    validateForm(event) {
        const form = event.target;
        let isValid = true;

        const inputs = form.querySelectorAll('input[required], textarea[required]');
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        if (!isValid) {
            event.preventDefault();
            this.showFormError(form, 'يرجى تصحيح الأخطاء المذكورة أعلاه');
        }

        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        let isValid = true;
        let errorMessage = '';

        // Required field validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'هذا الحقل مطلوب';
        }

        // Email validation
        if (type === 'email' && value && !this.validateEmail(value)) {
            isValid = false;
            errorMessage = 'يرجى إدخال بريد إلكتروني صحيح';
        }

        // Phone validation
        if (type === 'tel' && value && !this.validatePhone(value)) {
            isValid = false;
            errorMessage = 'يرجى إدخال رقم هاتف صحيح';
        }

        this.showFieldError(field, isValid ? '' : errorMessage);
        return isValid;
    }

    validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    validatePhone(phone) {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
        return phoneRegex.test(phone);
    }

    showFieldError(field, message) {
        this.clearFieldError(field);
        
        if (message) {
            field.classList.add('error');
            const errorElement = document.createElement('div');
            errorElement.className = 'field-error';
            errorElement.textContent = message;
            field.parentNode.appendChild(errorElement);
        }
    }

    clearFieldError(field) {
        field.classList.remove('error');
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
    }

    showFormError(form, message) {
        const errorContainer = form.querySelector('.form-errors') || this.createErrorContainer(form);
        errorContainer.textContent = message;
        errorContainer.style.display = 'block';
    }

    createErrorContainer(form) {
        const container = document.createElement('div');
        container.className = 'form-errors alert alert-error';
        container.style.display = 'none';
        form.insertBefore(container, form.firstChild);
        return container;
    }

    async submitNewsletter(email) {
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
                this.showNotification('تم الاشتراك بنجاح في النشرة الإخبارية', 'success');
                document.querySelector('.newsletter-form').reset();
            } else {
                throw new Error(data.message || 'حدث خطأ أثناء الاشتراك');
            }
        } catch (error) {
            this.showNotification(error.message, 'error');
        }
    }

    showTestimonialModal(card) {
        // Implementation for testimonial modal
        const modal = document.createElement('div');
        modal.className = 'testimonial-modal';
        modal.innerHTML = `
            <div class="modal-backdrop" onclick="this.parentElement.remove()"></div>
            <div class="modal-content">
                <button class="modal-close" onclick="this.closest('.testimonial-modal').remove()">
                    <i class="material-icons">close</i>
                </button>
                ${card.innerHTML}
            </div>
        `;
        document.body.appendChild(modal);
    }

    updateActiveNavigation() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('nav a[href^="#"]');
        
        let currentSection = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100;
            const sectionHeight = section.offsetHeight;
            if (window.pageYOffset >= sectionTop && window.pageYOffset < sectionTop + sectionHeight) {
                currentSection = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${currentSection}`) {
                link.classList.add('active');
            }
        });
    }

    updateResponsiveElements() {
        // Update elements based on screen size
        const isMobile = window.innerWidth < 768;
        const isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;
        
        document.body.classList.toggle('mobile', isMobile);
        document.body.classList.toggle('tablet', isTablet);
    }

    showTooltip(event) {
        const element = event.target;
        const tooltipText = element.dataset.tooltip;
        
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = tooltipText;
        document.body.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
        
        element._tooltip = tooltip;
    }

    hideTooltip(event) {
        const element = event.target;
        if (element._tooltip) {
            element._tooltip.remove();
            delete element._tooltip;
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="material-icons">${this.getNotificationIcon(type)}</i>
                <span>${message}</span>
            </div>
            <button class="notification-close" onclick="this.parentElement.remove()">
                <i class="material-icons">close</i>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    getNotificationIcon(type) {
        const icons = {
            success: 'check_circle',
            error: 'error',
            warning: 'warning',
            info: 'info'
        };
        return icons[type] || 'info';
    }

    focusSearch() {
        const searchInput = document.querySelector('.search-input, input[type="search"]');
        if (searchInput) {
            searchInput.focus();
        }
    }

    scrollToTop() {
        this.smoothScrollTo(document.body);
    }

    closeModals() {
        document.querySelectorAll('.modal, .testimonial-modal').forEach(modal => {
            modal.remove();
        });
    }

    trackEvent(eventName, properties = {}) {
        // Analytics tracking
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, properties);
        }
        
        // Custom analytics
        console.log('Event tracked:', eventName, properties);
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Cleanup method
    destroy() {
        if (this.animationObserver) {
            this.animationObserver.disconnect();
        }
        
        // Remove event listeners
        window.removeEventListener('scroll', this.handleScroll);
        window.removeEventListener('resize', this.handleResize);
        document.removeEventListener('keydown', this.handleKeyboardShortcuts);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.welcomePage = new WelcomePage();
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.welcomePage) {
        window.welcomePage.destroy();
    }
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = WelcomePage;
}
