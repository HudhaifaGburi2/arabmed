<template>
  <div>
    <!-- Hero Section -->
    <section class="hero">
      <div class="container">
        <h1>Arab‑Med — المنصة العربية لعلوم الطب</h1>
        <p>دورات، فيديوهات، ووثائق طبية باللغة العربية مع اختبارات وتتبّع للتقدم.</p>
        <div style="margin-top:1rem">
          <a href="/student" class="btn">الانتقال إلى واجهة الطالب</a>
          <a href="/admin" class="btn" style="background:#10b981">لوحة الإدارة / المعلم</a>
        </div>
      </div>
    </section>

    <!-- KPIs -->
    <section class="container" style="margin-top:1rem">
      <div class="grid">
        <div class="col-span-12 md:col-span-4"><KpiCard :label="'الدورات'" :value="stats.courses" /></div>
        <div class="col-span-12 md:col-span-4"><KpiCard :label="'الفيديوهات'" :value="stats.videos" /></div>
        <div class="col-span-12 md:col-span-4"><KpiCard :label="'الاختبارات'" :value="stats.exams" /></div>
      </div>
    </section>

    <!-- Courses Table + Filters -->
    <section class="container" style="margin-top:1rem">
      <div class="card">
        <div style="display:flex;gap:.5rem;align-items:center;justify-content:space-between;flex-wrap:wrap">
          <h2 style="margin:0">الدورات المتاحة</h2>
          <div style="display:flex;gap:.5rem;align-items:center">
            <input class="input" placeholder="بحث بالعنوان" v-model="filters.q" @input="applyFilters" />
            <select class="input" v-model="filters.level" @change="applyFilters">
              <option value="">جميع المستويات</option>
              <option value="beginner">Beginner</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
            </select>
            <button class="btn" @click="openCreateCourse">+ دورة جديدة</button>
          </div>
        </div>

        <div style="overflow:auto;margin-top:.5rem">
          <table class="tbl">
            <thead>
              <tr>
                <th @click="sortBy('id')">#</th>
                <th @click="sortBy('title_ar')">العنوان</th>
                <th>الفئة</th>
                <th @click="sortBy('level')">المستوى</th>
                <th>السعر</th>
                <th>نشر</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="c in paginated" :key="c.id">
                <td>{{ c.id }}</td>
                <td>{{ c.title_ar }}</td>
                <td>{{ c.category?.name_ar }}</td>
                <td>{{ c.level }}</td>
                <td>{{ c.is_free ? 'مجاني' : (c.price+'$') }}</td>
                <td>{{ c.is_published ? '✓' : '—' }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:.5rem">
          <div>صفحة {{ page }} / {{ totalPages }}</div>
          <div>
            <button class="btn" @click="prev" :disabled="page===1">السابق</button>
            <button class="btn" @click="next" :disabled="page===totalPages">التالي</button>
          </div>
        </div>
      </div>
    </section>

    <!-- Chart Section -->
    <section class="container" style="margin-top:1rem">
      <div class="card">
        <h2 style="margin:0 0 .5rem">معدلات التقدم في الدورات (تجريبي)</h2>
        <canvas ref="chartEl" height="120"></canvas>
      </div>
    </section>

    <!-- Create/Edit Course Modal -->
    <div v-if="showCourse" class="modal">
      <div class="modal-body">
        <h3 style="margin-top:0">{{ courseForm.id ? 'تعديل دورة' : 'دورة جديدة' }}</h3>
        <CourseForm :modelValue="courseForm" @update:modelValue="v=>Object.assign(courseForm,v)" @submit="saveCourse" @cancel="()=>showCourse=false" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, reactive, computed, watch } from 'vue'
import KpiCard from './KpiCard.vue'
import api from '../../services/api'
import CourseForm from '../../components/Forms/CourseForm.vue'
import { Chart, BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from 'chart.js'
Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend)

const stats = reactive({ courses: 0, videos: 0, exams: 0 })
const items = ref([])
const filtered = ref([])
const sort = reactive({ by:'id', dir:'asc' })
const page = ref(1)
const perPage = ref(8)
const totalPages = computed(()=> Math.max(1, Math.ceil(filtered.value.length / perPage.value)))
const paginated = computed(()=> filtered.value.slice((page.value-1)*perPage.value, page.value*perPage.value))

const filters = reactive({ q:'', level:'' })

const showCourse = ref(false)
const courseForm = reactive({ id:null, title_ar:'', title_en:'', category_id:null, level:'beginner', is_free:false, is_published:false })

const chartEl = ref(null)
let chartInst = null

function sortBy(key){
  if (sort.by===key) sort.dir = sort.dir==='asc'?'desc':'asc'; else { sort.by=key; sort.dir='asc' }
  applyFilters()
}
function applyFilters(){
  let data=[...items.value]
  if (filters.q) data = data.filter(c => (c.title_ar||'').includes(filters.q) || (c.title_en||'').toLowerCase().includes(filters.q.toLowerCase()))
  if (filters.level) data = data.filter(c => (c.level||'')===filters.level)
  data.sort((a,b)=>{
    const A=a[sort.by]; const B=b[sort.by]
    if (A==null && B!=null) return sort.dir==='asc'?1:-1
    if (A!=null && B==null) return sort.dir==='asc'?-1:1
    if (A==null && B==null) return 0
    return sort.dir==='asc' ? (A>B?1:A<B?-1:0) : (A<B?1:A>B?-1:0)
  })
  filtered.value = data
  page.value = 1
}

function next(){ if (page.value<totalPages.value) page.value++ }
function prev(){ if (page.value>1) page.value-- }

function openCreateCourse(){ Object.assign(courseForm, { id:null, title_ar:'', title_en:'', category_id:null, level:'beginner', is_free:false, is_published:false }); showCourse.value=true }
async function saveCourse(payload){
  // If editing: PUT, else POST
  if (payload.id) await api.put(`/courses/${payload.id}`, payload); else await api.post('/courses', payload)
  showCourse.value=false
  await load()
}

async function load(){
  const courses = await api.get('/courses?published=false')
  items.value = courses.data?.data || []
  stats.courses = courses.data?.meta?.total ?? items.value.length
  // demo-only stats
  stats.videos = Math.round(stats.courses * 5)
  stats.exams = Math.round(stats.courses * 2)
  applyFilters()

  // simple demo chart: course progress distribution
  const labels = items.value.slice(0,6).map(c=>c.title_ar?.slice(0,8) || `دورة ${c.id}`)
  const data = items.value.slice(0,6).map(()=> Math.round(Math.random()*100))
  if (chartInst) chartInst.destroy()
  chartInst = new Chart(chartEl.value.getContext('2d'), {
    type: 'bar',
    data: { labels, datasets: [{ label:'إكمال المساق %', data, backgroundColor:'#22d3ee' }] },
    options: { plugins: { legend: { labels: { color:'#e5e7eb' } } }, scales:{ x:{ ticks:{ color:'#9ca3af' } }, y:{ ticks:{ color:'#9ca3af' } } } }
  })
}

onMounted(load)
watch(()=>[filters.q, filters.level, sort.by, sort.dir], applyFilters)
</script>

<style scoped>
/* small css helpers for grid columns */
.col-span-12{grid-column:span 12}
.md\:col-span-4{grid-column:span 12}
@media(min-width:768px){ .md\:col-span-4{grid-column:span 4} }
.input{ background:#0b1220; color:#e5e7eb; border:1px solid #1f2937; padding:.5rem .75rem; border-radius:.375rem }
.tbl{ width:100%; border-collapse: collapse; }
th,td{ border-bottom:1px solid #1f2937; padding:.5rem .75rem; text-align:start }
th{ cursor:pointer; color:#9ca3af }
.modal{ position:fixed; inset:0; background:rgba(0,0,0,.5); display:flex; align-items:center; justify-content:center }
.modal-body{ background:#0b1220; border:1px solid #1f2937; border-radius:.75rem; padding:1rem; min-width:360px; width:min(680px,90vw) }
</style>
