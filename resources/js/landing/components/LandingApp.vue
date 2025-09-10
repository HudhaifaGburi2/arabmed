<template>
  <div class="landing-app">
    <!-- Navigation -->
    <nav class="nav">
      <div class="container">
        <div class="nav-content">
          <a href="/" class="logo">
            <span class="material-icons">local_hospital</span>
            منصة علوم الطب العربي
          </a>
          <div class="nav-links">
            <a href="/admin" class="material-btn outline">لوحة الإدارة</a>
            <a href="/student/login" class="material-btn">دخول الطلاب</a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
      <div class="container">
        <div class="hero-content">
          <h1>منصة علوم الطب العربي</h1>
          <p>منصة تعليمية متقدمة لتدريس العلوم الطبية باللغة العربية مع أحدث التقنيات والأساليب التفاعلية</p>
          <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <button @click="openCourseModal" class="material-btn">
              <span class="material-icons">add</span>
              إضافة دورة جديدة
            </button>
            <a href="/student" class="material-btn secondary">
              <span class="material-icons">school</span>
              بوابة الطلاب
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- KPI Section -->
    <section class="section" style="background: var(--surface-variant);">
      <div class="container">
        <div class="section-header">
          <h2>إحصائيات المنصة</h2>
          <p>نظرة شاملة على أداء وإحصائيات المنصة التعليمية</p>
        </div>
        <div class="grid grid-cols-4">
          <div class="material-card kpi-card">
            <div class="kpi-content">
              <h3>الطلاب المسجلون</h3>
              <div class="value">{{ stats.students }}</div>
            </div>
            <div class="kpi-icon students">
              <span class="material-icons">people</span>
            </div>
          </div>
          <div class="material-card kpi-card">
            <div class="kpi-content">
              <h3>الدورات المتاحة</h3>
              <div class="value">{{ stats.courses }}</div>
            </div>
            <div class="kpi-icon courses">
              <span class="material-icons">menu_book</span>
            </div>
          </div>
          <div class="material-card kpi-card">
            <div class="kpi-content">
              <h3>مقاطع الفيديو</h3>
              <div class="value">{{ stats.videos }}</div>
            </div>
            <div class="kpi-icon videos">
              <span class="material-icons">play_circle</span>
            </div>
          </div>
          <div class="material-card kpi-card">
            <div class="kpi-content">
              <h3>الامتحانات</h3>
              <div class="value">{{ stats.exams }}</div>
            </div>
            <div class="kpi-icon exams">
              <span class="material-icons">quiz</span>
            </div>
          </div>
        </div>
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
          <template>
            <div class="landing-app">
              <!-- Navigation -->
              <nav class="nav">
                <div class="container">
                  <div class="nav-content">
                    <a href="/" class="logo">
                      <span class="material-icons">local_hospital</span>
                      منصة علوم الطب العربي
                    </a>
                    <div class="nav-links">
                      <a href="/admin" class="material-btn outline">لوحة الإدارة</a>
                      <a href="/student/login" class="material-btn">دخول الطلاب</a>
                    </div>
                  </div>
                </div>
              </nav>

              <!-- Hero Section -->
              <section class="hero">
                <div class="container">
                  <div class="hero-content">
                    <h1>منصة علوم الطب العربي</h1>
                    <p>منصة تعليمية متقدمة لتدريس العلوم الطبية باللغة العربية مع أحدث التقنيات والأساليب التفاعلية</p>
                    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                      <button @click="openCourseModal" class="material-btn">
                        <span class="material-icons">add</span>
                        إضافة دورة جديدة
                      </button>
                      <a href="/student" class="material-btn secondary">
                        <span class="material-icons">school</span>
                        بوابة الطلاب
                      </a>
                    </div>
                  </div>
                </div>
              </section>

              <!-- KPI Section -->
              <section class="section" style="background: var(--surface-variant);">
                <div class="container">
                  <div class="section-header">
                    <h2>إحصائيات المنصة</h2>
                    <p>نظرة شاملة على أداء وإحصائيات المنصة التعليمية</p>
                  </div>
                  <div class="grid grid-cols-4">
                    <div class="material-card kpi-card">
                      <div class="kpi-content">
                        <h3>الطلاب المسجلون</h3>
                        <div class="value">{{ stats.students }}</div>
                      </div>
                      <div class="kpi-icon students">
                        <span class="material-icons">people</span>
                      </div>
                    </div>
                    <div class="material-card kpi-card">
                      <div class="kpi-content">
                        <h3>الدورات المتاحة</h3>
                        <div class="value">{{ stats.courses }}</div>
                      </div>
                      <div class="kpi-icon courses">
                        <span class="material-icons">menu_book</span>
                      </div>
                    </div>
                    <div class="material-card kpi-card">
                      <div class="kpi-content">
                        <h3>مقاطع الفيديو</h3>
                        <div class="value">{{ stats.videos }}</div>
                      </div>
                      <div class="kpi-icon videos">
                        <span class="material-icons">play_circle</span>
                      </div>
                    </div>
                    <div class="material-card kpi-card">
                      <div class="kpi-content">
                        <h3>الامتحانات</h3>
                        <div class="value">{{ stats.exams }}</div>
                      </div>
                      <div class="kpi-icon exams">
                        <span class="material-icons">quiz</span>
                      </div>
                    </div>
                  </div>
                </div>
              </section>

              <!-- Demo Table Section -->
              <section class="section">
                <div class="container">
                  <div class="section-header">
                    <h2>الدورات الحديثة</h2>
                    <p>أحدث الدورات المضافة إلى المنصة</p>
                  </div>
                  <div class="material-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                      <h3 style="margin: 0;">قائمة الدورات</h3>
                      <button @click="openVideoModal" class="material-btn">
                        <span class="material-icons">video_library</span>
                        إضافة فيديو
                      </button>
                    </div>
                    <table class="material-table">
                      <thead>
                        <tr>
                          <th @click="sortTable('title')">اسم الدورة <span class="material-icons" style="font-size: 16px;">sort</span></th>
                          <th @click="sortTable('instructor')">المدرس</th>
                          <th @click="sortTable('students')">عدد الطلاب</th>
                          <th @click="sortTable('status')">الحالة</th>
                          <th>الإجراءات</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="course in sortedCourses" :key="course.id">
                          <td>{{ course.title }}</td>
                          <td>{{ course.instructor }}</td>
                          <td>{{ course.students }}</td>
                          <td>
                            <span :class="['status-badge', course.status]">
                              {{ course.statusText }}
                            </span>
                          </td>
                          <td>
                            <button @click="editCourse(course)" class="material-btn" style="padding: 0.5rem;">
                              <span class="material-icons">edit</span>
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div style="display: flex; justify-content: between; align-items: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.1);">
                      <span>عرض 1-5 من 23 دورة</span>
                      <div style="display: flex; gap: 0.5rem;">
                        <button class="material-btn outline" style="padding: 0.5rem 1rem;">السابق</button>
                        <button class="material-btn outline" style="padding: 0.5rem 1rem;">التالي</button>
                      </div>
                    </div>
                  </div>
                </div>
              </section>

              <!-- Charts Section -->
              <section class="section" style="background: var(--surface-variant);">
                <div class="container">
                  <div class="section-header">
                    <h2>التقارير والإحصائيات</h2>
                    <p>تحليلات مفصلة لأداء المنصة والطلاب</p>
                  </div>
                  <div class="grid grid-cols-2">
                    <div class="material-card">
                      <h3 style="margin-top: 0;">تسجيلات الطلاب الشهرية</h3>
                      <div class="chart-container">
                        <canvas ref="enrollmentChart"></canvas>
                      </div>
                    </div>
                    <div class="material-card">
                      <h3 style="margin-top: 0;">توزيع الدورات حسب التخصص</h3>
                      <div class="chart-container">
                        <canvas ref="categoryChart"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </section>

              <!-- Student Progress Demo -->
              <section class="section">
                <div class="container">
                  <div class="section-header">
                    <h2>تقدم الطلاب</h2>
                    <p>نموذج لوحة تحكم الطالب</p>
                  </div>
                  <div class="grid grid-cols-3">
                    <div class="material-card">
                      <h3 style="margin-top: 0;">التقدم العام</h3>
                      <div style="margin: 1rem 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                          <span>الدورات المكتملة</span>
                          <span>75%</span>
                        </div>
                        <div style="background: #f3f4f6; height: 8px; border-radius: 4px; overflow: hidden;">
                          <div style="background: var(--primary); height: 100%; width: 75%; transition: width 0.3s ease;"></div>
                        </div>
                      </div>
                      <div style="margin: 1rem 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                          <span>الامتحانات المجتازة</span>
                          <span>85%</span>
                        </div>
                        <div style="background: #f3f4f6; height: 8px; border-radius: 4px; overflow: hidden;">
                          <div style="background: var(--secondary); height: 100%; width: 85%; transition: width 0.3s ease;"></div>
                        </div>
                      </div>
                    </div>
                    <div class="material-card">
                      <h3 style="margin-top: 0;">الإنجازات</h3>
                      <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                          <div style="width: 40px; height: 40px; background: rgba(245, 158, 11, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <span class="material-icons" style="color: var(--accent);">star</span>
                          </div>
                          <div>
                            <div style="font-weight: 600;">طالب متميز</div>
                            <div style="font-size: 0.875rem; color: var(--on-surface-variant);">أكمل 10 دورات</div>
                          </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                          <div style="width: 40px; height: 40px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <span class="material-icons" style="color: var(--secondary);">verified</span>
                          </div>
                          <div>
                            <div style="font-weight: 600;">شهادة معتمدة</div>
                            <div style="font-size: 0.875rem; color: var(--on-surface-variant);">في علم التشريح</div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="material-card">
                      <h3 style="margin-top: 0;">النشاط الأخير</h3>
                      <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="padding: 0.75rem; background: var(--surface-variant); border-radius: 8px;">
                          <div style="font-weight: 500;">مشاهدة فيديو</div>
                          <div style="font-size: 0.875rem; color: var(--on-surface-variant);">أساسيات علم وظائف الأعضاء</div>
                          <div style="font-size: 0.75rem; color: var(--on-surface-variant);">منذ ساعتين</div>
                        </div>
                        <div style="padding: 0.75rem; background: var(--surface-variant); border-radius: 8px;">
                          <div style="font-weight: 500;">اجتياز امتحان</div>
                          <div style="font-size: 0.875rem; color: var(--on-surface-variant);">امتحان الفصل الثالث - النتيجة: 92%</div>
                          <div style="font-size: 0.75rem; color: var(--on-surface-variant);">أمس</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>

              <!-- Course Modal -->
              <div v-if="showCourseModal" class="modal-overlay" @click="closeCourseModal">
                <div class="modal-content" @click.stop>
                  <div class="modal-header">
                    <h2>إضافة دورة جديدة</h2>
                    <button @click="closeCourseModal" class="close-btn">
                      <span class="material-icons">close</span>
                    </button>
                  </div>
                  <form @submit.prevent="submitCourse">
                    <div class="form-group">
                      <label class="form-label">عنوان الدورة (عربي)</label>
                      <input v-model="courseForm.title_ar" class="form-input" required />
                    </div>
                    <div class="form-group">
                      <label class="form-label">عنوان الدورة (إنجليزي)</label>
                      <input v-model="courseForm.title_en" class="form-input" />
                    </div>
                    <div class="form-group">
                      <label class="form-label">التخصص</label>
                      <select v-model="courseForm.category_id" class="form-select" required>
                        <option value="">اختر التخصص</option>
                        <option value="1">علم التشريح</option>
                        <option value="2">علم وظائف الأعضاء</option>
                        <option value="3">علم الأمراض</option>
                        <option value="4">الطب الباطني</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="form-label">المستوى</label>
                      <select v-model="courseForm.level" class="form-select">
                        <option value="beginner">مبتدئ</option>
                        <option value="intermediate">متوسط</option>
                        <option value="advanced">متقدم</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <div class="checkbox-group">
                        <input type="checkbox" v-model="courseForm.is_free" id="is_free" />
                        <label for="is_free">دورة مجانية</label>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="checkbox-group">
                        <input type="checkbox" v-model="courseForm.is_published" id="is_published" />
                        <label for="is_published">نشر الدورة</label>
                      </div>
                    </div>
                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                      <button type="button" @click="closeCourseModal" class="material-btn outline">إلغاء</button>
                      <button type="submit" class="material-btn">
                        <span class="material-icons">save</span>
                        حفظ الدورة
                      </button>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Video Modal -->
              <div v-if="showVideoModal" class="modal-overlay" @click="closeVideoModal">
                <div class="modal-content" @click.stop>
                  <div class="modal-header">
                    <h2>إضافة فيديو جديد</h2>
                    <button @click="closeVideoModal" class="close-btn">
                      <span class="material-icons">close</span>
                    </button>
                  </div>
                  <form @submit.prevent="submitVideo">
                    <div class="form-group">
                      <label class="form-label">الدورة</label>
                      <select v-model="videoForm.course_id" class="form-select" required>
                        <option value="">اختر الدورة</option>
                        <option value="1">أساسيات علم التشريح</option>
                        <option value="2">علم وظائف الأعضاء المتقدم</option>
                        <option value="3">مقدمة في علم الأمراض</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="form-label">عنوان الفيديو (عربي)</label>
                      <input v-model="videoForm.title_ar" class="form-input" required />
                    </div>
                    <div class="form-group">
                      <label class="form-label">رابط الفيديو</label>
                      <input v-model="videoForm.video_url" class="form-input" type="url" required />
                    </div>
                    <div class="form-group">
                      <label class="form-label">مدة الفيديو (بالثواني)</label>
                      <input v-model="videoForm.duration_seconds" class="form-input" type="number" min="0" />
                    </div>
                    <div class="form-group">
                      <label class="form-label">جودة الفيديو</label>
                      <select v-model="videoForm.video_quality" class="form-select">
                        <option value="">اختر الجودة</option>
                        <option value="360p">360p</option>
                        <option value="720p">720p</option>
                        <option value="1080p">1080p</option>
                        <option value="4k">4K</option>
                      </select>
                    </div>
                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                      <button type="button" @click="closeVideoModal" class="material-btn outline">إلغاء</button>
                      <button type="submit" class="material-btn">
                        <span class="material-icons">save</span>
                        حفظ الفيديو
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </template>
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
