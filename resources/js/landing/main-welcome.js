// Landing page entry: mounts a Vue app on #welcome-app
import { createApp } from 'vue'
import LandingApp from './components/LandingApp.vue'

// Import Chart.js for demo charts
import { Chart, registerables } from 'chart.js'
Chart.register(...registerables)

createApp(LandingApp).mount('#welcome-app')
