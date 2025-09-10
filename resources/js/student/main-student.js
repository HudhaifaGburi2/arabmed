// Entry for Student SPA (Material Dashboard-inspired)
// - Mounts to #student-app (resources/views/student.blade.php)
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import StudentShell from './components/Layout/StudentShell.vue'

const app = createApp(StudentShell)
app.use(createPinia())
app.use(router)
app.mount('#student-app')
