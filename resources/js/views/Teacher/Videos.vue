<template>
  <section>
    <h1>Manage Videos</h1>
    <div class="actions">
      <select v-model.number="courseId" @change="fetchVideos" class="select">
        <option :value="null">-- Select Course --</option>
        <option v-for="c in courses" :key="c.id" :value="c.id">#{{ c.id }} - {{ c.title_ar }}</option>
      </select>
      <button class="btn" @click="openCreate" :disabled="!courseId">+ New Video</button>
    </div>

    <table class="tbl">
      <thead>
        <tr>
          <th>#</th>
          <th>Title</th>
          <th>Course</th>
          <th>Duration (s)</th>
          <th>Published</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="v in items" :key="v.id">
          <td>{{ v.id }}</td>
          <td>{{ v.title_ar }}</td>
          <td>{{ v.course_id }}</td>
          <td>{{ v.duration_seconds }}</td>
          <td>{{ v.is_published ? 'Yes' : 'No' }}</td>
          <td>
            <button class="btn" @click="openEdit(v)">Edit</button>
            <button class="btn danger" @click="remove(v)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="show" class="modal">
      <div class="modal-body">
        <h3>{{ form.id ? 'Edit Video' : 'New Video' }}</h3>
        <VideoForm :modelValue="form" @update:modelValue="v=>Object.assign(form,v)" @submit="save" @cancel="close" />
      </div>
    </div>
  </section>
</template>
<script setup>
import { onMounted, reactive, ref } from 'vue'
import api from '../../services/api'
import VideoForm from '../../components/Forms/VideoForm.vue'

const items = ref([])
const courses = ref([])
const courseId = ref(null)
const show = ref(false)
const form = reactive({ id:null, course_id:null, title_ar:'', title_en:'', video_url:'', thumbnail_url:'', duration_seconds:null, video_quality:'', is_free:false, is_published:false })

async function loadCourses(){
  const { data } = await api.get('/courses?published=false')
  courses.value = data?.data || []
  if (!courseId.value && courses.value.length) courseId.value = courses.value[0].id
}

async function fetchVideos() {
  if (!courseId.value) { items.value = []; return }
  const vids = await api.get(`/courses/${courseId.value}/videos`)
  items.value = vids.data?.data || []
}

function openCreate(){ Object.assign(form, { id:null, course_id:courseId.value, title_ar:'', title_en:'', video_url:'', thumbnail_url:'', duration_seconds:null, video_quality:'', is_free:false, is_published:false }); show.value=true }
function openEdit(v){ Object.assign(form, { id:v.id, course_id:v.course_id, title_ar:v.title_ar, title_en:v.title_en, video_url:v.video_url, thumbnail_url:v.thumbnail_url, duration_seconds:v.duration_seconds, video_quality:v.video_quality, is_free:v.is_free, is_published:v.is_published }); show.value=true }
function close(){ show.value=false }

async function save(){
  if (!form.course_id || !form.title_ar || !form.video_url) { alert('Missing required fields'); return }
  if (form.id) {
    await api.put(`/videos/${form.id}`, form)
  } else {
    await api.post('/videos', form)
  }
  show.value=false
  await fetchVideos()
}

async function remove(v){
  if (!confirm('Delete video?')) return
  await api.delete(`/videos/${v.id}`)
  await fetchVideos()
}

onMounted(async () => { await loadCourses(); await fetchVideos() })
</script>
<style scoped>
.tbl{ width:100%; border-collapse: collapse; margin-top:1rem }
th,td{ border:1px solid #e5e7eb; padding:.5rem; text-align:start }
.actions{ margin:.5rem 0 }
.btn{ background:#2563eb; color:#fff; border:none; padding:.25rem .5rem; border-radius:.25rem; margin-inline-end:.25rem }
.btn.danger{ background:#dc2626 }
.modal{ position:fixed; inset:0; background:rgba(0,0,0,.5); display:flex; align-items:center; justify-content:center }
.modal-body{ background:#fff; padding:1rem; border-radius:.5rem; min-width:360px }
.modal-actions{ margin-top:.5rem }
label{ display:block; margin:.25rem 0 }
input,select{ width:100%; padding:.5rem; border:1px solid #d1d5db; border-radius:.25rem }
</style>
