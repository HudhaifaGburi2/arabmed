// Pinia Student Auth Store (reuses same backend endpoints)
import { defineStore } from 'pinia'
import api from '../../services/api'

export const useStudentAuthStore = defineStore('studentAuth', {
  state: () => ({
    token: localStorage.getItem('token') || null,
    user: null,
    isAuthenticated: !!localStorage.getItem('token'),
    bootstrapped: false,
  }),
  actions: {
    async bootstrap () {
      if (this.bootstrapped) return
      if (this.token) {
        try {
          const { data } = await api.get('/auth/me')
          this.user = data
          this.isAuthenticated = true
        } catch (e) {
          this.token = null
          localStorage.removeItem('token')
          this.isAuthenticated = false
        }
      }
      this.bootstrapped = true
    },
    async login (payload) {
      const { data } = await api.post('/auth/login', payload)
      this.token = data.token
      localStorage.setItem('token', this.token)
      this.user = data.user
      this.isAuthenticated = true
      return data
    },
    async logout () {
      try { await api.post('/auth/logout') } catch (e) {}
      this.token = null
      localStorage.removeItem('token')
      this.user = null
      this.isAuthenticated = false
    }
  }
})
