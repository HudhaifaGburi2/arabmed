<template>
  <section>
    <h1>Student Dashboard</h1>

    <!-- KPI Cards -->
    <div class="grid">
      <div class="card kpi"><div><div class="label">My Enrollments</div><div class="value">{{ stats.enrollments }}</div></div></div>
      <div class="card kpi"><div><div class="label">Videos Progress</div><div class="value">{{ stats.videosCompleted }} / {{ stats.videosTotal }}</div></div></div>
      <div class="card kpi"><div><div class="label">Exams Taken</div><div class="value">{{ stats.examsTaken }}</div></div></div>
    </div>

    <!-- Progress Bars -->
    <div class="card" style="margin-top:1rem">
      <h3 style="margin-top:0">Overall Progress</h3>
      <div class="prog">
        <div class="prog-label">Videos</div>
        <div class="bar"><div class="fill" :style="{ width: videosPct + '%' }"></div></div>
        <div class="pct">{{ videosPct }}%</div>
      </div>
      <div class="prog">
        <div class="prog-label">Exams</div>
        <div class="bar"><div class="fill green" :style="{ width: examsPct + '%' }"></div></div>
        <div class="pct">{{ examsPct }}%</div>
      </div>
    </div>

    <!-- Mini chart -->
    <div class="card" style="margin-top:1rem">
      <h3 style="margin-top:0">Weekly Study Minutes (sample)</h3>
      <canvas ref="miniChart" height="100"></canvas>
    </div>
  </section>
</template>
<script setup>
import { onMounted, ref, computed } from 'vue'
import api from '../../services/api'
import { useStudentAuthStore } from '../store/auth'
import { Chart, LineController, LineElement, PointElement, CategoryScale, LinearScale, Tooltip, Legend } from 'chart.js'
Chart.register(LineController, LineElement, PointElement, CategoryScale, LinearScale, Tooltip, Legend)

const auth = useStudentAuthStore()
const stats = ref({ enrollments: 0, videosCompleted: 0, videosTotal: 0, examsTaken: 0, examsTotal: 0 })

const videosPct = computed(() => stats.value.videosTotal ? Math.round(100 * stats.value.videosCompleted / stats.value.videosTotal) : 0)
const examsPct = computed(() => stats.value.examsTotal ? Math.round(100 * stats.value.examsTaken / stats.value.examsTotal) : 0)

const miniChart = ref(null)
let chartInst

onMounted(async () => {
  await auth.bootstrap()
  if (auth.user?.id) {
    // Videos progress
    const vp = await api.get(`/users/${auth.user.id}/progress/videos`)
    const list = vp.data?.data || []
    stats.value.videosTotal = list.length
    stats.value.videosCompleted = list.filter(v => (v.watched_seconds ?? 0) >= (v.total_seconds ?? 1)).length
  }

  // Demo: assume exams attempted out of total 5
  stats.value.examsTotal = 5
  stats.value.examsTaken = Math.min(5, Math.round(stats.value.videosCompleted / 2))

  // Demo line chart data (study minutes per day)
  const labels = ['Sat','Sun','Mon','Tue','Wed','Thu','Fri']
  const data = labels.map(() => Math.round(20 + Math.random() * 40))
  if (chartInst) chartInst.destroy()
  chartInst = new Chart(miniChart.value.getContext('2d'), {
    type: 'line',
    data: { labels, datasets: [{ label:'Minutes', data, borderColor:'#2563eb', backgroundColor:'rgba(37,99,235,.2)', tension:.3, fill:true }] },
    options: { plugins: { legend: { display:false } }, scales: { x: { ticks:{ color:'#6b7280' } }, y: { ticks:{ color:'#6b7280' } } } }
  })
})
</script>
<style scoped>
.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem}
.card{background:#fff;padding:1rem;border:1px solid #e5e7eb;border-radius:.5rem}
.kpi .label{color:#6b7280}
.kpi .value{font-size:1.5rem;font-weight:700}
.prog{display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:.5rem;margin:.5rem 0}
.bar{height:10px;background:#f3f4f6;border-radius:9999px;overflow:hidden}
.fill{height:100%;background:#2563eb}
.fill.green{background:#10b981}
.pct{font-weight:600;color:#374151}
@media(max-width:900px){ .grid{grid-template-columns:1fr} }
</style>
