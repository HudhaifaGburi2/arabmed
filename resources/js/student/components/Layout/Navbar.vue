<template>
  <header class="md-navbar">
    <div class="left">
      <span class="brand">Arab-Med</span>
    </div>
    <div class="right">
      <router-link to="/student/profile">الملف الشخصي</router-link>
      <button class="logout" @click="onLogout">تسجيل الخروج</button>
    </div>
  </header>
</template>
<script setup>
import { useRouter } from 'vue-router'
import { useStudentAuthStore } from '../../store/auth'

const auth = useStudentAuthStore()
const router = useRouter()

async function onLogout(){
  await auth.logout()
  router.push({ name: 'student.login' })
}
</script>
<style scoped>
.md-navbar{display:flex;justify-content:space-between;align-items:center;padding:.75rem 1rem;background:#1f2937;color:#fff}
.brand{font-weight:600}
.right a{color:#fff}
.logout{ margin-inline-start:.5rem; background:#ef4444; color:#fff; border:none; padding:.35rem .6rem; border-radius:.375rem }
</style>
