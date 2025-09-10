<template>
  <section class="p-4">
    <h1 class="text-2xl font-semibold mb-4">Reports</h1>

    <!-- KPI Cards -->
    <div class="grid grid-cols-12 gap-4">
      <div class="col-span-12 md:col-span-3"><div class="kpi"><div class="label">Users</div><div class="value">{{ stats.users.total }}</div></div></div>
      <div class="col-span-12 md:col-span-3"><div class="kpi"><div class="label">Courses</div><div class="value">{{ stats.courses.total }}</div></div></div>
      <div class="col-span-12 md:col-span-3"><div class="kpi"><div class="label">Videos</div><div class="value">{{ stats.videos.total }}</div></div></div>
      <div class="col-span-12 md:col-span-3"><div class="kpi"><div class="label">Exams</div><div class="value">{{ stats.exams.total }}</div></div></div>
    </div>

    <div class="flex items-center justify-between mt-4">
      <div></div>
      <button class="px-3 py-2 bg-emerald-600 text-white rounded" @click="downloadCsv">Download CSV</button>
    </div>

    <div class="grid grid-cols-12 gap-4 mt-2">
      <!-- Users by Role -->
      <div class="col-span-12 md:col-span-6 bg-white border border-gray-200 rounded p-4">
        <h3 class="font-medium mb-2">Users by Role</h3>
        <canvas ref="usersRoleChart" height="160"></canvas>
      </div>

      <!-- Published vs Total -->
      <div class="col-span-12 md:col-span-6 bg-white border border-gray-200 rounded p-4">
        <h3 class="font-medium mb-2">Published Content</h3>
        <canvas ref="publishedChart" height="160"></canvas>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import api from '../../services/api'
import { Chart, BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend, PieController, ArcElement } from 'chart.js'
Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend, PieController, ArcElement)

const stats = reactive({ users:{ total:0, by_role:{} }, courses:{ total:0, published:0 }, videos:{ total:0, published:0 }, exams:{ total:0, active:0 } })
const usersRoleChart = ref(null)
const publishedChart = ref(null)
let chart1, chart2

async function load(){
  const { data } = await api.get('/admin/stats')
  Object.assign(stats, data)

  // Users by role pie
  const labels1 = Object.keys(stats.users.by_role || {})
  const data1 = labels1.map(k => stats.users.by_role[k])
  if (chart1) chart1.destroy()
  chart1 = new Chart(usersRoleChart.value.getContext('2d'), {
    type: 'pie',
    data: { labels: labels1, datasets: [{ data: data1, backgroundColor:['#60a5fa','#34d399','#fbbf24','#f87171'] }] },
    options: { plugins:{ legend:{ position:'bottom' } } }
  })

  // Published vs total bar
  const labels2 = ['Courses','Videos']
  const totals = [stats.courses.total, stats.videos.total]
  const published = [stats.courses.published, stats.videos.published]
  if (chart2) chart2.destroy()
  chart2 = new Chart(publishedChart.value.getContext('2d'), {
    type: 'bar',
    data: { labels: labels2, datasets: [
      { label:'Total', data: totals, backgroundColor:'#cbd5e1' },
      { label:'Published', data: published, backgroundColor:'#22c55e' },
    ] },
    options: { responsive:true, scales:{ y:{ beginAtZero:true } } }
  })
}

onMounted(load)

function downloadCsv(){
  const rows = []
  rows.push(['Metric','Key','Value'])
  rows.push(['Users','Total', stats.users.total])
  for (const [role,count] of Object.entries(stats.users.by_role || {})) {
    rows.push(['UsersByRole', role, count])
  }
  rows.push(['Courses','Total', stats.courses.total])
  rows.push(['Courses','Published', stats.courses.published])
  rows.push(['Videos','Total', stats.videos.total])
  rows.push(['Videos','Published', stats.videos.published])
  rows.push(['Exams','Total', stats.exams.total])
  rows.push(['Exams','Active', stats.exams.active])

  const csv = rows.map(r => r.map(v => `"${String(v).replaceAll('"','""')}"`).join(',')).join('\n')
  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = 'arabmed-stats.csv'
  document.body.appendChild(a)
  a.click()
  document.body.removeChild(a)
  URL.revokeObjectURL(url)
}
</script>

<style scoped>
@reference "tailwindcss";
.kpi{ @apply bg-white border border-gray-200 rounded p-4 flex items-center justify-between; }
.kpi .label{ @apply text-gray-500; }
.kpi .value{ @apply text-2xl font-semibold; }
</style>
