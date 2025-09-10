<template>
  <aside class="md-sidebar">
    <nav>
      <ul>
        <li><router-link to="/student">Dashboard</router-link></li>
        <li><router-link to="/student/courses">Courses</router-link></li>
        <li><router-link to="/student/videos">Videos</router-link></li>
        <li><router-link to="/student/exams">Exams</router-link></li>
        <li><router-link to="/student/profile">Profile</router-link></li>
      </ul>
    </nav>
  </aside>
</template>
<script setup>
</script>
<style scoped>
.md-sidebar{width:240px;background:#111827;color:#e5e7eb;padding:1rem}
.md-sidebar a{display:block;color:#e5e7eb;padding:.5rem .75rem;border-radius:.25rem}
.md-sidebar a.router-link-active{background:#374151}
ul{list-style:none;margin:0;padding:0}
li+li{margin-top:.25rem}
</style>
