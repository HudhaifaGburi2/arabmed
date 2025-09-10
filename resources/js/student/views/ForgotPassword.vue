<template>
  <section class="auth">
    <div class="card">
      <div class="header">
        <h1>Forgot password</h1>
        <p class="subtitle">Enter your email to receive a reset link.</p>
      </div>
      <form @submit.prevent="submit" class="form">
        <label>Email</label>
        <input v-model="email" type="email" required class="input" placeholder="you@example.com" />
        <button class="btn" :disabled="loading">Send reset link</button>
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
import { ref } from 'vue'
import api from '../../services/api'

const email = ref('')
const loading = ref(false)
const error = ref('')
const message = ref('')

async function submit(){
  loading.value = true
  error.value = ''
  message.value = ''
  try {
    const { data } = await api.post('/auth/forgot-password', { email: email.value })
    message.value = data.message || 'Reset link sent if email exists.'
  } catch (e) {
    error.value = e.response?.data?.message || 'Failed to send reset link'
  } finally {
    loading.value = false
  }
}
</script>
<style scoped>
@import './Login.css';
/* Reuse login styles via import if extracted; otherwise mirror them: */
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
