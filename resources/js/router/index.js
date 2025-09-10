// Admin & Teacher Router (Vuexy-like)
// - Defines routes for Admin and Teacher dashboards
// - Protects routes via a simple auth/role guard using token in localStorage and user role in store
import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../store/auth'

// Views
import AdminDashboard from '../views/Admin/Dashboard.vue'
import Users from '../views/Admin/Users.vue'
import Reports from '../views/Admin/Reports.vue'

import TeacherDashboard from '../views/Teacher/Dashboard.vue'
import TeacherCourses from '../views/Teacher/Courses.vue'
import TeacherVideos from '../views/Teacher/Videos.vue'
import TeacherExams from '../views/Teacher/Exams.vue'
import TeacherStudents from '../views/Teacher/Students.vue'

import Login from '../views/Auth/Login.vue'
import Register from '../views/Auth/Register.vue'

const routes = [
  { path: '/login', name: 'login', component: Login, meta: { guest: true } },
  { path: '/register', name: 'register', component: Register, meta: { guest: true } },

  // Admin
  { path: '/admin', name: 'admin.dashboard', component: AdminDashboard, meta: { requiresAuth: true, roles: ['admin'] } },
  { path: '/admin/users', name: 'admin.users', component: Users, meta: { requiresAuth: true, roles: ['admin'] } },
  { path: '/admin/reports', name: 'admin.reports', component: Reports, meta: { requiresAuth: true, roles: ['admin'] } },

  // Teacher
  { path: '/teacher', name: 'teacher.dashboard', component: TeacherDashboard, meta: { requiresAuth: true, roles: ['teacher','admin'] } },
  { path: '/teacher/courses', name: 'teacher.courses', component: TeacherCourses, meta: { requiresAuth: true, roles: ['teacher','admin'] } },
  { path: '/teacher/videos', name: 'teacher.videos', component: TeacherVideos, meta: { requiresAuth: true, roles: ['teacher','admin'] } },
  { path: '/teacher/exams', name: 'teacher.exams', component: TeacherExams, meta: { requiresAuth: true, roles: ['teacher','admin'] } },
  { path: '/teacher/students', name: 'teacher.students', component: TeacherStudents, meta: { requiresAuth: true, roles: ['teacher','admin'] } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore()
  await auth.bootstrap()

  if (to.meta.guest && auth.isAuthenticated) {
    return next({ name: auth.role === 'admin' ? 'admin.dashboard' : 'teacher.dashboard' })
  }

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return next({ name: 'login' })
  }

  if (to.meta.roles && to.meta.roles.length) {
    if (!to.meta.roles.includes(auth.role)) {
      return next({ name: 'login' })
    }
  }

  next()
})

export default router
