<template>
  <section>
    <h1>الاختبارات</h1>
    <div v-if="!course">اختر دورة لعرض الاختبارات.</div>
    <div v-else>
      <ul>
        <li v-for="e in items" :key="e.id">
          <strong>{{ e.title_ar }}</strong>
          <button class="btn" @click="start(e)">بدء محاولة</button>
        </li>
      </ul>
    </div>
  </section>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import api from '../../services/api'

const course = ref(null)
const items = ref([])

async function load() {
  const { data } = await api.get('/courses?lang=ar')
  course.value = data?.data?.[0]
  if (course.value) {
    const exams = await api.get(`/courses/${course.value.id}/exams`)
    items.value = exams.data?.data || []
  }
}

async function start(exam) {
  const res = await api.post(`/exams/${exam.id}/attempts/start`)
  alert(`بدأت محاولة #${res.data.attempt_id}`)
}

onMounted(load)
</script>
<style scoped>
.btn{ margin-inline-start:.5rem; background:#10b981; color:#fff; border:none; padding:.25rem .5rem; border-radius:.25rem }
ul{ padding:0; list-style:none }
li{ background:#fff; border:1px solid #e5e7eb; border-radius:.5rem; padding:.5rem .75rem; margin:.5rem 0 }
</style>
