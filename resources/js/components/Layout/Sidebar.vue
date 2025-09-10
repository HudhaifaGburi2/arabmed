<template>
  <!-- Vuexy-like sidebar with role-based menu -->
  <aside class="vx-sidebar">
    <nav>
      <ul v-if="role==='admin'">
        <li><router-link to="/admin">Dashboard</router-link></li>
        <li><router-link to="/admin/users">Users</router-link></li>
        <li><router-link to="/admin/reports">Reports</router-link></li>
        <li><router-link to="/teacher/courses">Courses</router-link></li>
        <li><router-link to="/teacher/videos">Videos</router-link></li>
        <li><router-link to="/teacher/exams">Exams</router-link></li>
      </ul>
      <ul v-else>
        <li><router-link to="/teacher">Dashboard</router-link></li>
        <li><router-link to="/teacher/courses">Courses</router-link></li>
        <li><router-link to="/teacher/videos">Videos</router-link></li>
        <li><router-link to="/teacher/exams">Exams</router-link></li>
        <li><router-link to="/teacher/students">Students</router-link></li>
      </ul>
    </nav>
  </aside>
</template>

<script setup>
import { computed } from 'vue'
import { useAuthStore } from '../../store/auth'
const auth = useAuthStore()
const role = computed(()=>auth.role)
</script>

<style scoped>
.vx-sidebar { width: 240px; background:#111827; color:#e5e7eb; padding:1rem; }
.vx-sidebar a { display:block; color:#e5e7eb; padding:.5rem .75rem; border-radius:.25rem; }
.vx-sidebar a.router-link-active { background:#374151; }
ul { list-style:none; margin:0; padding:0; }
li+li{ margin-top:.25rem; }
</style>
