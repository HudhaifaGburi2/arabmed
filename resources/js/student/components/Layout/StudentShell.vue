<template>
  <!-- Material Dashboard-like shell for Students -->
  <div class="md-shell">
    <Navbar />
    <div class="md-body">
      <Sidebar />
      <main class="md-content">
        <router-view />
      </main>
    </div>
    <Footer />
  </div>
</template>
<script setup>
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'
import Footer from './Footer.vue'
</script>
<style scoped>
.md-body{display:flex}
.md-content{flex:1;padding:1rem;background:#f8fafc}
</style>
