<template>
  <section>
    <h1>Manage Videos</h1>
    <div class="actions">
      <button class="btn" @click="openCreate">+ New Video</button>
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
        <form @submit.prevent="save">
          <label>Course ID <input v-model.number="form.course_id" type="number" required /></label>
          <label>Title (AR) <input v-model="form.title_ar" required /></label>
          <label>Title (EN) <input v-model="form.title_en" /></label>
          <label>Video URL <input v-model="form.video_url" required /></label>
          <label>Thumbnail URL <input v-model="form.thumbnail_url" /></label>
          <label>Duration (seconds) <input v-model.number="form.duration_seconds" type="number" min="0" /></label>
          <label>Quality
            <select v-model="form.video_quality">
              <option value="">-</option>
              <option value="360p">360p</option>
              <option value="720p">720p</option>
              <option value="1080p">1080p</option>
              <option value="4k">4k</option>
            </select>
          </label>
          <label>Is Free <input type="checkbox" v-model="form.is_free" /></label>
          <label>Is Published <input type="checkbox" v-model="form.is_published" /></label>
          <div class="modal-actions">
            <button class="btn" type="submit">Save</button>
            <button class="btn" type="button" @click="close">Close</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>
<script setup>
import { onMounted, reactive, ref } from 'vue'
import api from '../../services/api'

const items = ref([])
const show = ref(false)
const form = reactive({ id:null, course_id:null, title_ar:'', title_en:'', video_url:'', thumbnail_url:'', duration_seconds:null, video_quality:'', is_free:false, is_published:false })

async function fetchVideos() {
  // list videos via first course for simplicity (or build a dedicated listing endpoint)
  const courses = await api.get('/courses?published=false')
  const first = courses.data?.data?.[0]
  if (first) {
    const vids = await api.get(`/courses/${first.id}/videos`)
    items.value = vids.data?.data || []
  } else {
    items.value = []
  }
}

function openCreate(){ Object.assign(form, { id:null, course_id:null, title_ar:'', title_en:'', video_url:'', thumbnail_url:'', duration_seconds:null, video_quality:'', is_free:false, is_published:false }); show.value=true }
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

onMounted(fetchVideos)
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
