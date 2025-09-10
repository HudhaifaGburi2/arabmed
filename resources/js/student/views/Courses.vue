<template>
  <section>
    <h1>الدورات</h1>
    <div class="grid">
      <CourseCard v-for="c in items" :key="c.id" :course="c" @open="open(c)" />
    </div>
  </section>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import api from '../../services/api'
import CourseCard from '../components/Cards/CourseCard.vue'

const items = ref([])

async function fetchCourses() {
  const { data } = await api.get('/courses?lang=ar')
  items.value = data?.data || []
}

function open(course) {
  // navigate or open modal (placeholder)
  alert(`فتح الدورة: ${course.title_ar}`)
}

onMounted(fetchCourses)
</script>
<style scoped>
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem}
</style>
