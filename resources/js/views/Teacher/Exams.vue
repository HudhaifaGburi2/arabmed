<template>
  <section>
    <h1>Manage Exams</h1>

    <div class="actions">
      <select v-model.number="courseId" @change="fetchExams" class="select">
        <option :value="null">-- Select Course --</option>
        <option v-for="c in courses" :key="c.id" :value="c.id">#{{ c.id }} - {{ c.title_ar }}</option>
      </select>
      <button class="btn" @click="openCreate" :disabled="!courseId">+ New Exam</button>
    </div>

    <table class="tbl">
      <thead>
        <tr>
          <th>#</th>
          <th>Title</th>
          <th>Course</th>
          <th>Questions</th>
          <th>Active</th>
          <th>Window</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="e in items" :key="e.id">
          <td>{{ e.id }}</td>
          <td>{{ e.title_ar }}</td>
          <td>{{ e.course_id }}</td>
          <td>{{ e.questions_count }}</td>
          <td>{{ e.is_active ? 'Yes' : 'No' }}</td>
          <td>{{ e.starts_at ? new Date(e.starts_at).toLocaleString() : '-' }} → {{ e.ends_at ? new Date(e.ends_at).toLocaleString() : '-' }}</td>
          <td>
            <button class="btn" @click="openEdit(e)">Edit</button>
            <button class="btn" @click="openQuestions(e)">Questions</button>
            <button class="btn danger" @click="remove(e)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Exam Modal -->
    <div v-if="showExam" class="modal">
      <div class="modal-body">
        <h3>{{ examForm.id ? 'Edit Exam' : 'New Exam' }}</h3>
        <ExamForm :modelValue="examForm" @update:modelValue="v=>Object.assign(examForm,v)" @submit="saveExam" @cancel="closeExam" />
      </div>
    </div>

    <!-- Questions Modal (lightweight management) -->
    <div v-if="showQuestions" class="modal">
      <div class="modal-body modal-lg">
        <div class="modal-header">
          <h3>Questions for: {{ currentExam?.title_ar }}</h3>
          <button class="btn" @click="closeQuestions">Close</button>
        </div>

        <div class="q-list">
          <div class="q-item" v-for="q in questions" :key="q.id">
            <div>
              <strong>#{{ q.id }}</strong> {{ q.question_ar }}
              <em>({{ q.question_type }}, {{ q.marks }} marks)</em>
            </div>
            <div class="q-actions">
              <button class="btn" @click="editQuestion(q)">Edit</button>
              <button class="btn danger" @click="removeQuestion(q)">Delete</button>
            </div>
          </div>
        </div>

        <hr />
        <h4>{{ qForm.id ? 'Edit Question' : 'Add Question' }}</h4>
        <form @submit.prevent="saveQuestion" class="q-form">
          <label>Question (AR) <input v-model="qForm.question_ar" required /></label>
          <label>Type
            <select v-model="qForm.question_type">
              <option value="multiple_choice">multiple_choice</option>
              <option value="true_false">true_false</option>
              <option value="short_answer">short_answer</option>
              <option value="essay">essay</option>
            </select>
          </label>
          <label>Marks <input v-model.number="qForm.marks" type="number" min="0" step="0.1" /></label>
          <button class="btn" type="submit">{{ qForm.id ? 'Update' : 'Add' }}</button>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import api from '../../services/api'
import ExamForm from '../../components/Forms/ExamForm.vue'

const items = ref([])
const courses = ref([])
const courseId = ref(null)
const showExam = ref(false)
const examForm = reactive({ id:null, course_id:null, title_ar:'', title_en:'', time_limit_minutes:null, max_attempts:null, passing_score:null, total_marks:null, is_active:false, starts_at:'', ends_at:'' })

const showQuestions = ref(false)
const currentExam = ref(null)
const questions = ref([])
const qForm = reactive({ id:null, question_ar:'', question_type:'multiple_choice', marks:1 })

async function loadCourses(){
  const { data } = await api.get('/courses?published=false')
  courses.value = data?.data || []
  if (!courseId.value && courses.value.length) courseId.value = courses.value[0].id
}

async function fetchExams() {
  if (!courseId.value) { items.value = []; return }
  const res = await api.get(`/courses/${courseId.value}/exams`)
  items.value = res.data?.data || []
}

function openCreate(){ Object.assign(examForm, { id:null, course_id:courseId.value, title_ar:'', title_en:'', time_limit_minutes:null, max_attempts:null, passing_score:null, total_marks:null, is_active:false, starts_at:'', ends_at:'' }); showExam.value=true }
function openEdit(e){ Object.assign(examForm, { id:e.id, course_id:e.course_id, title_ar:e.title_ar, title_en:e.title_en, time_limit_minutes:e.time_limit_minutes, max_attempts:e.max_attempts, passing_score:e.passing_score, total_marks:e.total_marks, is_active:e.is_active, starts_at:toLocal(e.starts_at), ends_at:toLocal(e.ends_at) }); showExam.value=true }
function closeExam(){ showExam.value=false }

async function saveExam(){
  const payload = { ...examForm }
  if (!payload.course_id || !payload.title_ar) { alert('Missing required fields'); return }
  // Convert datetime-local back to ISO if present
  if (payload.starts_at) payload.starts_at = new Date(payload.starts_at).toISOString()
  if (payload.ends_at) payload.ends_at = new Date(payload.ends_at).toISOString()

  if (payload.id) {
    await api.put(`/exams/${payload.id}`, payload)
  } else {
    await api.post('/exams', payload)
  }
  showExam.value=false
  await fetchExams()
}

async function remove(e){
  if (!confirm('Delete exam?')) return
  await api.delete(`/exams/${e.id}`)
  await fetchExams()
}

// Questions management
async function openQuestions(e){
  currentExam.value = e
  showQuestions.value = true
  await loadQuestions()
}
function closeQuestions(){ showQuestions.value = false }

async function loadQuestions(){
  // We already include questions in show endpoint; use it to refresh
  const res = await api.get(`/exams/${currentExam.value.id}`)
  questions.value = res.data?.questions || []
}

function editQuestion(q){ Object.assign(qForm, { id:q.id, question_ar:q.question_ar, question_type:q.question_type, marks:q.marks }) }

async function saveQuestion(){
  if (!qForm.question_ar) { alert('Question is required'); return }
  const payload = { question_ar:qForm.question_ar, question_type:qForm.question_type, marks:qForm.marks }
  if (qForm.id) {
    await api.put(`/exams/${currentExam.value.id}/questions/${qForm.id}`, payload)
  } else {
    await api.post(`/exams/${currentExam.value.id}/questions`, payload)
  }
  Object.assign(qForm, { id:null, question_ar:'', question_type:'multiple_choice', marks:1 })
  await loadQuestions()
}

async function removeQuestion(q){
  if (!confirm('Delete question?')) return
  await api.delete(`/exams/${currentExam.value.id}/questions/${q.id}`)
  await loadQuestions()
}

function toLocal(iso){ if(!iso) return ''; const d=new Date(iso); const pad=(n)=>String(n).padStart(2,'0'); return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}` }

onMounted(async () => { await loadCourses(); await fetchExams() })
</script>

<style scoped>
.tbl{ width:100%; border-collapse: collapse; margin-top:1rem }
th,td{ border:1px solid #e5e7eb; padding:.5rem; text-align:start }
.actions{ margin:.5rem 0 }
.btn{ background:#2563eb; color:#fff; border:none; padding:.25rem .5rem; border-radius:.25rem; margin-inline-end:.25rem }
.btn.danger{ background:#dc2626 }
.modal{ position:fixed; inset:0; background:rgba(0,0,0,.5); display:flex; align-items:center; justify-content:center }
.modal-body{ background:#fff; padding:1rem; border-radius:.5rem; min-width:380px }
.modal-lg{ min-width:720px }
.modal-header{ display:flex; justify-content:space-between; align-items:center; margin-bottom:.5rem }
.q-list{ max-height:300px; overflow:auto; background:#f9fafb; border:1px solid #e5e7eb; border-radius:.5rem; padding:.5rem }
.q-item{ display:flex; justify-content:space-between; align-items:center; background:#fff; border:1px solid #e5e7eb; border-radius:.5rem; padding:.5rem .75rem; margin:.25rem 0 }
.q-actions .btn{ margin-inline-start:.25rem }
.q-form label{ display:block; margin:.25rem 0 }
input,select{ width:100%; padding:.5rem; border:1px solid #d1d5db; border-radius:.25rem }
</style>
