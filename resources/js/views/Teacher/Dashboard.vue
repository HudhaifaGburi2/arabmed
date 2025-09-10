<template>
  <section>
    <h1>Teacher Dashboard</h1>
    <div class="grid">
      <div class="card"><h3>My Courses</h3><p>{{ stats.courses }}</p></div>
      <div class="card"><h3>My Videos</h3><p>{{ stats.videos }}</p></div>
      <div class="card"><h3>My Exams</h3><p>{{ stats.exams }}</p></div>
    </div>
  </section>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import api from '../../services/api'

const stats = ref({ courses: 0, videos: 0, exams: 0 })

onMounted(async () => {
  // Example API calls to populate teacher stats
  const courses = await api.get('/courses')
  stats.value.courses = courses.data?.data?.length || 0
})
</script>
<style scoped>
.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem}
.card{background:#fff;padding:1rem;border:1px solid #e5e7eb;border-radius:.5rem}
</style>
