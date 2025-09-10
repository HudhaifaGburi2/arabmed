// Pinia Auth Store
// - Manages Sanctum token, current user, and role
// - Persists token in localStorage
import { defineStore } from 'pinia'
import api from '../services/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token') || null,
    user: null,
    role: null,
    bootstrapped: false,
  }),
  getters: {
    isAuthenticated: (s) => !!s.token,
  },
  actions: {
    async bootstrap () {
      if (this.bootstrapped) return
      if (this.token) {
        try {
          const { data } = await api.get('/auth/me')
          this.user = data
          this.role = Array.isArray(data.roles) && data.roles.length ? data.roles[0] : 'student'
        } catch (e) {
          this.token = null
          localStorage.removeItem('token')
        }
      }
      this.bootstrapped = true
    },
    async login (payload) {
      const { data } = await api.post('/auth/login', payload)
      this.token = data.token
      localStorage.setItem('token', this.token)
      this.user = data.user
      this.role = Array.isArray(data.user.roles) && data.user.roles.length ? data.user.roles[0] : 'student'
      return data
    },
    async register (payload) {
      const { data } = await api.post('/auth/register', payload)
      this.token = data.token
      localStorage.setItem('token', this.token)
      this.user = data.user
      this.role = Array.isArray(data.user.roles) && data.user.roles.length ? data.user.roles[0] : 'student'
      return data
    },
    async logout () {
      try { await api.post('/auth/logout') } catch (e) {}
      this.token = null
      localStorage.removeItem('token')
      this.user = null
      this.role = null
      window.location.href = '/admin' // redirect to entry; router guard will send to login
    }
  }
})
