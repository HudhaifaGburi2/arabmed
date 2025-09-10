@props([
    'id' => 'modal',
    'title' => '',
    'size' => 'md', // sm, md, lg, xl, fullscreen
    'centered' => true,
    'backdrop' => true,
    'keyboard' => true,
    'scrollable' => false,
    'fade' => true,
    'class' => ''
])

@php
    $modalClasses = [
        'modal',
        $fade ? 'modal-fade' : '',
        $class
    ];
    
    $dialogClasses = [
        'modal-dialog',
        'modal-' . $size,
        $centered ? 'modal-dialog-centered' : '',
        $scrollable ? 'modal-dialog-scrollable' : ''
    ];
@endphp

<div class="{{ implode(' ', array_filter($modalClasses)) }}" 
     id="{{ $id }}" 
     tabindex="-1" 
     role="dialog" 
     aria-labelledby="{{ $id }}-title"
     aria-hidden="true"
     data-backdrop="{{ $backdrop ? 'true' : 'static' }}"
     data-keyboard="{{ $keyboard ? 'true' : 'false' }}">
    
    <div class="{{ implode(' ', array_filter($dialogClasses)) }}" role="document">
        <div class="modal-content">
            
            <!-- Modal Header -->
            @if($title || isset($header))
                <div class="modal-header">
                    @isset($header)
                        {{ $header }}
                    @else
                        <h4 class="modal-title" id="{{ $id }}-title">{{ $title }}</h4>
                        <button type="button" class="modal-close" data-dismiss="modal" aria-label="إغلاق">
                            <i class="material-icons">close</i>
                        </button>
                    @endisset
                </div>
            @endif

            <!-- Modal Body -->
            <div class="modal-body">
                {{ $slot }}
            </div>

            <!-- Modal Footer -->
            @isset($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endisset

        </div>
    </div>
</div>

<!-- Modal Backdrop -->
<div class="modal-backdrop" id="{{ $id }}-backdrop"></div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('{{ $id }}');
    const backdrop = document.getElementById('{{ $id }}-backdrop');
    
    if (!modal) return;
    
    // Show modal function
    function showModal() {
        modal.style.display = 'block';
        backdrop.style.display = 'block';
        document.body.classList.add('modal-open');
        
        setTimeout(() => {
            modal.classList.add('show');
            backdrop.classList.add('show');
        }, 10);
        
        // Focus management
        modal.focus();
        
        // Trigger shown event
        modal.dispatchEvent(new CustomEvent('modal:shown'));
    }
    
    // Hide modal function
    function hideModal() {
        modal.classList.remove('show');
        backdrop.classList.remove('show');
        
        setTimeout(() => {
            modal.style.display = 'none';
            backdrop.style.display = 'none';
            document.body.classList.remove('modal-open');
        }, 300);
        
        // Trigger hidden event
        modal.dispatchEvent(new CustomEvent('modal:hidden'));
    }
    
    // Show modal triggers
    document.querySelectorAll('[data-toggle="modal"][data-target="#{{ $id }}"]').forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            showModal();
        });
    });
    
    // Hide modal triggers
    modal.querySelectorAll('[data-dismiss="modal"]').forEach(closeBtn => {
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            hideModal();
        });
    });
    
    // Backdrop click
    @if($backdrop)
    backdrop.addEventListener('click', function(e) {
        if (e.target === backdrop) {
            hideModal();
        }
    });
    @endif
    
    // Keyboard events
    @if($keyboard)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            hideModal();
        }
    });
    @endif
    
    // Global modal functions
    window.{{ $id }} = {
        show: showModal,
        hide: hideModal,
        toggle: function() {
            if (modal.classList.contains('show')) {
                hideModal();
            } else {
                showModal();
            }
        }
    };
});
</script>
@endpush
