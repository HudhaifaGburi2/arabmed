<template>
  <!-- Admin/Teacher Shell using Vuexy-like layout -->
  <div class="vx-layout">
    <Navbar />
    <div class="vx-body">
      <Sidebar />
      <main class="vx-content">
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
.vx-layout { display:flex; flex-direction:column; min-height:100vh; }
.vx-body { display:flex; flex:1; }
.vx-content { flex:1; padding:1rem; background:#f8fafc; }
</style>
