// Landing page entry: mounts a Vue app on #welcome-app
import { createApp } from 'vue'
import LandingApp from './components/LandingApp.vue'

createApp(LandingApp).mount('#welcome-app')
