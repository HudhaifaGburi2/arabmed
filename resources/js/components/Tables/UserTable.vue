<template>
  <!-- Generic users table with sorting/pagination hooks -->
  <div>
    <table class="tbl">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="u in items" :key="u.id">
          <td>{{ u.id }}</td>
          <td>{{ u.name }}</td>
          <td>{{ u.email }}</td>
          <td>{{ u.role }}</td>
        </tr>
      </tbody>
    </table>
    <div v-if="loading">Loading...</div>
    <button class="btn" @click="$emit('refresh')">Refresh</button>
  </div>
</template>
<script setup>
defineProps({ items: Array, loading: Boolean })
</script>
<style scoped>
.tbl{ width:100%; border-collapse: collapse; }
th,td{ border:1px solid #e5e7eb; padding:.5rem; text-align:start }
.btn{ margin-top:.5rem; background:#10b981; color:#fff; padding:.25rem .5rem; border-radius:.25rem }
</style>
