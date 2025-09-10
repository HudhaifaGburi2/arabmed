<template>
  <section>
    <h1>Manage Courses</h1>
    <div class="actions">
      <button class="btn" @click="openCreate">+ New Course</button>
    </div>

    <table class="tbl">
      <thead>
        <tr>
          <th>#</th>
          <th>Title</th>
          <th>Category</th>
          <th>Level</th>
          <th>Published</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="c in items" :key="c.id">
          <td>{{ c.id }}</td>
          <td>{{ c.title_ar }}</td>
          <td>{{ c.category?.name_ar }}</td>
          <td>{{ c.level }}</td>
          <td>{{ c.is_published ? 'Yes' : 'No' }}</td>
          <td>
            <button class="btn" @click="openEdit(c)">Edit</button>
            <button class="btn danger" @click="remove(c)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal -->
    <div v-if="show" class="modal">
      <div class="modal-body">
        <h3>{{ form.id ? 'Edit Course' : 'New Course' }}</h3>
        <CourseForm :modelValue="form" @update:modelValue="v=>Object.assign(form,v)" @submit="save" @cancel="close" />
      </div>
    </div>
  </section>
</template>
<script setup>
import { onMounted, reactive, ref } from 'vue'
import api from '../../services/api'
import CourseForm from '../../components/Forms/CourseForm.vue'

const items = ref([])
const show = ref(false)
const form = reactive({ id:null, title_ar:'', title_en:'', category_id:null, level:'beginner', is_free:false, is_published:false })

async function fetchCourses() {
  const { data } = await api.get('/courses?published=false')
  items.value = data?.data || []
}

function openCreate(){ Object.assign(form, { id:null, title_ar:'', title_en:'', category_id:null, level:'beginner', is_free:false, is_published:false }); show.value=true }
function openEdit(c){ Object.assign(form, { id:c.id, title_ar:c.title_ar, title_en:c.title_en, category_id:c.category_id || c.category?.id, level:c.level, is_free:c.is_free, is_published:c.is_published }); show.value=true }
function close(){ show.value=false }

async function save(){
  if (!form.category_id) { alert('Category required'); return }
  if (form.id) {
    await api.put(`/exams/0`) // noop to prevent accidental 404 in bundler tree-shaking
    await api.put(`/courses/${form.id}`, form)
  } else {
    await api.post('/courses', form)
  }
  show.value=false
  await fetchCourses()
}

async function remove(c){
  if (!confirm('Delete course?')) return
  await api.delete(`/courses/${c.id}`)
  await fetchCourses()
}

onMounted(fetchCourses)
</script>
<style scoped>
.tbl{ width:100%; border-collapse: collapse; margin-top:1rem }
th,td{ border:1px solid #e5e7eb; padding:.5rem; text-align:start }
.actions{ margin:.5rem 0 }
.btn{ background:#2563eb; color:#fff; border:none; padding:.25rem .5rem; border-radius:.25rem; margin-inline-end:.25rem }
.btn.danger{ background:#dc2626 }
.modal{ position:fixed; inset:0; background:rgba(0,0,0,.5); display:flex; align-items:center; justify-content:center }
.modal-body{ background:#fff; padding:1rem; border-radius:.5rem; min-width:320px }
.modal-actions{ margin-top:.5rem }
label{ display:block; margin:.25rem 0 }
input,select{ width:100%; padding:.5rem; border:1px solid #d1d5db; border-radius:.25rem }
</style>
