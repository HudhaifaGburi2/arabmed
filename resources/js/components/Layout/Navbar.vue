<template>
  <!-- Vuexy-like top navbar -->
  <header class="vx-navbar">
    <div class="left">
      <button @click="$emit('toggle-sidebar')">☰</button>
      <span class="brand">Arab-Med Admin</span>
    </div>
    <div class="right">
      <span v-if="user">{{ user.first_name }} {{ user.last_name }}</span>
      <button @click="logout" class="btn">Logout</button>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'
import { useAuthStore } from '../../store/auth'

const auth = useAuthStore()
const user = computed(() => auth.user)

async function logout () {
  await auth.logout()
}
</script>

<style scoped>
.vx-navbar { display:flex; justify-content:space-between; align-items:center; padding:.75rem 1rem; background:#1f2937; color:white; }
.btn { background:#ef4444; color:white; padding:.25rem .5rem; border-radius:.25rem; }
.brand { font-weight:600; margin-left:.5rem; }
</style>
