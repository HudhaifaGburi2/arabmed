<template>
  <div class="video-form">
    <div class="form-header">
      <h2 class="form-title">
        {{ isEditing ? 'تحرير الفيديو' : 'إضافة فيديو جديد' }}
      </h2>
    </div>

    <form @submit.prevent="handleSubmit" class="video-form-content" ref="videoForm">
      <!-- Basic Information -->
      <div class="form-section">
        <h3 class="section-title">معلومات الفيديو</h3>
        
        <div class="form-group">
          <label for="title" class="form-label required">عنوان الفيديو</label>
          <input
            type="text"
            id="title"
            name="title"
            v-model="formData.title"
            class="form-input"
            :class="{ 'error': errors.title }"
            placeholder="أدخل عنوان الفيديو"
            required
          />
          <div v-if="errors.title" class="field-error">{{ errors.title }}</div>
        </div>

        <div class="form-group">
          <label for="description" class="form-label">وصف الفيديو</label>
          <textarea
            id="description"
            name="description"
            v-model="formData.description"
            class="form-textarea"
            rows="3"
            placeholder="وصف مختصر للفيديو"
          ></textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="duration" class="form-label required">المدة (دقائق)</label>
            <input
              type="number"
              id="duration"
              name="duration"
              v-model="formData.duration"
              class="form-input"
              :class="{ 'error': errors.duration }"
              min="1"
              placeholder="المدة بالدقائق"
              required
            />
            <div v-if="errors.duration" class="field-error">{{ errors.duration }}</div>
          </div>

          <div class="form-group">
            <label for="order" class="form-label">الترتيب</label>
            <input
              type="number"
              id="order"
              name="order"
              v-model="formData.order"
              class="form-input"
              min="1"
              placeholder="ترتيب الفيديو"
            />
          </div>
        </div>
      </div>

      <!-- Video Upload -->
      <div class="form-section">
        <h3 class="section-title">رفع الفيديو</h3>
        
        <div class="form-group">
          <label class="form-label required">ملف الفيديو</label>
          <div class="file-upload-area" @click="triggerVideoInput" @dragover.prevent @drop.prevent="handleVideoDrop">
            <input
              type="file"
              ref="videoInput"
              @change="handleVideoSelect"
              accept="video/*"
              class="file-input"
              style="display: none;"
            />
            <div v-if="!videoFile && !formData.video_url" class="file-upload-placeholder">
              <i class="material-icons">video_library</i>
              <p>اضغط أو اسحب الفيديو هنا</p>
              <small>MP4, WebM, MOV حتى 500MB</small>
            </div>
            <div v-else class="file-preview">
              <div class="video-info">
                <i class="material-icons">play_circle_filled</i>
                <div class="video-details">
                  <p class="video-name">{{ videoFile?.name || 'فيديو موجود' }}</p>
                  <p class="video-size" v-if="videoFile">{{ formatFileSize(videoFile.size) }}</p>
                </div>
              </div>
              <button type="button" @click.stop="removeVideo" class="remove-file-btn">
                <i class="material-icons">close</i>
              </button>
            </div>
          </div>
          <div v-if="errors.video_file" class="field-error">{{ errors.video_file }}</div>
        </div>

        <!-- Upload Progress -->
        <div v-if="uploadProgress > 0" class="upload-progress">
          <div class="progress-bar">
            <div class="progress-fill" :style="{ width: uploadProgress + '%' }"></div>
          </div>
          <span class="progress-text">{{ uploadProgress }}%</span>
        </div>
      </div>

      <!-- Settings -->
      <div class="form-section">
        <h3 class="section-title">إعدادات الفيديو</h3>
        
        <div class="checkbox-group">
          <label class="checkbox-label">
            <input
              type="checkbox"
              v-model="formData.is_free"
              class="checkbox-input"
            />
            <span class="checkbox-custom"></span>
            <span class="checkbox-text">فيديو مجاني</span>
          </label>

          <label class="checkbox-label">
            <input
              type="checkbox"
              v-model="formData.is_published"
              class="checkbox-input"
            />
            <span class="checkbox-custom"></span>
            <span class="checkbox-text">نشر الفيديو</span>
          </label>

          <label class="checkbox-label">
            <input
              type="checkbox"
              v-model="formData.allow_download"
              class="checkbox-input"
            />
            <span class="checkbox-custom"></span>
            <span class="checkbox-text">السماح بالتحميل</span>
          </label>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="form-actions">
        <button
          type="button"
          @click="saveDraft"
          class="btn btn-secondary"
          :disabled="isLoading"
        >
          <i class="material-icons">save</i>
          حفظ كمسودة
        </button>
        
        <button
          type="submit"
          class="btn btn-primary"
          :disabled="isLoading || !isFormValid"
        >
          <i v-if="isLoading" class="material-icons spinning">refresh</i>
          <i v-else class="material-icons">{{ isEditing ? 'save' : 'add' }}</i>
          {{ isEditing ? 'تحديث الفيديو' : 'إضافة الفيديو' }}
        </button>
      </div>
    </form>

    <!-- Loading Overlay -->
    <div v-if="isLoading" class="loading-overlay">
      <div class="loading-spinner">
        <i class="material-icons spinning">refresh</i>
        <p>{{ loadingMessage }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, computed, onMounted } from 'vue';
import { apiService } from '../services/api.js';
import { validationService } from '../services/validation.js';
import { storageService } from '../services/storage.js';

export default {
  name: 'VideoForm',
  props: {
    courseId: {
      type: [String, Number],
      required: true
    },
    videoId: {
      type: [String, Number],
      default: null
    },
    initialData: {
      type: Object,
      default: () => ({})
    }
  },
  emits: ['success', 'error', 'cancel'],
  setup(props, { emit }) {
    // Reactive data
    const videoForm = ref(null);
    const videoInput = ref(null);
    const isLoading = ref(false);
    const loadingMessage = ref('');
    const errors = ref({});
    const videoFile = ref(null);
    const uploadProgress = ref(0);

    // Form data
    const formData = reactive({
      title: '',
      description: '',
      duration: 1,
      order: 1,
      video_url: '',
      is_free: false,
      is_published: true,
      allow_download: false,
      ...props.initialData
    });

    // Computed properties
    const isEditing = computed(() => !!props.videoId);
    
    const isFormValid = computed(() => {
      return formData.title && 
             formData.duration > 0 && 
             (videoFile.value || formData.video_url);
    });

    // Validation rules
    const validationRules = {
      title: 'required|minLength:3|maxLength:100',
      duration: 'required|integer|min:1',
      video_file: 'file|video|fileSize:500'
    };

    // Methods
    const loadVideo = async () => {
      if (!props.videoId) return;
      
      try {
        isLoading.value = true;
        loadingMessage.value = 'جاري تحميل بيانات الفيديو...';
        
        const video = await apiService.getVideo(props.courseId, props.videoId);
        
        Object.keys(formData).forEach(key => {
          if (video[key] !== undefined) {
            formData[key] = video[key];
          }
        });

      } catch (error) {
        emit('error', 'فشل في تحميل بيانات الفيديو');
      } finally {
        isLoading.value = false;
      }
    };

    const handleSubmit = async () => {
      try {
        errors.value = {};
        
        // Validate form
        const validation = validationService.validate(formData, validationRules);
        if (!validation.valid) {
          validation.errors.forEach(error => {
            errors.value[error.field] = error.message;
          });
          return;
        }

        isLoading.value = true;
        loadingMessage.value = isEditing.value ? 'جاري تحديث الفيديو...' : 'جاري رفع الفيديو...';

        const submitData = new FormData();
        Object.keys(formData).forEach(key => {
          if (formData[key] !== null && formData[key] !== '') {
            submitData.append(key, formData[key]);
          }
        });

        if (videoFile.value) {
          submitData.append('video_file', videoFile.value);
        }

        let response;
        if (isEditing.value) {
          response = await apiService.updateVideo(props.courseId, props.videoId, submitData);
        } else {
          response = await apiService.createVideo(props.courseId, submitData);
        }

        storageService.removeDraft('video-form');
        
        emit('success', {
          message: isEditing.value ? 'تم تحديث الفيديو بنجاح' : 'تم إضافة الفيديو بنجاح',
          video: response
        });

      } catch (error) {
        if (error.response?.status === 422) {
          const validationErrors = error.response.data.errors;
          Object.keys(validationErrors).forEach(field => {
            errors.value[field] = validationErrors[field][0];
          });
        } else {
          emit('error', 'حدث خطأ أثناء حفظ الفيديو');
        }
      } finally {
        isLoading.value = false;
      }
    };

    const saveDraft = () => {
      storageService.saveDraft('video-form', formData);
      const event = new CustomEvent('showNotification', {
        detail: { message: 'تم حفظ المسودة', type: 'success' }
      });
      window.dispatchEvent(event);
    };

    const triggerVideoInput = () => {
      videoInput.value?.click();
    };

    const handleVideoSelect = (event) => {
      const file = event.target.files[0];
      if (file) {
        processVideoFile(file);
      }
    };

    const handleVideoDrop = (event) => {
      const file = event.dataTransfer.files[0];
      if (file) {
        processVideoFile(file);
      }
    };

    const processVideoFile = (file) => {
      const validation = validationService.validateField(file, 'file|video|fileSize:500');
      if (!validation.valid) {
        errors.value.video_file = validation.errors[0].message;
        return;
      }

      videoFile.value = file;
      delete errors.value.video_file;
    };

    const removeVideo = () => {
      videoFile.value = null;
      formData.video_url = '';
      if (videoInput.value) {
        videoInput.value.value = '';
      }
    };

    const formatFileSize = (bytes) => {
      if (bytes === 0) return '0 Bytes';
      const k = 1024;
      const sizes = ['Bytes', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    };

    // Load draft on mount
    const loadDraft = () => {
      if (!isEditing.value) {
        const draft = storageService.getDraft('video-form');
        if (draft) {
          Object.keys(draft).forEach(key => {
            if (formData.hasOwnProperty(key)) {
              formData[key] = draft[key];
            }
          });
        }
      }
    };

    // Lifecycle
    onMounted(async () => {
      loadDraft();
      if (isEditing.value) {
        await loadVideo();
      }
    });

    return {
      // Refs
      videoForm,
      videoInput,
      
      // Reactive data
      formData,
      errors,
      isLoading,
      loadingMessage,
      videoFile,
      uploadProgress,
      
      // Computed
      isEditing,
      isFormValid,
      
      // Methods
      handleSubmit,
      saveDraft,
      triggerVideoInput,
      handleVideoSelect,
      handleVideoDrop,
      removeVideo,
      formatFileSize
    };
  }
};
</script>
