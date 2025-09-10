<template>
  <header class="md-navbar">
    <div class="left">
      <span class="brand">Arab-Med</span>
    </div>
    <div class="right">
      <router-link to="/student/profile">الملف الشخصي</router-link>
    </div>
  </header>
</template>
<script setup>
</script>
<style scoped>
.md-navbar{display:flex;justify-content:space-between;align-items:center;padding:.75rem 1rem;background:#1f2937;color:#fff}
.brand{font-weight:600}
.right a{color:#fff}
</style>
