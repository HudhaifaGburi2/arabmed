<template>
  <section>
    <h1>الملف الشخصي</h1>
    <form class="card" @submit.prevent="save">
      <label>الاسم الأول <input v-model="form.first_name" required /></label>
      <label>اسم العائلة <input v-model="form.last_name" required /></label>
      <label>الهاتف <input v-model="form.phone" /></label>
      <label>الصورة (URL) <input v-model="form.avatar_url" /></label>
      <div class="actions">
        <button class="btn" :disabled="loading">حفظ</button>
      </div>
    </form>
  </section>
</template>
<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useStudentAuthStore } from '../store/auth'
import api from '../../services/api'

const auth = useStudentAuthStore()
const form = reactive({ first_name:'', last_name:'', phone:'', avatar_url:'' })
const loading = ref(false)

onMounted(async () => {
  await auth.bootstrap()
  if (auth.user) {
    Object.assign(form, auth.user)
  }
})

async function save(){
  loading.value = true
  try {
    await api.put('/auth/profile', form)
    alert('تم الحفظ')
  } finally {
    loading.value = false
  }
}
</script>
<style scoped>
.card{background:#fff;padding:1rem;border:1px solid #e5e7eb;border-radius:.5rem;max-width:420px}
label{display:block;margin:.5rem 0}
input{width:100%;padding:.5rem;border:1px solid #d1d5db;border-radius:.25rem}
.actions{margin-top:.5rem}
.btn{background:#10b981;color:#fff;border:none;padding:.5rem .75rem;border-radius:.25rem}
</style>
