// Student Router (Material Dashboard-like)
// - Defines routes for Student dashboard
// - Uses a separate student auth store
import { createRouter, createWebHistory } from 'vue-router'
import { useStudentAuthStore } from '../store/auth'

import Dashboard from '../views/Dashboard.vue'
import Courses from '../views/Courses.vue'
import Videos from '../views/Videos.vue'
import Exams from '../views/Exams.vue'
import Profile from '../views/Profile.vue'

const routes = [
  { path: '/student', name: 'student.dashboard', component: Dashboard, meta: { requiresAuth: true } },
  { path: '/student/courses', name: 'student.courses', component: Courses, meta: { requiresAuth: true } },
  { path: '/student/videos', name: 'student.videos', component: Videos, meta: { requiresAuth: true } },
  { path: '/student/exams', name: 'student.exams', component: Exams, meta: { requiresAuth: true } },
  { path: '/student/profile', name: 'student.profile', component: Profile, meta: { requiresAuth: true } },
]

const router = createRouter({ history: createWebHistory(), routes })

router.beforeEach(async (to, from, next) => {
  const auth = useStudentAuthStore()
  await auth.bootstrap()
  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    // Student app assumes token exists (login via admin/teacher auth or dedicated student login page if added)
    return next('/admin/login')
  }
  next()
})

export default router
