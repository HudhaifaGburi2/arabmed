<template>
  <section class="auth">
    <h1>Login</h1>
    <form @submit.prevent="submit">
      <label>Email <input v-model="form.email" type="email" required /></label>
      <label>Password <input v-model="form.password" type="password" required /></label>
      <button class="btn" :disabled="loading">Login</button>
    </form>
    <p v-if="error" class="error">{{ error }}</p>
  </section>
</template>
<script setup>
import { reactive, ref } from 'vue'
import { useAuthStore } from '../../store/auth'
import { useRouter } from 'vue-router'

const router = useRouter()
const auth = useAuthStore()
const form = reactive({ email: '', password: '' })
const loading = ref(false)
const error = ref('')

async function submit () {
  loading.value = true
  error.value = ''
  try {
    await auth.login(form)
    router.push(auth.role === 'admin' ? { name: 'admin.dashboard' } : { name: 'teacher.dashboard' })
  } catch (e) {
    error.value = 'Invalid credentials'
  } finally {
    loading.value = false
  }
}
</script>
<style scoped>
.auth{ max-width:360px; margin:3rem auto; background:#fff; padding:1rem; border:1px solid #e5e7eb; border-radius:.5rem }
label{ display:block; margin-bottom:.5rem }
input{ width:100%; padding:.5rem; border:1px solid #d1d5db; border-radius:.25rem }
.btn{ margin-top:.5rem; background:#2563eb; color:#fff; border:none; padding:.5rem .75rem; border-radius:.25rem }
.error{ color:#b91c1c; margin-top:.5rem }
</style>
