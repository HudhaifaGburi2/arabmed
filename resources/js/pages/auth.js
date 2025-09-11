/**
 * Arab-Med Authentication Pages JavaScript
 * Handles login, registration, and password reset functionality
 */

class ArabMedAuth {
    constructor() {
        this.currentForm = null;
        this.validationRules = {
            email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            phone: /^[\+]?[1-9][\d]{0,15}$/,
            password: {
                minLength: 8,
                requireUppercase: true,
                requireLowercase: true,
                requireNumbers: true,
                requireSpecialChars: false
            }
        };
        
        this.init();
    }

    init() {
        this.setupFormValidation();
        this.setupPasswordToggles();
        this.setupPasswordStrength();
        this.setupRoleSelection();
        this.setupSocialLogin();
        this.setupFormSubmission();
        this.setupKeyboardShortcuts();
        this.setupAccessibility();
        
        // Auto-focus first input
        const firstInput = document.querySelector('.form-input');
        if (firstInput) {
            firstInput.focus();
        }
    }

    setupFormValidation() {
        const forms = document.querySelectorAll('.auth-form');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('.form-input');
            
            inputs.forEach(input => {
                // Real-time validation
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', () => this.clearFieldError(input));
                
                // Arabic text support
                if (input.name === 'first_name' || input.name === 'last_name') {
                    input.addEventListener('input', (e) => {
                        // Allow Arabic and Latin characters
                        const value = e.target.value;
                        const arabicLatinRegex = /^[\u0600-\u06FFa-zA-Z\s]*$/;
                        
                        if (!arabicLatinRegex.test(value)) {
                            e.target.value = value.replace(/[^\u0600-\u06FFa-zA-Z\s]/g, '');
                        }
                    });
                }
            });
        });
    }

    validateField(input) {
        const value = input.value.trim();
        const fieldName = input.name;
        let isValid = true;
        let errorMessage = '';

        // Clear previous errors
        this.clearFieldError(input);

        // Required field validation
        if (input.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = this.getErrorMessage('required', fieldName);
        }
        // Email validation
        else if (fieldName === 'email' && value && !this.validationRules.email.test(value)) {
            isValid = false;
            errorMessage = this.getErrorMessage('email');
        }
        // Phone validation
        else if (fieldName === 'phone' && value && !this.validationRules.phone.test(value)) {
            isValid = false;
            errorMessage = this.getErrorMessage('phone');
        }
        // Password validation
        else if (fieldName === 'password' && value) {
            const passwordValidation = this.validatePassword(value);
            if (!passwordValidation.isValid) {
                isValid = false;
                errorMessage = passwordValidation.message;
            }
        }
        // Password confirmation
        else if (fieldName === 'password_confirmation' && value) {
            const passwordInput = document.getElementById('password');
            if (passwordInput && value !== passwordInput.value) {
                isValid = false;
                errorMessage = this.getErrorMessage('password_match');
            }
        }

        if (!isValid) {
            this.showFieldError(input, errorMessage);
        }

        return isValid;
    }

    validatePassword(password) {
        const rules = this.validationRules.password;
        const errors = [];

        if (password.length < rules.minLength) {
            errors.push(`يجب أن تحتوي على ${rules.minLength} أحرف على الأقل`);
        }

        if (rules.requireUppercase && !/[A-Z]/.test(password)) {
            errors.push('يجب أن تحتوي على حرف كبير واحد على الأقل');
        }

        if (rules.requireLowercase && !/[a-z]/.test(password)) {
            errors.push('يجب أن تحتوي على حرف صغير واحد على الأقل');
        }

        if (rules.requireNumbers && !/\d/.test(password)) {
            errors.push('يجب أن تحتوي على رقم واحد على الأقل');
        }

        if (rules.requireSpecialChars && !/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            errors.push('يجب أن تحتوي على رمز خاص واحد على الأقل');
        }

        return {
            isValid: errors.length === 0,
            message: errors.join('، '),
            strength: this.calculatePasswordStrength(password)
        };
    }

    calculatePasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/\d/.test(password)) strength++;
        if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;
        
        return Math.min(strength, 5);
    }

    showFieldError(input, message) {
        input.classList.add('error');
        
        // Remove existing error
        const existingError = input.parentNode.querySelector('.form-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Add new error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'form-error';
        errorDiv.textContent = message;
        
        // Insert after input or password wrapper
        const wrapper = input.closest('.password-input-wrapper');
        const insertAfter = wrapper || input;
        insertAfter.parentNode.insertBefore(errorDiv, insertAfter.nextSibling);
    }

    clearFieldError(input) {
        input.classList.remove('error');
        const errorDiv = input.parentNode.querySelector('.form-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    getErrorMessage(type, fieldName = '') {
        const messages = {
            required: {
                email: 'البريد الإلكتروني مطلوب',
                password: 'كلمة المرور مطلوبة',
                first_name: 'الاسم الأول مطلوب',
                last_name: 'الاسم الأخير مطلوب',
                default: 'هذا الحقل مطلوب'
            },
            email: 'يرجى إدخال بريد إلكتروني صحيح',
            phone: 'يرجى إدخال رقم هاتف صحيح',
            password_match: 'كلمات المرور غير متطابقة'
        };

        if (type === 'required') {
            return messages.required[fieldName] || messages.required.default;
        }

        return messages[type] || 'خطأ في التحقق من صحة البيانات';
    }

    setupPasswordToggles() {
        const toggleButtons = document.querySelectorAll('.password-toggle');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const input = button.previousElementSibling;
                const icon = button.querySelector('.material-icons');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = 'visibility_off';
                    button.setAttribute('aria-label', 'إخفاء كلمة المرور');
                } else {
                    input.type = 'password';
                    icon.textContent = 'visibility';
                    button.setAttribute('aria-label', 'إظهار كلمة المرور');
                }
            });
        });
    }

    setupPasswordStrength() {
        const passwordInput = document.getElementById('password');
        if (!passwordInput) return;

        passwordInput.addEventListener('input', (e) => {
            const password = e.target.value;
            const strengthIndicator = document.querySelector('.password-strength');
            
            if (!strengthIndicator) return;

            const validation = this.validatePassword(password);
            const strength = validation.strength;
            
            this.updatePasswordStrengthUI(strengthIndicator, strength, password.length > 0);
        });
    }

    updatePasswordStrengthUI(container, strength, hasValue) {
        const strengthBar = container.querySelector('.strength-fill');
        const strengthText = container.querySelector('.strength-text');
        
        if (!strengthBar || !strengthText) return;

        const colors = ['#f44336', '#ff9800', '#ffeb3b', '#8bc34a', '#4caf50'];
        const labels = ['ضعيفة جداً', 'ضعيفة', 'متوسطة', 'قوية', 'قوية جداً'];
        
        if (!hasValue) {
            strengthBar.style.width = '0%';
            strengthText.textContent = 'قوة كلمة المرور';
            strengthText.style.color = 'var(--text-secondary)';
            return;
        }

        const percentage = (strength / 5) * 100;
        const color = colors[Math.min(strength - 1, 4)] || colors[0];
        const label = labels[Math.min(strength - 1, 4)] || labels[0];
        
        strengthBar.style.width = `${percentage}%`;
        strengthBar.style.backgroundColor = color;
        strengthText.textContent = label;
        strengthText.style.color = color;
    }

    setupRoleSelection() {
        const roleOptions = document.querySelectorAll('.role-option');
        
        roleOptions.forEach(option => {
            option.addEventListener('click', () => {
                const radio = option.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    
                    // Update visual state
                    roleOptions.forEach(opt => {
                        opt.querySelector('.role-card').classList.remove('selected');
                    });
                    option.querySelector('.role-card').classList.add('selected');
                }
            });
            
            // Keyboard support
            option.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    option.click();
                }
            });
        });
    }

    setupSocialLogin() {
        // Google login
        window.loginWithGoogle = () => {
            this.showSocialLoginLoading('google');
            // Redirect will be handled by the backend
        };

        window.registerWithGoogle = () => {
            this.showSocialLoginLoading('google');
            // Redirect will be handled by the backend
        };

        // Facebook login
        window.loginWithFacebook = () => {
            this.showSocialLoginLoading('facebook');
            // Redirect will be handled by the backend
        };

        window.registerWithFacebook = () => {
            this.showSocialLoginLoading('facebook');
            // Redirect will be handled by the backend
        };
    }

    showSocialLoginLoading(provider) {
        const button = document.querySelector(`.btn-${provider}`);
        if (button) {
            button.disabled = true;
            button.innerHTML = `
                <i class="material-icons spinning">refresh</i>
                جاري التحميل...
            `;
        }
    }

    setupFormSubmission() {
        const forms = document.querySelectorAll('.auth-form');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                    return;
                }
                
                this.showFormLoading(form);
            });
        });
    }

    validateForm(form) {
        const inputs = form.querySelectorAll('.form-input[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });
        
        // Check terms acceptance for registration
        const termsCheckbox = form.querySelector('input[name="terms"]');
        if (termsCheckbox && !termsCheckbox.checked) {
            isValid = false;
            this.showNotification('يجب الموافقة على شروط الاستخدام', 'error');
        }
        
        return isValid;
    }

    showFormLoading(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            const textSpan = submitButton.querySelector('.btn-text');
            const loadingSpan = submitButton.querySelector('.btn-loading');
            
            if (textSpan && loadingSpan) {
                textSpan.style.display = 'none';
                loadingSpan.style.display = 'flex';
            }
            
            submitButton.disabled = true;
        }
        
        // Disable form inputs
        const inputs = form.querySelectorAll('input, button');
        inputs.forEach(input => {
            if (input.type !== 'submit') {
                input.disabled = true;
            }
        });
    }

    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Enter key on form elements
            if (e.key === 'Enter' && e.target.classList.contains('form-input')) {
                const form = e.target.closest('form');
                const inputs = Array.from(form.querySelectorAll('.form-input'));
                const currentIndex = inputs.indexOf(e.target);
                
                if (currentIndex < inputs.length - 1) {
                    e.preventDefault();
                    inputs[currentIndex + 1].focus();
                }
            }
            
            // Escape key to clear focus
            if (e.key === 'Escape') {
                document.activeElement.blur();
            }
        });
    }

    setupAccessibility() {
        // Add ARIA labels
        const passwordToggles = document.querySelectorAll('.password-toggle');
        passwordToggles.forEach(toggle => {
            toggle.setAttribute('aria-label', 'إظهار كلمة المرور');
            toggle.setAttribute('tabindex', '0');
        });
        
        // Role options accessibility
        const roleOptions = document.querySelectorAll('.role-option');
        roleOptions.forEach((option, index) => {
            option.setAttribute('tabindex', '0');
            option.setAttribute('role', 'radio');
            option.setAttribute('aria-label', option.querySelector('.role-title').textContent);
        });
        
        // Form labels association
        const labels = document.querySelectorAll('.form-label');
        labels.forEach(label => {
            const input = label.parentNode.querySelector('.form-input');
            if (input && !input.id) {
                const id = 'input_' + Math.random().toString(36).substr(2, 9);
                input.id = id;
                label.setAttribute('for', id);
            }
        });
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="material-icons">${this.getNotificationIcon(type)}</i>
                <span class="notification-message">${message}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="material-icons">close</i>
                </button>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
        
        // Animate in
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
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

    // Utility methods
    static togglePassword(inputId) {
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

    static showLoading(button) {
        const text = button.querySelector('.btn-text');
        const loading = button.querySelector('.btn-loading');
        
        if (text && loading) {
            text.style.display = 'none';
            loading.style.display = 'flex';
        }
        button.disabled = true;
    }

    static hideLoading(button) {
        const text = button.querySelector('.btn-text');
        const loading = button.querySelector('.btn-loading');
        
        if (text && loading) {
            text.style.display = 'block';
            loading.style.display = 'none';
        }
        button.disabled = false;
    }
}

// Global functions for backward compatibility
window.togglePassword = ArabMedAuth.togglePassword;
window.showLoading = ArabMedAuth.showLoading;
window.hideLoading = ArabMedAuth.hideLoading;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new ArabMedAuth();
});

// Handle form errors from server
window.addEventListener('load', () => {
    const errorMessages = document.querySelectorAll('.alert-danger');
    errorMessages.forEach(alert => {
        const message = alert.textContent.trim();
        if (message) {
            const auth = new ArabMedAuth();
            auth.showNotification(message, 'error');
        }
    });
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ArabMedAuth;
}
