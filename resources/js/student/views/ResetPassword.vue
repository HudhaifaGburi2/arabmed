<template>
  <section class="auth">
    <div class="card">
      <div class="header">
        <h1>Reset password</h1>
        <p class="subtitle">Enter your new password.</p>
      </div>
      <form @submit.prevent="submit" class="form">
        <label>Email</label>
        <input v-model="form.email" type="email" required class="input" placeholder="you@example.com" />
        <label>Token</label>
        <input v-model="form.token" required class="input" placeholder="Paste token from email" />
        <label>New password</label>
        <input v-model="form.password" type="password" required class="input" placeholder="••••••••" />
        <label>Confirm password</label>
        <input v-model="form.password_confirmation" type="password" required class="input" placeholder="••••••••" />
        <button class="btn" :disabled="loading">Reset</button>
        <p v-if="message" class="ok">{{ message }}</p>
        <p v-if="error" class="error">{{ error }}</p>
      </form>
      <div class="center">
        <router-link to="/student/login" class="link">Back to login</router-link>
      </div>
    </div>
  </section>
</template>
<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../services/api'

const route = useRoute()
const form = reactive({ email:'', token:'', password:'', password_confirmation:'' })
const loading = ref(false)
const error = ref('')
const message = ref('')

onMounted(() => {
  form.email = route.query.email || ''
  form.token = route.query.token || ''
})

async function submit(){
  loading.value = true
  error.value = ''
  message.value = ''
  try {
    const { data } = await api.post('/auth/reset-password', form)
    message.value = data.message || 'Password reset successfully. You may login now.'
  } catch (e) {
    error.value = e.response?.data?.message || 'Failed to reset password'
  } finally {
    loading.value = false
  }
}
</script>
<style scoped>
.auth{ min-height:70vh; display:flex; align-items:center; justify-content:center; padding:1rem }
.card{ width:100%; max-width:420px; background:#ffffff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -2px rgba(0,0,0,.05); padding:1.25rem }
.header{ text-align:center; margin-bottom:1rem }
.subtitle{ color:#6b7280; margin-top:.25rem }
.input{ width:100%; padding:.625rem .75rem; border:1px solid #d1d5db; border-radius:.5rem; outline:none }
.btn{ width:100%; margin-top:.75rem; background:#2563eb; color:#fff; border:none; padding:.6rem .75rem; border-radius:.5rem; font-weight:600 }
.link{ background:none; border:none; color:#2563eb; cursor:pointer }
.error{ color:#b91c1c; margin-top:.5rem; text-align:center }
.ok{ color:#065f46; margin-top:.5rem; text-align:center }
.center{ text-align:center; margin-top:.5rem }
</style>
