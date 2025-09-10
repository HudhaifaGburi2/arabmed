// Entry point for Admin & Teacher SPA (Vuexy-inspired)
// - Sets up Vue app, Pinia store, and router
// - Mounts to #admin-app (resources/views/admin.blade.php)
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import AdminShell from './components/Layout/AdminShell.vue'

const app = createApp(AdminShell)
app.use(createPinia())
app.use(router)
app.mount('#admin-app')
