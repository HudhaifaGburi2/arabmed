<template>
  <section class="auth">
    <div class="card">
      <div class="header">
        <h1>Student Login</h1>
        <p class="subtitle">Welcome back. Please sign in to continue.</p>
      </div>
      <form @submit.prevent="submit" class="form">
        <div class="field">
          <label>Email</label>
          <input v-model="form.email" type="email" required class="input" placeholder="you@example.com" />
        </div>
        <div class="field">
          <div class="row">
            <label>Password</label>
            <router-link class="link" to="/student/forgot">Forgot password?</router-link>
          </div>
          <input v-model="form.password" type="password" required class="input" placeholder="••••••••" />
        </div>
        <button class="btn" :disabled="loading">Sign in</button>
        <p v-if="error" class="error">{{ error }}</p>
      </form>

      <div class="divider"><span>OR</span></div>

      <div class="social">
        <button class="btn-outlined" @click="social('google')">
          <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="g" />
          Continue with Google
        </button>
        <button class="btn-outlined" @click="social('facebook')">
          <img src="https://www.svgrepo.com/show/452196/facebook-1.svg" alt="fb" />
          Continue with Facebook
        </button>
      </div>
    </div>
  </section>
</template>
<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useStudentAuthStore } from '../store/auth'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const auth = useStudentAuthStore()
const form = reactive({ email: '', password: '' })
const loading = ref(false)
const error = ref('')

async function submit () {
  loading.value = true
  error.value = ''
  try {
    await auth.login(form)
    const redirect = route.query.redirect || { name: 'student.dashboard' }
    router.push(redirect)
  } catch (e) {
    error.value = 'Invalid credentials'
  } finally {
    loading.value = false
  }
}

function social(provider){
  // Redirect to server-side Socialite flow
  window.location.href = `/auth/${provider}/redirect`
}

onMounted(async () => {
  // If the backend redirected back with a token, consume it
  const tok = route.query.token
  if (tok) {
    auth.token = tok
    localStorage.setItem('token', tok)
    await auth.bootstrap()
    router.replace({ name: 'student.dashboard' })
  }
})
</script>
<style scoped>
.auth{ min-height:70vh; display:flex; align-items:center; justify-content:center; padding:1rem }
.card{ width:100%; max-width:420px; background:#ffffff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -2px rgba(0,0,0,.05); padding:1.25rem }
.header{ text-align:center; margin-bottom:1rem }
.subtitle{ color:#6b7280; margin-top:.25rem }
.field{ margin:.5rem 0 }
.row{ display:flex; justify-content:space-between; align-items:center }
.input{ width:100%; padding:.625rem .75rem; border:1px solid #d1d5db; border-radius:.5rem; outline:none }
.input:focus{ border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.15) }
.btn{ width:100%; margin-top:.75rem; background:#2563eb; color:#fff; border:none; padding:.6rem .75rem; border-radius:.5rem; font-weight:600 }
.btn:disabled{ opacity:.7 }
.link{ background:none; border:none; color:#2563eb; cursor:pointer }
.error{ color:#b91c1c; margin-top:.5rem; text-align:center }
.divider{ display:flex; align-items:center; gap:.5rem; color:#9ca3af; margin:1rem 0 }
.divider::before,.divider::after{ content:""; flex:1; height:1px; background:#e5e7eb }
.social{ display:grid; gap:.5rem }
.btn-outlined{ display:flex; align-items:center; gap:.5rem; justify-content:center; width:100%; border:1px solid #d1d5db; border-radius:.5rem; padding:.5rem .75rem; background:#fff }
.btn-outlined img{ width:18px; height:18px }
</style>
