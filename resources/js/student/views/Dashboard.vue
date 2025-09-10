<template>
  <section>
    <h1>Student Dashboard</h1>
    <div class="grid">
      <div class="card"><h3>My Enrollments</h3><p>{{ stats.enrollments }}</p></div>
      <div class="card"><h3>Videos Progress</h3><p>{{ stats.videos }}</p></div>
      <div class="card"><h3>Exams Taken</h3><p>{{ stats.exams }}</p></div>
    </div>
  </section>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import api from '../../services/api'
import { useStudentAuthStore } from '../store/auth'

const auth = useStudentAuthStore()
const stats = ref({ enrollments: 0, videos: 0, exams: 0 })

onMounted(async () => {
  await auth.bootstrap()
  if (auth.user?.id) {
    const vp = await api.get(`/users/${auth.user.id}/progress/videos`)
    stats.value.videos = vp.data?.meta?.total ?? (vp.data?.data?.length || 0)
  }
})
</script>
<style scoped>
.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem}
.card{background:#fff;padding:1rem;border:1px solid #e5e7eb;border-radius:.5rem}
</style>
