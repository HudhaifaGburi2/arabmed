<template>
  <form @submit.prevent="onSubmit" class="form">
    <label>Course ID
      <input v-model.number="model.course_id" type="number" required class="input" />
      <div class="err" v-if="errors?.course_id">{{ errors.course_id[0] }}</div>
    </label>
    <label>Title (AR)
      <input v-model="model.title_ar" required class="input" />
      <div class="err" v-if="errors?.title_ar">{{ errors.title_ar[0] }}</div>
    </label>
    <label>Title (EN)
      <input v-model="model.title_en" class="input" />
    </label>
    <label>Video URL
      <input v-model="model.video_url" required class="input" />
      <div class="err" v-if="errors?.video_url">{{ errors.video_url[0] }}</div>
    </label>
    <label>Thumbnail URL
      <input v-model="model.thumbnail_url" class="input" />
    </label>
    <label>Duration (seconds)
      <input v-model.number="model.duration_seconds" type="number" min="0" class="input" />
    </label>
    <label>Quality
      <select v-model="model.video_quality" class="input">
        <option value="">-</option>
        <option value="360p">360p</option>
        <option value="720p">720p</option>
        <option value="1080p">1080p</option>
        <option value="4k">4k</option>
      </select>
    </label>
    <label class="chk"><input type="checkbox" v-model="model.is_free" /> Is Free</label>
    <label class="chk"><input type="checkbox" v-model="model.is_published" /> Published</label>

    <div class="actions">
      <button class="btn" type="submit">Save</button>
      <button class="btn" type="button" @click="$emit('cancel')">Cancel</button>
    </div>
  </form>
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({ modelValue: { type: Object, required: true }, errors: { type: Object, default: null } })
const emit = defineEmits(['update:modelValue','submit','cancel'])
const model = computed({
  get: () => props.modelValue,
  set: v => emit('update:modelValue', v)
})
function onSubmit(){ emit('submit', { ...props.modelValue }) }
 </script>
<style scoped>
label{ display:block; margin:.5rem 0 }
.input{ width:100%; padding:.5rem; border:1px solid #d1d5db; border-radius:.375rem }
.chk{ display:flex; align-items:center; gap:.5rem }
.err{ color:#b91c1c; font-size:.875rem; margin-top:.25rem }
.actions{ margin-top:.5rem }
.btn{ background:#2563eb; color:#fff; border:none; padding:.25rem .5rem; border-radius:.25rem; margin-inline-end:.25rem }
.form{ max-width:520px }
</style>
