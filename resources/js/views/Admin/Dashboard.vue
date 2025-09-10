<template>
  <section>
    <h1>Admin Dashboard</h1>
    <div class="grid">
      <div class="card"><h3>Total Users</h3><p>{{ stats.users }}</p></div>
      <div class="card"><h3>Total Courses</h3><p>{{ stats.courses }}</p></div>
      <div class="card"><h3>Total Exams</h3><p>{{ stats.exams }}</p></div>
    </div>
  </section>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import api from '../../services/api'

const stats = ref({ users: 0, courses: 0, exams: 0 })

onMounted(async () => {
  // Example: fetch counts via existing endpoints (simplified demo)
  const [courses, exams] = await Promise.all([
    api.get('/courses'),
    api.get('/exams/1').catch(()=>({ data: {} })) // placeholder
  ])
  stats.value.courses = courses.data?.meta?.total ?? (courses.data?.data?.length || 0)
  stats.value.exams = 0 // replace with a proper /courses/:id/exams listing or dedicated stats API
  // Users would come from an admin endpoint; placeholder 0 for now.
})
</script>
<style scoped>
.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem}
.card{background:#fff;padding:1rem;border:1px solid #e5e7eb;border-radius:.5rem}
</style>
