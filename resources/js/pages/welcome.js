// Welcome Page JavaScript - Arab-Med Platform
import { createApp } from 'vue';
import { Chart, registerables } from 'chart.js';
import LandingApp from '../landing/components/LandingApp.vue';

// Register Chart.js components
Chart.register(...registerables);

// Initialize Vue app for landing page
const app = createApp(LandingApp);

// Global chart configuration
Chart.defaults.font.family = 'Inter, sans-serif';
Chart.defaults.color = '#64748b';
Chart.defaults.borderColor = 'rgba(0, 0, 0, 0.1)';

// Mount the app
app.mount('#landing-app');

// Smooth scrolling for navigation links
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
    }

    // Animate statistics on scroll
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe stat numbers
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach(stat => observer.observe(stat));

    // Initialize tooltips
    initializeTooltips();

    // Initialize modals
    initializeModals();
});

// Counter animation function
function animateCounter(element) {
    const target = parseInt(element.textContent.replace(/[^\d]/g, ''));
    const duration = 2000;
    const increment = target / (duration / 16);
    let current = 0;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        
        // Format number with commas
        const formatted = Math.floor(current).toLocaleString('ar-SA');
        element.textContent = formatted + (element.textContent.includes('+') ? '+' : '');
    }, 16);
}

// Tooltip initialization
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

function showTooltip(event) {
    const text = event.target.getAttribute('data-tooltip');
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = text;
    
    document.body.appendChild(tooltip);
    
    const rect = event.target.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
    
    setTimeout(() => tooltip.classList.add('show'), 10);
}

function hideTooltip() {
    const tooltip = document.querySelector('.tooltip');
    if (tooltip) {
        tooltip.remove();
    }
}

// Modal functionality
function initializeModals() {
    const modalTriggers = document.querySelectorAll('[data-modal]');
    const modals = document.querySelectorAll('.modal');
    
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            if (modal) {
                openModal(modal);
            }
        });
    });

    modals.forEach(modal => {
        const closeBtn = modal.querySelector('.modal-close');
        const overlay = modal.querySelector('.modal-overlay');
        
        if (closeBtn) {
            closeBtn.addEventListener('click', () => closeModal(modal));
        }
        
        if (overlay) {
            overlay.addEventListener('click', () => closeModal(modal));
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                closeModal(openModal);
            }
        }
    });
}

function openModal(modal) {
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Focus first input if exists
    const firstInput = modal.querySelector('input, textarea, select');
    if (firstInput) {
        setTimeout(() => firstInput.focus(), 100);
    }
}

function closeModal(modal) {
    modal.classList.remove('show');
    document.body.style.overflow = '';
}

// Form validation utilities
export function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            showFieldError(input, 'هذا الحقل مطلوب');
            isValid = false;
        } else {
            clearFieldError(input);
        }

        // Email validation
        if (input.type === 'email' && input.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value)) {
                showFieldError(input, 'يرجى إدخال بريد إلكتروني صحيح');
                isValid = false;
            }
        }

        // Phone validation (Saudi format)
        if (input.type === 'tel' && input.value) {
            const phoneRegex = /^(05|5)[0-9]{8}$/;
            if (!phoneRegex.test(input.value.replace(/\s/g, ''))) {
                showFieldError(input, 'يرجى إدخال رقم جوال صحيح');
                isValid = false;
            }
        }
    });

    return isValid;
}

function showFieldError(input, message) {
    clearFieldError(input);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    
    input.classList.add('error');
    input.parentNode.appendChild(errorDiv);
}

function clearFieldError(input) {
    input.classList.remove('error');
    const existingError = input.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Loading state utilities
export function showLoading(button) {
    button.disabled = true;
    button.innerHTML = '<span class="spinner"></span> جاري التحميل...';
}

export function hideLoading(button, originalText) {
    button.disabled = false;
    button.innerHTML = originalText;
}

// Notification system
export function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `;

    document.body.appendChild(notification);

    // Show notification
    setTimeout(() => notification.classList.add('show'), 100);

    // Auto hide after 5 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);

    // Manual close
    notification.querySelector('.notification-close').addEventListener('click', () => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    });
}

// RTL text direction utilities
export function setTextDirection(element, content) {
    const arabicRegex = /[\u0600-\u06FF]/;
    const direction = arabicRegex.test(content) ? 'rtl' : 'ltr';
    element.setAttribute('dir', direction);
    return direction;
}

// Format numbers for Arabic locale
export function formatNumber(number, locale = 'ar-SA') {
    return new Intl.NumberFormat(locale).format(number);
}

// Format dates for Arabic locale
export function formatDate(date, locale = 'ar-SA') {
    return new Intl.DateTimeFormat(locale, {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(new Date(date));
}

// Export utilities for use in other modules
window.ArabMed = {
    validateForm,
    showLoading,
    hideLoading,
    showNotification,
    setTextDirection,
    formatNumber,
    formatDate
};
