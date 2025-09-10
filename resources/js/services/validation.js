// Validation Service - Arab-Med Platform

class ValidationService {
    constructor() {
        this.rules = {};
        this.messages = {};
        this.customValidators = {};
        
        this.setupDefaultRules();
        this.setupDefaultMessages();
    }

    // Setup default validation rules
    setupDefaultRules() {
        this.rules = {
            required: (value) => {
                if (Array.isArray(value)) return value.length > 0;
                if (typeof value === 'object' && value !== null) return Object.keys(value).length > 0;
                return value !== null && value !== undefined && String(value).trim() !== '';
            },

            email: (value) => {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(value);
            },

            min: (value, min) => {
                if (typeof value === 'string') return value.length >= min;
                if (typeof value === 'number') return value >= min;
                if (Array.isArray(value)) return value.length >= min;
                return false;
            },

            max: (value, max) => {
                if (typeof value === 'string') return value.length <= max;
                if (typeof value === 'number') return value <= max;
                if (Array.isArray(value)) return value.length <= max;
                return false;
            },

            minLength: (value, length) => {
                return String(value).length >= length;
            },

            maxLength: (value, length) => {
                return String(value).length <= length;
            },

            numeric: (value) => {
                return !isNaN(value) && !isNaN(parseFloat(value));
            },

            integer: (value) => {
                return Number.isInteger(Number(value));
            },

            alpha: (value) => {
                const alphaRegex = /^[a-zA-Z\u0600-\u06FF\s]+$/;
                return alphaRegex.test(value);
            },

            alphaNumeric: (value) => {
                const alphaNumericRegex = /^[a-zA-Z0-9\u0600-\u06FF\s]+$/;
                return alphaNumericRegex.test(value);
            },

            url: (value) => {
                try {
                    new URL(value);
                    return true;
                } catch {
                    return false;
                }
            },

            phone: (value) => {
                const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
                return phoneRegex.test(value.replace(/[\s\-\(\)]/g, ''));
            },

            password: (value) => {
                // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
                const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{8,}$/;
                return passwordRegex.test(value);
            },

            confirmed: (value, confirmValue) => {
                return value === confirmValue;
            },

            date: (value) => {
                const date = new Date(value);
                return date instanceof Date && !isNaN(date);
            },

            dateAfter: (value, afterDate) => {
                const date = new Date(value);
                const after = new Date(afterDate);
                return date > after;
            },

            dateBefore: (value, beforeDate) => {
                const date = new Date(value);
                const before = new Date(beforeDate);
                return date < before;
            },

            in: (value, allowedValues) => {
                return allowedValues.includes(value);
            },

            notIn: (value, forbiddenValues) => {
                return !forbiddenValues.includes(value);
            },

            regex: (value, pattern) => {
                const regex = new RegExp(pattern);
                return regex.test(value);
            },

            file: (file) => {
                return file instanceof File;
            },

            fileSize: (file, maxSizeInMB) => {
                if (!(file instanceof File)) return false;
                const maxSizeInBytes = maxSizeInMB * 1024 * 1024;
                return file.size <= maxSizeInBytes;
            },

            fileType: (file, allowedTypes) => {
                if (!(file instanceof File)) return false;
                return allowedTypes.includes(file.type);
            },

            image: (file) => {
                if (!(file instanceof File)) return false;
                const imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                return imageTypes.includes(file.type);
            },

            video: (file) => {
                if (!(file instanceof File)) return false;
                const videoTypes = ['video/mp4', 'video/webm', 'video/ogg'];
                return videoTypes.includes(file.type);
            },

            audio: (file) => {
                if (!(file instanceof File)) return false;
                const audioTypes = ['audio/mp3', 'audio/wav', 'audio/ogg'];
                return audioTypes.includes(file.type);
            }
        };
    }

    // Setup default error messages
    setupDefaultMessages() {
        this.messages = {
            required: 'This field is required.',
            email: 'Please enter a valid email address.',
            min: 'The value must be at least {0}.',
            max: 'The value must not exceed {0}.',
            minLength: 'The field must be at least {0} characters.',
            maxLength: 'The field must not exceed {0} characters.',
            numeric: 'Please enter a valid number.',
            integer: 'Please enter a valid integer.',
            alpha: 'This field may only contain letters.',
            alphaNumeric: 'This field may only contain letters and numbers.',
            url: 'Please enter a valid URL.',
            phone: 'Please enter a valid phone number.',
            password: 'Password must be at least 8 characters with uppercase, lowercase, and number.',
            confirmed: 'The confirmation does not match.',
            date: 'Please enter a valid date.',
            dateAfter: 'The date must be after {0}.',
            dateBefore: 'The date must be before {0}.',
            in: 'The selected value is invalid.',
            notIn: 'The selected value is not allowed.',
            regex: 'The format is invalid.',
            file: 'Please select a valid file.',
            fileSize: 'The file size must not exceed {0}MB.',
            fileType: 'The file type is not allowed.',
            image: 'Please select a valid image file.',
            video: 'Please select a valid video file.',
            audio: 'Please select a valid audio file.'
        };
    }

    // Add custom validation rule
    addRule(name, validator, message) {
        this.customValidators[name] = validator;
        this.messages[name] = message;
    }

    // Validate a single field
    validateField(value, rules, fieldName = 'field') {
        const errors = [];
        
        if (!rules) return { valid: true, errors: [] };

        const ruleList = Array.isArray(rules) ? rules : rules.split('|');

        for (const rule of ruleList) {
            const [ruleName, ...params] = rule.split(':');
            const ruleParams = params.length > 0 ? params[0].split(',') : [];

            let isValid = false;

            // Check built-in rules
            if (this.rules[ruleName]) {
                isValid = this.rules[ruleName](value, ...ruleParams);
            }
            // Check custom validators
            else if (this.customValidators[ruleName]) {
                isValid = this.customValidators[ruleName](value, ...ruleParams);
            }
            else {
                console.warn(`Validation rule '${ruleName}' not found`);
                continue;
            }

            if (!isValid) {
                const message = this.formatMessage(this.messages[ruleName] || 'Invalid value', ruleParams);
                errors.push({
                    rule: ruleName,
                    message: message,
                    field: fieldName
                });
            }
        }

        return {
            valid: errors.length === 0,
            errors: errors
        };
    }

    // Validate multiple fields
    validate(data, validationRules) {
        const results = {};
        const allErrors = [];

        for (const [fieldName, rules] of Object.entries(validationRules)) {
            const fieldValue = this.getNestedValue(data, fieldName);
            const result = this.validateField(fieldValue, rules, fieldName);
            
            results[fieldName] = result;
            
            if (!result.valid) {
                allErrors.push(...result.errors);
            }
        }

        return {
            valid: allErrors.length === 0,
            errors: allErrors,
            fields: results
        };
    }

    // Get nested object value using dot notation
    getNestedValue(obj, path) {
        return path.split('.').reduce((current, key) => {
            return current && current[key] !== undefined ? current[key] : undefined;
        }, obj);
    }

    // Format error message with parameters
    formatMessage(message, params) {
        return message.replace(/\{(\d+)\}/g, (match, index) => {
            return params[index] || match;
        });
    }

    // Sanitize input data
    sanitize(data, rules = {}) {
        const sanitized = {};

        for (const [key, value] of Object.entries(data)) {
            const sanitizeRules = rules[key] || ['trim'];
            sanitized[key] = this.sanitizeValue(value, sanitizeRules);
        }

        return sanitized;
    }

    // Sanitize a single value
    sanitizeValue(value, rules) {
        let sanitized = value;

        for (const rule of rules) {
            switch (rule) {
                case 'trim':
                    if (typeof sanitized === 'string') {
                        sanitized = sanitized.trim();
                    }
                    break;
                case 'lowercase':
                    if (typeof sanitized === 'string') {
                        sanitized = sanitized.toLowerCase();
                    }
                    break;
                case 'uppercase':
                    if (typeof sanitized === 'string') {
                        sanitized = sanitized.toUpperCase();
                    }
                    break;
                case 'stripTags':
                    if (typeof sanitized === 'string') {
                        sanitized = sanitized.replace(/<[^>]*>/g, '');
                    }
                    break;
                case 'escape':
                    if (typeof sanitized === 'string') {
                        sanitized = this.escapeHtml(sanitized);
                    }
                    break;
                case 'numeric':
                    sanitized = parseFloat(sanitized) || 0;
                    break;
                case 'integer':
                    sanitized = parseInt(sanitized) || 0;
                    break;
                case 'boolean':
                    sanitized = Boolean(sanitized);
                    break;
            }
        }

        return sanitized;
    }

    // Escape HTML characters
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Real-time validation for forms
    setupFormValidation(formElement, validationRules, options = {}) {
        const {
            validateOnBlur = true,
            validateOnInput = false,
            showErrorsImmediately = false
        } = options;

        const formData = {};
        const errors = {};

        // Get all form inputs
        const inputs = formElement.querySelectorAll('input, select, textarea');

        inputs.forEach(input => {
            const fieldName = input.name || input.id;
            if (!fieldName || !validationRules[fieldName]) return;

            // Initialize form data
            formData[fieldName] = input.value;

            // Add event listeners
            if (validateOnBlur) {
                input.addEventListener('blur', () => {
                    this.validateFormField(input, fieldName, validationRules[fieldName], formData, errors);
                });
            }

            if (validateOnInput) {
                input.addEventListener('input', () => {
                    formData[fieldName] = input.value;
                    if (errors[fieldName] || showErrorsImmediately) {
                        this.validateFormField(input, fieldName, validationRules[fieldName], formData, errors);
                    }
                });
            }

            // Handle file inputs
            if (input.type === 'file') {
                input.addEventListener('change', () => {
                    formData[fieldName] = input.files[0];
                    this.validateFormField(input, fieldName, validationRules[fieldName], formData, errors);
                });
            }
        });

        // Form submit validation
        formElement.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Update form data
            inputs.forEach(input => {
                const fieldName = input.name || input.id;
                if (fieldName) {
                    if (input.type === 'file') {
                        formData[fieldName] = input.files[0];
                    } else {
                        formData[fieldName] = input.value;
                    }
                }
            });

            // Validate all fields
            const result = this.validate(formData, validationRules);
            
            if (result.valid) {
                // Clear all errors
                this.clearFormErrors(formElement);
                
                // Dispatch custom event
                const submitEvent = new CustomEvent('formValidated', {
                    detail: { data: formData, valid: true }
                });
                formElement.dispatchEvent(submitEvent);
            } else {
                // Show errors
                this.showFormErrors(formElement, result.fields);
                
                // Dispatch custom event
                const errorEvent = new CustomEvent('formValidationError', {
                    detail: { errors: result.errors, fields: result.fields }
                });
                formElement.dispatchEvent(errorEvent);
            }
        });

        return {
            validate: () => this.validate(formData, validationRules),
            getData: () => formData,
            getErrors: () => errors
        };
    }

    // Validate individual form field
    validateFormField(input, fieldName, rules, formData, errors) {
        const result = this.validateField(formData[fieldName], rules, fieldName);
        
        if (result.valid) {
            delete errors[fieldName];
            this.clearFieldError(input);
        } else {
            errors[fieldName] = result.errors;
            this.showFieldError(input, result.errors[0].message);
        }
    }

    // Show field error
    showFieldError(input, message) {
        // Remove existing error
        this.clearFieldError(input);
        
        // Add error class
        input.classList.add('error', 'is-invalid');
        
        // Create error element
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error invalid-feedback';
        errorElement.textContent = message;
        
        // Insert error after input
        input.parentNode.insertBefore(errorElement, input.nextSibling);
    }

    // Clear field error
    clearFieldError(input) {
        input.classList.remove('error', 'is-invalid');
        
        const errorElement = input.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    // Show form errors
    showFormErrors(formElement, fieldResults) {
        Object.entries(fieldResults).forEach(([fieldName, result]) => {
            const input = formElement.querySelector(`[name="${fieldName}"], #${fieldName}`);
            if (input && !result.valid) {
                this.showFieldError(input, result.errors[0].message);
            }
        });
    }

    // Clear all form errors
    clearFormErrors(formElement) {
        const inputs = formElement.querySelectorAll('input, select, textarea');
        inputs.forEach(input => this.clearFieldError(input));
    }

    // Common validation rule sets
    getCommonRules() {
        return {
            name: 'required|alpha|minLength:2|maxLength:50',
            email: 'required|email|maxLength:255',
            password: 'required|password|minLength:8',
            phone: 'required|phone',
            age: 'required|integer|min:1|max:120',
            url: 'url',
            description: 'maxLength:1000',
            title: 'required|minLength:3|maxLength:100',
            content: 'required|minLength:10',
            image: 'required|file|image|fileSize:5',
            video: 'required|file|video|fileSize:100',
            audio: 'required|file|audio|fileSize:50'
        };
    }

    // Arab-Med specific validation rules
    getArabMedRules() {
        return {
            courseName: 'required|minLength:3|maxLength:100',
            courseDescription: 'required|minLength:10|maxLength:1000',
            coursePrice: 'required|numeric|min:0',
            courseDuration: 'required|integer|min:1',
            videoTitle: 'required|minLength:3|maxLength:100',
            videoDescription: 'maxLength:500',
            videoDuration: 'required|integer|min:1',
            examTitle: 'required|minLength:3|maxLength:100',
            examDuration: 'required|integer|min:5|max:180',
            questionText: 'required|minLength:10|maxLength:500',
            answerText: 'required|minLength:1|maxLength:200',
            studentId: 'required|alphaNumeric|minLength:6|maxLength:20',
            instructorBio: 'maxLength:1000',
            categoryName: 'required|alpha|minLength:2|maxLength:50'
        };
    }
}

// Create and export singleton instance
export const validationService = new ValidationService();
export default validationService;
