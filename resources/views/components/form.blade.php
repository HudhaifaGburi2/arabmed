@props([
    'method' => 'POST',
    'action' => '',
    'enctype' => null,
    'novalidate' => false,
    'autocomplete' => 'on',
    'class' => '',
    'id' => 'form',
    'ajax' => false,
    'validation' => true,
    'rtl' => true
])

@php
    $formClasses = [
        'form',
        $rtl ? 'form-rtl' : 'form-ltr',
        $ajax ? 'form-ajax' : '',
        $validation ? 'form-validation' : '',
        $class
    ];
    
    $actualMethod = strtoupper($method);
    $spoofMethod = in_array($actualMethod, ['PUT', 'PATCH', 'DELETE']) ? $actualMethod : null;
    $formMethod = $spoofMethod ? 'POST' : $actualMethod;
@endphp

<form class="{{ implode(' ', array_filter($formClasses)) }}" 
      method="{{ $formMethod }}" 
      action="{{ $action }}"
      id="{{ $id }}"
      @if($enctype) enctype="{{ $enctype }}" @endif
      @if($novalidate) novalidate @endif
      autocomplete="{{ $autocomplete }}"
      @if($ajax) data-ajax-form @endif>
    
    @csrf
    @if($spoofMethod)
        @method($spoofMethod)
    @endif
    
    <!-- Form Header -->
    @isset($header)
        <div class="form-header">
            {{ $header }}
        </div>
    @endisset
    
    <!-- Form Body -->
    <div class="form-body">
        {{ $slot }}
    </div>
    
    <!-- Form Footer -->
    @isset($footer)
        <div class="form-footer">
            {{ $footer }}
        </div>
    @endisset
    
    <!-- Loading Overlay -->
    <div class="form-loading" style="display: none;">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p class="loading-text">جاري المعالجة...</p>
        </div>
    </div>
    
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('{{ $id }}');
    if (!form) return;
    
    const isAjax = form.hasAttribute('data-ajax-form');
    const hasValidation = form.classList.contains('form-validation');
    const loadingOverlay = form.querySelector('.form-loading');
    
    // Form validation
    if (hasValidation) {
        // Real-time validation
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });
        
        function validateField(field) {
            const value = field.value.trim();
            const rules = field.dataset.rules ? field.dataset.rules.split('|') : [];
            let isValid = true;
            let errorMessage = '';
            
            // Clear previous validation
            field.classList.remove('is-valid', 'is-invalid');
            const existingFeedback = field.parentNode.querySelector('.invalid-feedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }
            
            // Validate rules
            for (const rule of rules) {
                const [ruleName, ruleValue] = rule.split(':');
                
                switch (ruleName) {
                    case 'required':
                        if (!value) {
                            isValid = false;
                            errorMessage = 'هذا الحقل مطلوب';
                        }
                        break;
                        
                    case 'email':
                        if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                            isValid = false;
                            errorMessage = 'يرجى إدخال بريد إلكتروني صحيح';
                        }
                        break;
                        
                    case 'min':
                        if (value && value.length < parseInt(ruleValue)) {
                            isValid = false;
                            errorMessage = `يجب أن يكون الحد الأدنى ${ruleValue} أحرف`;
                        }
                        break;
                        
                    case 'max':
                        if (value && value.length > parseInt(ruleValue)) {
                            isValid = false;
                            errorMessage = `يجب أن لا يتجاوز ${ruleValue} حرف`;
                        }
                        break;
                        
                    case 'numeric':
                        if (value && !/^\d+$/.test(value)) {
                            isValid = false;
                            errorMessage = 'يجب أن يكون رقماً';
                        }
                        break;
                        
                    case 'phone':
                        if (value && !/^[\+]?[0-9\s\-\(\)]{10,}$/.test(value)) {
                            isValid = false;
                            errorMessage = 'يرجى إدخال رقم هاتف صحيح';
                        }
                        break;
                }
                
                if (!isValid) break;
            }
            
            // Apply validation result
            if (isValid && value) {
                field.classList.add('is-valid');
            } else if (!isValid) {
                field.classList.add('is-invalid');
                
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = errorMessage;
                field.parentNode.appendChild(feedback);
            }
            
            return isValid;
        }
        
        // Form submission validation
        form.addEventListener('submit', function(e) {
            if (hasValidation) {
                let isFormValid = true;
                const inputs = form.querySelectorAll('input, select, textarea');
                
                inputs.forEach(input => {
                    if (!validateField(input)) {
                        isFormValid = false;
                    }
                });
                
                if (!isFormValid) {
                    e.preventDefault();
                    
                    // Focus first invalid field
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    
                    return false;
                }
            }
        });
    }
    
    // AJAX form submission
    if (isAjax) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = this.action || window.location.href;
            const method = this.method || 'POST';
            
            // Show loading
            if (loadingOverlay) {
                loadingOverlay.style.display = 'flex';
            }
            
            // Disable form
            const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            submitButtons.forEach(btn => {
                btn.disabled = true;
                btn.dataset.originalText = btn.textContent;
                btn.textContent = 'جاري المعالجة...';
            });
            
            fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success handling
                    form.dispatchEvent(new CustomEvent('form:success', { detail: data }));
                    
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.message) {
                        showNotification(data.message, 'success');
                    }
                } else {
                    // Error handling
                    form.dispatchEvent(new CustomEvent('form:error', { detail: data }));
                    
                    if (data.errors) {
                        displayValidationErrors(data.errors);
                    } else if (data.message) {
                        showNotification(data.message, 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Form submission error:', error);
                form.dispatchEvent(new CustomEvent('form:error', { detail: { error } }));
                showNotification('حدث خطأ أثناء معالجة الطلب', 'error');
            })
            .finally(() => {
                // Hide loading
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'none';
                }
                
                // Re-enable form
                submitButtons.forEach(btn => {
                    btn.disabled = false;
                    btn.textContent = btn.dataset.originalText || btn.textContent;
                });
            });
        });
        
        function displayValidationErrors(errors) {
            // Clear previous errors
            form.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            form.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.remove();
            });
            
            // Display new errors
            Object.keys(errors).forEach(fieldName => {
                const field = form.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.classList.add('is-invalid');
                    
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = errors[fieldName][0];
                    field.parentNode.appendChild(feedback);
                }
            });
        }
        
        function showNotification(message, type = 'info') {
            // Simple notification - can be replaced with your notification system
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show`;
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.insertBefore(notification, document.body.firstChild);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    }
});
</script>
@endpush
