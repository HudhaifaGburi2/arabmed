<template>
  <section>
    <h1>Users</h1>
    <div class="actions">
      <input class="input" v-model="q" placeholder="Search name/email..." @keyup.enter="fetch" />
      <button class="btn" @click="fetch">Search</button>
    </div>
    <div class="tbl-wrap">
      <table class="tbl">
        <thead>
          <tr>
            <th @click="toggleSort('id')">#</th>
            <th @click="toggleSort('first_name')">Name</th>
            <th @click="toggleSort('email')">Email</th>
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
    </div>
    <div class="pager" v-if="meta.total">
      <span>Page {{ meta.current_page }} / {{ meta.last_page }} ({{ meta.total }} users)</span>
      <div>
        <button class="btn" @click="prev" :disabled="meta.current_page<=1">Prev</button>
        <button class="btn" @click="next" :disabled="meta.current_page>=meta.last_page">Next</button>
      </div>
    </div>
  </section>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import api from '../../services/api'
import UserTable from '../../components/Tables/UserTable.vue'

const items = ref([])
const loading = ref(false)
const q = ref('')
const page = ref(1)
const meta = ref({ total:0, current_page:1, last_page:1 })
const sortBy = ref('id')
const sortDir = ref('desc')

async function fetch () {
  loading.value = true
  try {
    const { data } = await api.get('/admin/users', { params: { per_page: 10, page: page.value, q: q.value, sort_by: sortBy.value, sort_dir: sortDir.value } })
    items.value = (data?.data || []).map(u => ({
      id: u.id,
      name: `${u.first_name ?? ''} ${u.last_name ?? ''}`.trim() || u.email,
      email: u.email,
      role: (u.roles || []).map(r=>r.name).join(', ')
    }))
    meta.value = { total: data?.total || 0, current_page: data?.current_page || 1, last_page: data?.last_page || 1 }
  } finally {
    loading.value = false
  }
}

onMounted(fetch)

function next(){ if (meta.value.current_page < meta.value.last_page) { page.value++; fetch() } }
function prev(){ if (meta.value.current_page > 1) { page.value--; fetch() } }
function toggleSort(col){
  if (sortBy.value === col) {
    sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortBy.value = col; sortDir.value = 'asc'
  }
  fetch()
}
</script>

<style scoped>
.actions{ margin:.5rem 0; display:flex; gap:.5rem; align-items:center }
.pager{ margin-top:.5rem; display:flex; justify-content:space-between; align-items:center }
.btn{ background:#2563eb; color:#fff; border:none; padding:.25rem .5rem; border-radius:.25rem }
.input{ padding:.5rem; border:1px solid #d1d5db; border-radius:.25rem }
.tbl{ width:100%; border-collapse: collapse; }
th,td{ border:1px solid #e5e7eb; padding:.5rem; text-align:start }
th{ cursor:pointer }
</style>
