<template>
  <div class="course-form">
    <div class="form-header">
      <h2 class="form-title">
        {{ isEditing ? 'تحرير الدورة' : 'إنشاء دورة جديدة' }}
      </h2>
      <p class="form-subtitle">
        {{ isEditing ? 'قم بتحديث معلومات الدورة' : 'أدخل تفاصيل الدورة الجديدة' }}
      </p>
    </div>

    <form @submit.prevent="handleSubmit" class="course-form-content" ref="courseForm">
      <!-- Basic Information -->
      <div class="form-section">
        <h3 class="section-title">المعلومات الأساسية</h3>
        
        <div class="form-row">
          <div class="form-group">
            <label for="title" class="form-label required">عنوان الدورة</label>
            <input
              type="text"
              id="title"
              name="title"
              v-model="formData.title"
              class="form-input"
              :class="{ 'error': errors.title }"
              placeholder="أدخل عنوان الدورة"
              required
            />
            <div v-if="errors.title" class="field-error">{{ errors.title }}</div>
          </div>

          <div class="form-group">
            <label for="category_id" class="form-label required">التصنيف</label>
            <select
              id="category_id"
              name="category_id"
              v-model="formData.category_id"
              class="form-select"
              :class="{ 'error': errors.category_id }"
              required
            >
              <option value="">اختر التصنيف</option>
              <option
                v-for="category in categories"
                :key="category.id"
                :value="category.id"
              >
                {{ category.name }}
              </option>
            </select>
            <div v-if="errors.category_id" class="field-error">{{ errors.category_id }}</div>
          </div>
        </div>

        <div class="form-group">
          <label for="description" class="form-label required">وصف الدورة</label>
          <textarea
            id="description"
            name="description"
            v-model="formData.description"
            class="form-textarea"
            :class="{ 'error': errors.description }"
            rows="4"
            placeholder="أدخل وصفاً مفصلاً للدورة"
            required
          ></textarea>
          <div v-if="errors.description" class="field-error">{{ errors.description }}</div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="level" class="form-label required">مستوى الدورة</label>
            <select
              id="level"
              name="level"
              v-model="formData.level"
              class="form-select"
              :class="{ 'error': errors.level }"
              required
            >
              <option value="">اختر المستوى</option>
              <option value="beginner">مبتدئ</option>
              <option value="intermediate">متوسط</option>
              <option value="advanced">متقدم</option>
            </select>
            <div v-if="errors.level" class="field-error">{{ errors.level }}</div>
          </div>

          <div class="form-group">
            <label for="language" class="form-label required">لغة الدورة</label>
            <select
              id="language"
              name="language"
              v-model="formData.language"
              class="form-select"
              :class="{ 'error': errors.language }"
              required
            >
              <option value="">اختر اللغة</option>
              <option value="ar">العربية</option>
              <option value="en">الإنجليزية</option>
              <option value="fr">الفرنسية</option>
            </select>
            <div v-if="errors.language" class="field-error">{{ errors.language }}</div>
          </div>
        </div>
      </div>

      <!-- Pricing & Duration -->
      <div class="form-section">
        <h3 class="section-title">التسعير والمدة</h3>
        
        <div class="form-row">
          <div class="form-group">
            <label for="price" class="form-label required">السعر</label>
            <div class="input-group">
              <input
                type="number"
                id="price"
                name="price"
                v-model="formData.price"
                class="form-input"
                :class="{ 'error': errors.price }"
                min="0"
                step="0.01"
                placeholder="0.00"
                required
              />
              <span class="input-addon">ر.س</span>
            </div>
            <div v-if="errors.price" class="field-error">{{ errors.price }}</div>
          </div>

          <div class="form-group">
            <label for="duration_hours" class="form-label required">مدة الدورة (ساعات)</label>
            <input
              type="number"
              id="duration_hours"
              name="duration_hours"
              v-model="formData.duration_hours"
              class="form-input"
              :class="{ 'error': errors.duration_hours }"
              min="1"
              placeholder="عدد الساعات"
              required
            />
            <div v-if="errors.duration_hours" class="field-error">{{ errors.duration_hours }}</div>
          </div>
        </div>

        <div class="form-group">
          <div class="checkbox-group">
            <label class="checkbox-label">
              <input
                type="checkbox"
                v-model="formData.is_free"
                class="checkbox-input"
                @change="handleFreeChange"
              />
              <span class="checkbox-custom"></span>
              <span class="checkbox-text">دورة مجانية</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Media & Resources -->
      <div class="form-section">
        <h3 class="section-title">الوسائط والموارد</h3>
        
        <div class="form-group">
          <label for="thumbnail" class="form-label">صورة الدورة</label>
          <div class="file-upload-area" @click="triggerFileInput" @dragover.prevent @drop.prevent="handleFileDrop">
            <input
              type="file"
              id="thumbnail"
              name="thumbnail"
              ref="thumbnailInput"
              @change="handleFileSelect"
              accept="image/*"
              class="file-input"
              style="display: none;"
            />
            <div v-if="!thumbnailPreview" class="file-upload-placeholder">
              <i class="material-icons">cloud_upload</i>
              <p>اضغط أو اسحب الصورة هنا</p>
              <small>PNG, JPG, GIF حتى 5MB</small>
            </div>
            <div v-else class="file-preview">
              <img :src="thumbnailPreview" alt="معاينة الصورة" class="preview-image" />
              <button type="button" @click.stop="removeThumbnail" class="remove-file-btn">
                <i class="material-icons">close</i>
              </button>
            </div>
          </div>
          <div v-if="errors.thumbnail" class="field-error">{{ errors.thumbnail }}</div>
        </div>

        <div class="form-group">
          <label for="tags" class="form-label">الكلمات المفتاحية</label>
          <div class="tags-input">
            <div class="tags-list">
              <span
                v-for="(tag, index) in formData.tags"
                :key="index"
                class="tag-item"
              >
                {{ tag }}
                <button type="button" @click="removeTag(index)" class="tag-remove">
                  <i class="material-icons">close</i>
                </button>
              </span>
            </div>
            <input
              type="text"
              v-model="newTag"
              @keydown.enter.prevent="addTag"
              @keydown.comma.prevent="addTag"
              class="tag-input"
              placeholder="أضف كلمة مفتاحية واضغط Enter"
            />
          </div>
        </div>
      </div>

      <!-- Settings -->
      <div class="form-section">
        <h3 class="section-title">الإعدادات</h3>
        
        <div class="form-row">
          <div class="form-group">
            <label for="max_students" class="form-label">الحد الأقصى للطلاب</label>
            <input
              type="number"
              id="max_students"
              name="max_students"
              v-model="formData.max_students"
              class="form-input"
              min="1"
              placeholder="غير محدود"
            />
          </div>

          <div class="form-group">
            <label for="certificate_template" class="form-label">قالب الشهادة</label>
            <select
              id="certificate_template"
              name="certificate_template"
              v-model="formData.certificate_template"
              class="form-select"
            >
              <option value="">بدون شهادة</option>
              <option value="basic">شهادة أساسية</option>
              <option value="advanced">شهادة متقدمة</option>
              <option value="custom">شهادة مخصصة</option>
            </select>
          </div>
        </div>

        <div class="checkbox-group">
          <label class="checkbox-label">
            <input
              type="checkbox"
              v-model="formData.is_published"
              class="checkbox-input"
            />
            <span class="checkbox-custom"></span>
            <span class="checkbox-text">نشر الدورة فوراً</span>
          </label>

          <label class="checkbox-label">
            <input
              type="checkbox"
              v-model="formData.allow_comments"
              class="checkbox-input"
            />
            <span class="checkbox-custom"></span>
            <span class="checkbox-text">السماح بالتعليقات</span>
          </label>

          <label class="checkbox-label">
            <input
              type="checkbox"
              v-model="formData.send_notifications"
              class="checkbox-input"
            />
            <span class="checkbox-custom"></span>
            <span class="checkbox-text">إرسال إشعارات للطلاب</span>
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
          type="button"
          @click="resetForm"
          class="btn btn-outline"
          :disabled="isLoading"
        >
          <i class="material-icons">refresh</i>
          إعادة تعيين
        </button>
        
        <button
          type="submit"
          class="btn btn-primary"
          :disabled="isLoading || !isFormValid"
        >
          <i v-if="isLoading" class="material-icons spinning">refresh</i>
          <i v-else class="material-icons">{{ isEditing ? 'save' : 'add' }}</i>
          {{ isEditing ? 'تحديث الدورة' : 'إنشاء الدورة' }}
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
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { apiService } from '../services/api.js';
import { validationService } from '../services/validation.js';
import { storageService } from '../services/storage.js';

export default {
  name: 'CourseForm',
  props: {
    courseId: {
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
    const courseForm = ref(null);
    const thumbnailInput = ref(null);
    const isLoading = ref(false);
    const loadingMessage = ref('');
    const categories = ref([]);
    const errors = ref({});
    const newTag = ref('');
    const thumbnailPreview = ref(null);

    // Form data
    const formData = reactive({
      title: '',
      description: '',
      category_id: '',
      level: '',
      language: 'ar',
      price: 0,
      duration_hours: 1,
      is_free: false,
      thumbnail: null,
      tags: [],
      max_students: null,
      certificate_template: '',
      is_published: false,
      allow_comments: true,
      send_notifications: true,
      ...props.initialData
    });

    // Computed properties
    const isEditing = computed(() => !!props.courseId);
    
    const isFormValid = computed(() => {
      return formData.title && 
             formData.description && 
             formData.category_id && 
             formData.level && 
             formData.language &&
             (formData.is_free || formData.price > 0);
    });

    // Validation rules
    const validationRules = {
      title: 'required|minLength:3|maxLength:100',
      description: 'required|minLength:10|maxLength:1000',
      category_id: 'required',
      level: 'required|in:beginner,intermediate,advanced',
      language: 'required|in:ar,en,fr',
      price: 'required|numeric|min:0',
      duration_hours: 'required|integer|min:1',
      thumbnail: 'file|image|fileSize:5'
    };

    // Methods
    const loadCategories = async () => {
      try {
        const response = await apiService.getCategories();
        categories.value = response.data || response;
      } catch (error) {
        console.error('Error loading categories:', error);
      }
    };

    const loadCourse = async () => {
      if (!props.courseId) return;
      
      try {
        isLoading.value = true;
        loadingMessage.value = 'جاري تحميل بيانات الدورة...';
        
        const course = await apiService.getCourse(props.courseId);
        
        // Populate form data
        Object.keys(formData).forEach(key => {
          if (course[key] !== undefined) {
            formData[key] = course[key];
          }
        });

        // Handle thumbnail
        if (course.thumbnail_url) {
          thumbnailPreview.value = course.thumbnail_url;
        }

      } catch (error) {
        emit('error', 'فشل في تحميل بيانات الدورة');
      } finally {
        isLoading.value = false;
      }
    };

    const handleSubmit = async () => {
      try {
        // Clear previous errors
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
        loadingMessage.value = isEditing.value ? 'جاري تحديث الدورة...' : 'جاري إنشاء الدورة...';

        // Prepare form data for submission
        const submitData = new FormData();
        Object.keys(formData).forEach(key => {
          if (key === 'tags') {
            submitData.append(key, JSON.stringify(formData[key]));
          } else if (key === 'thumbnail' && formData[key]) {
            submitData.append(key, formData[key]);
          } else if (formData[key] !== null && formData[key] !== '') {
            submitData.append(key, formData[key]);
          }
        });

        let response;
        if (isEditing.value) {
          response = await apiService.updateCourse(props.courseId, submitData);
        } else {
          response = await apiService.createCourse(submitData);
        }

        // Clear draft
        storageService.removeDraft('course-form');
        
        emit('success', {
          message: isEditing.value ? 'تم تحديث الدورة بنجاح' : 'تم إنشاء الدورة بنجاح',
          course: response
        });

      } catch (error) {
        if (error.response?.status === 422) {
          // Handle validation errors
          const validationErrors = error.response.data.errors;
          Object.keys(validationErrors).forEach(field => {
            errors.value[field] = validationErrors[field][0];
          });
        } else {
          emit('error', 'حدث خطأ أثناء حفظ الدورة');
        }
      } finally {
        isLoading.value = false;
      }
    };

    const saveDraft = () => {
      storageService.saveDraft('course-form', formData);
      // Show success message
      const event = new CustomEvent('showNotification', {
        detail: { message: 'تم حفظ المسودة', type: 'success' }
      });
      window.dispatchEvent(event);
    };

    const resetForm = () => {
      Object.keys(formData).forEach(key => {
        if (key === 'tags') {
          formData[key] = [];
        } else if (key === 'language') {
          formData[key] = 'ar';
        } else if (key === 'allow_comments') {
          formData[key] = true;
        } else if (key === 'send_notifications') {
          formData[key] = true;
        } else if (typeof formData[key] === 'boolean') {
          formData[key] = false;
        } else if (typeof formData[key] === 'number') {
          formData[key] = key === 'duration_hours' ? 1 : 0;
        } else {
          formData[key] = '';
        }
      });
      
      thumbnailPreview.value = null;
      errors.value = {};
      storageService.removeDraft('course-form');
    };

    const handleFreeChange = () => {
      if (formData.is_free) {
        formData.price = 0;
      }
    };

    const triggerFileInput = () => {
      thumbnailInput.value?.click();
    };

    const handleFileSelect = (event) => {
      const file = event.target.files[0];
      if (file) {
        processFile(file);
      }
    };

    const handleFileDrop = (event) => {
      const file = event.dataTransfer.files[0];
      if (file) {
        processFile(file);
      }
    };

    const processFile = (file) => {
      // Validate file
      const validation = validationService.validateField(file, 'file|image|fileSize:5');
      if (!validation.valid) {
        errors.value.thumbnail = validation.errors[0].message;
        return;
      }

      formData.thumbnail = file;
      
      // Create preview
      const reader = new FileReader();
      reader.onload = (e) => {
        thumbnailPreview.value = e.target.result;
      };
      reader.readAsDataURL(file);
      
      // Clear error
      delete errors.value.thumbnail;
    };

    const removeThumbnail = () => {
      formData.thumbnail = null;
      thumbnailPreview.value = null;
      if (thumbnailInput.value) {
        thumbnailInput.value.value = '';
      }
    };

    const addTag = () => {
      const tag = newTag.value.trim();
      if (tag && !formData.tags.includes(tag)) {
        formData.tags.push(tag);
        newTag.value = '';
      }
    };

    const removeTag = (index) => {
      formData.tags.splice(index, 1);
    };

    // Load draft on mount
    const loadDraft = () => {
      if (!isEditing.value) {
        const draft = storageService.getDraft('course-form');
        if (draft) {
          Object.keys(draft).forEach(key => {
            if (formData.hasOwnProperty(key)) {
              formData[key] = draft[key];
            }
          });
        }
      }
    };

    // Auto-save draft
    const autoSaveDraft = () => {
      if (!isEditing.value) {
        storageService.saveDraft('course-form', formData);
      }
    };

    // Watchers
    watch(formData, autoSaveDraft, { deep: true });

    // Lifecycle
    onMounted(async () => {
      await loadCategories();
      loadDraft();
      if (isEditing.value) {
        await loadCourse();
      }
    });

    return {
      // Refs
      courseForm,
      thumbnailInput,
      
      // Reactive data
      formData,
      errors,
      categories,
      isLoading,
      loadingMessage,
      newTag,
      thumbnailPreview,
      
      // Computed
      isEditing,
      isFormValid,
      
      // Methods
      handleSubmit,
      saveDraft,
      resetForm,
      handleFreeChange,
      triggerFileInput,
      handleFileSelect,
      handleFileDrop,
      removeThumbnail,
      addTag,
      removeTag
    };
  }
};
</script>
