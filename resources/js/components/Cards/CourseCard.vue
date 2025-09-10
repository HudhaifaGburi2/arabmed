<template>
  <!-- Simple Vuexy-like course card -->
  <div class="card">
    <img v-if="course.thumbnail_url" :src="course.thumbnail_url" alt="thumb" class="thumb" />
    <div class="body">
      <h3 class="title">{{ course.title_ar }} <small v-if="course.title_en">({{ course.title_en }})</small></h3>
      <p class="meta">Level: {{ course.level }} • Price: {{ course.is_free ? 'Free' : course.price+'$' }}</p>
      <button class="btn" @click="$emit('open')">Open</button>
    </div>
  </div>
</template>
<script setup>
defineProps({ course: { type: Object, required: true } })
</script>
<style scoped>
.card{background:white;border:1px solid #e5e7eb;border-radius:.5rem;overflow:hidden;display:flex}
.thumb{width:160px;height:120px;object-fit:cover}
.body{padding:.75rem}
.title{margin:0 0 .25rem}
.btn{background:#2563eb;color:white;border:none;padding:.25rem .5rem;border-radius:.25rem}
.meta{color:#6b7280; font-size:.875rem}
</style>
