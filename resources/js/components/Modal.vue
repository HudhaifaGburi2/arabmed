<template>
  <Teleport to="body">
    <Transition name="modal" appear>
      <div
        v-if="isVisible"
        class="modal-overlay"
        @click="handleOverlayClick"
        :class="{ 'modal-overlay-blur': blur }"
      >
        <div
          class="modal-container"
          :class="[
            `modal-${size}`,
            { 'modal-fullscreen': fullscreen },
            { 'modal-centered': centered }
          ]"
          @click.stop
        >
          <!-- Modal Header -->
          <div v-if="!hideHeader" class="modal-header">
            <div class="modal-title-section">
              <i v-if="icon" class="material-icons modal-icon">{{ icon }}</i>
              <h3 class="modal-title">{{ title }}</h3>
            </div>
            <button
              v-if="!hideCloseButton"
              @click="close"
              class="modal-close-btn"
              type="button"
            >
              <i class="material-icons">close</i>
            </button>
          </div>

          <!-- Modal Body -->
          <div class="modal-body" :class="{ 'modal-body-scrollable': scrollable }">
            <slot></slot>
          </div>

          <!-- Modal Footer -->
          <div v-if="!hideFooter && (hasFooterSlot || showDefaultActions)" class="modal-footer">
            <slot name="footer">
              <div v-if="showDefaultActions" class="modal-actions">
                <button
                  v-if="showCancelButton"
                  @click="cancel"
                  class="btn btn-outline"
                  type="button"
                  :disabled="loading"
                >
                  {{ cancelText }}
                </button>
                <button
                  v-if="showConfirmButton"
                  @click="confirm"
                  class="btn"
                  :class="confirmButtonClass"
                  type="button"
                  :disabled="loading || confirmDisabled"
                >
                  <i v-if="loading" class="material-icons spinning">refresh</i>
                  <i v-else-if="confirmIcon" class="material-icons">{{ confirmIcon }}</i>
                  {{ confirmText }}
                </button>
              </div>
            </slot>
          </div>

          <!-- Loading Overlay -->
          <div v-if="loading" class="modal-loading-overlay">
            <div class="loading-spinner">
              <i class="material-icons spinning">refresh</i>
              <p v-if="loadingText">{{ loadingText }}</p>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script>
import { ref, computed, watch, onMounted, onUnmounted, useSlots } from 'vue';

export default {
  name: 'Modal',
  props: {
    modelValue: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: ''
    },
    icon: {
      type: String,
      default: ''
    },
    size: {
      type: String,
      default: 'medium',
      validator: (value) => ['small', 'medium', 'large', 'extra-large'].includes(value)
    },
    fullscreen: {
      type: Boolean,
      default: false
    },
    centered: {
      type: Boolean,
      default: true
    },
    scrollable: {
      type: Boolean,
      default: true
    },
    blur: {
      type: Boolean,
      default: true
    },
    closeOnOverlay: {
      type: Boolean,
      default: true
    },
    closeOnEscape: {
      type: Boolean,
      default: true
    },
    hideHeader: {
      type: Boolean,
      default: false
    },
    hideFooter: {
      type: Boolean,
      default: false
    },
    hideCloseButton: {
      type: Boolean,
      default: false
    },
    showDefaultActions: {
      type: Boolean,
      default: false
    },
    showCancelButton: {
      type: Boolean,
      default: true
    },
    showConfirmButton: {
      type: Boolean,
      default: true
    },
    cancelText: {
      type: String,
      default: 'إلغاء'
    },
    confirmText: {
      type: String,
      default: 'موافق'
    },
    confirmIcon: {
      type: String,
      default: ''
    },
    confirmType: {
      type: String,
      default: 'primary',
      validator: (value) => ['primary', 'success', 'warning', 'error'].includes(value)
    },
    confirmDisabled: {
      type: Boolean,
      default: false
    },
    loading: {
      type: Boolean,
      default: false
    },
    loadingText: {
      type: String,
      default: ''
    },
    persistent: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue', 'close', 'cancel', 'confirm', 'opened', 'closed'],
  setup(props, { emit }) {
    const slots = useSlots();
    const isVisible = ref(props.modelValue);

    // Computed properties
    const hasFooterSlot = computed(() => !!slots.footer);
    
    const confirmButtonClass = computed(() => {
      return `btn-${props.confirmType}`;
    });

    // Methods
    const close = () => {
      if (props.persistent && !props.loading) return;
      
      isVisible.value = false;
      emit('update:modelValue', false);
      emit('close');
    };

    const cancel = () => {
      emit('cancel');
      close();
    };

    const confirm = () => {
      emit('confirm');
    };

    const handleOverlayClick = () => {
      if (props.closeOnOverlay && !props.persistent) {
        close();
      }
    };

    const handleEscapeKey = (event) => {
      if (event.key === 'Escape' && props.closeOnEscape && !props.persistent) {
        close();
      }
    };

    // Watchers
    watch(() => props.modelValue, (newValue) => {
      isVisible.value = newValue;
      
      if (newValue) {
        emit('opened');
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
      } else {
        emit('closed');
        // Restore body scroll
        document.body.style.overflow = '';
      }
    });

    // Lifecycle
    onMounted(() => {
      if (props.closeOnEscape) {
        document.addEventListener('keydown', handleEscapeKey);
      }
    });

    onUnmounted(() => {
      if (props.closeOnEscape) {
        document.removeEventListener('keydown', handleEscapeKey);
      }
      // Restore body scroll on unmount
      document.body.style.overflow = '';
    });

    return {
      isVisible,
      hasFooterSlot,
      confirmButtonClass,
      close,
      cancel,
      confirm,
      handleOverlayClick
    };
  }
};
</script>
