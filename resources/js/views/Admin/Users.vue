<template>
  <section>
    <h1>Users</h1>
    <UserTable :items="items" :loading="loading" @refresh="fetch" />
  </section>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import api from '../../services/api'
import UserTable from '../../components/Tables/UserTable.vue'

const items = ref([])
const loading = ref(false)

async function fetch () {
  loading.value = true
  try {
    // Example placeholder: implement a real admin users endpoint
    const courses = await api.get('/courses')
    items.value = (courses.data?.data || []).map((c, i) => ({ id: i+1, name: c.instructor?.first_name+' '+c.instructor?.last_name, email: 'n/a', role: 'teacher' }))
  } finally {
    loading.value = false
  }
}

onMounted(fetch)
</script>
