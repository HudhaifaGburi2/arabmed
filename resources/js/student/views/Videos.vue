<template>
  <section>
    <h1>الفيديوهات</h1>
    <ul>
      <li v-for="v in items" :key="v.id">
        <strong>{{ v.title_ar }}</strong>
        <small v-if="v.title_en"> ({{ v.title_en }})</small>
        <button class="btn" @click="watch(v)">مشاهدة</button>
      </li>
    </ul>
  </section>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import api from '../../services/api'

const items = ref([])

async function fetchVideos() {
  // For demo, pull videos of the first course
  const courses = await api.get('/courses?lang=ar')
  const first = courses.data?.data?.[0]
  if (first) {
    const vids = await api.get(`/courses/${first.id}/videos`)
    items.value = vids.data?.data || []
  } else {
    items.value = []
  }
}

function watch(v) {
  window.open(v.video_url, '_blank')
}

onMounted(fetchVideos)
</script>
<style scoped>
.btn{ margin-inline-start:.5rem; background:#2563eb; color:#fff; border:none; padding:.25rem .5rem; border-radius:.25rem }
ul{ padding:0; list-style:none }
li{ background:#fff; border:1px solid #e5e7eb; border-radius:.5rem; padding:.5rem .75rem; margin:.5rem 0 }
</style>
