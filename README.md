# Arab-Med Platform

Robust Laravel 12 backend with Vue 3 SPAs for Admin/Teacher (Vuexy-like) and Student (Material Dashboard-like).

## Backend Setup

- Copy env and configure DB:
  - `cp .env.example .env`
  - Update DB credentials
- Install PHP deps:
  - `composer install`
- Generate key & migrate:
  - `php artisan key:generate`
  - `php artisan migrate`
- Seed base data:
  - `php artisan db:seed`
  - Optional demo content: `php artisan db:seed --class=DemoContentSeeder`

## Frontend Setup

- Install JS deps:
  - `npm install`
- Run dev servers:
  - `npm run dev` (Vite)
  - `php artisan serve` (Laravel)
- SPAs:
  - Admin/Teacher: open `http://localhost:8000/admin`
  - Student: open `http://localhost:8000/student`

## SPAs Structure

- Admin/Teacher (Vuexy-like): `resources/js/`
  - Entry: `resources/js/main-admin.js`
  - Router: `resources/js/router/index.js`
  - Store: `resources/js/store/auth.js`
  - Services: `resources/js/services/api.js`
  - Shell: `resources/js/components/Layout/AdminShell.vue`
  - Views: `resources/js/views/{Admin,Teacher,Auth}/*`
- Student (Material Dashboard-like): `resources/js/student/`
  - Entry: `resources/js/student/main-student.js`
  - Router: `resources/js/student/router/index.js`
  - Store: `resources/js/student/store/auth.js`
  - Shell: `resources/js/student/components/Layout/StudentShell.vue`
  - Views: `resources/js/student/views/*`
- Blade mounts:
  - Admin: `resources/views/admin.blade.php`
  - Student: `resources/views/student.blade.php`
- Vite entries: `vite.config.js` includes both admin and student bundles

## Authentication

- Sanctum token-based API
- Endpoints used by SPA:
  - `POST /api/v1/auth/register` → returns `{ user, token }`
  - `POST /api/v1/auth/login` → returns `{ user, token }`
  - `GET /api/v1/auth/me` → returns `{ id, first_name, last_name, email, roles[] }`
  - `POST /api/v1/auth/logout`
- The frontend stores token in `localStorage` and sends `Authorization: Bearer <token>` header.

## API Endpoints Cheat Sheet

Public Catalog:
- `GET /api/v1/courses?lang=ar` – list courses
- `GET /api/v1/courses/{course}` – course details
- `GET /api/v1/courses/{course}/videos` – course videos
- `GET /api/v1/courses/{course}/documents` – course docs
- `GET /api/v1/courses/{course}/exams` – course exams

Auth Required:
- Enroll course: `POST /api/v1/courses/{course}/enroll`
- Video progress: `PUT /api/v1/videos/{video}/progress`
- Audio progress: `PUT /api/v1/audios/{audio}/progress`
- Read progress: `GET /api/v1/users/{user}/progress/{videos|audios}`

Exam Attempts:
- Start: `POST /api/v1/exams/{exam}/attempts/start`
- Submit: `POST /api/v1/exams/{exam}/attempts/submit`

Authoring (role: admin or teacher):
- Courses: `POST/PUT/DELETE /api/v1/courses[/ {course}]`
- Videos: `POST/PUT/DELETE /api/v1/videos[/ {video}]`
- Documents: `POST/PUT/DELETE /api/v1/documents[/ {document}]`
- Exams: `POST/PUT/DELETE /api/v1/exams[/ {exam}]`
- Questions: `POST/PUT/DELETE /api/v1/exams/{exam}/questions[/ {question}]`
- Options: `POST/PUT/DELETE /api/v1/exams/{exam}/questions/{question}/options[/ {option}]`

## Example Requests (curl)

Register (Student/Teacher/Admin):
```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
 -H 'Content-Type: application/json' \
 -d '{"first_name":"Ali","last_name":"Ahmad","email":"ali@example.com","password":"password"}'
```

Login:
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
 -H 'Content-Type: application/json' \
 -d '{"email":"ali@example.com","password":"password"}'
```

List Courses:
```bash
curl 'http://localhost:8000/api/v1/courses?lang=ar'
```

Enroll:
```bash
curl -X POST http://localhost:8000/api/v1/courses/1/enroll \
 -H 'Authorization: Bearer TOKEN'
```

Update Video Progress:
```bash
curl -X PUT http://localhost:8000/api/v1/videos/1/progress \
 -H 'Authorization: Bearer TOKEN' -H 'Content-Type: application/json' \
 -d '{"watched_seconds":120,"total_seconds":600,"last_position_seconds":120}'
```

Create Exam:
```bash
curl -X POST http://localhost:8000/api/v1/exams \
 -H 'Authorization: Bearer TOKEN' -H 'Content-Type: application/json' \
 -d '{"course_id":1,"title_ar":"اختبار 1","time_limit_minutes":10,"max_attempts":3,"passing_score":70,"total_marks":10,"is_active":true}'
```

Add Question:
```bash
curl -X POST http://localhost:8000/api/v1/exams/1/questions \
 -H 'Authorization: Bearer TOKEN' -H 'Content-Type: application/json' \
 -d '{"question_ar":"ما عدد العظام؟","question_type":"multiple_choice","marks":1}'
```

Add Option:
```bash
curl -X POST http://localhost:8000/api/v1/exams/1/questions/1/options \
 -H 'Authorization: Bearer TOKEN' -H 'Content-Type: application/json' \
 -d '{"option_ar":"206","is_correct":true}'
```

Start Attempt:
```bash
curl -X POST http://localhost:8000/api/v1/exams/1/attempts/start \
 -H 'Authorization: Bearer TOKEN'
```

Submit Attempt:
```bash
curl -X POST http://localhost:8000/api/v1/exams/1/attempts/submit \
 -H 'Authorization: Bearer TOKEN' -H 'Content-Type: application/json' \
 -d '{"attempt_id":1,"answers":[{"question_id":1,"selected_option_id":10}]}'
```

## Development Notes

- RTL layout set on Blade mounts (`dir="rtl"`).
- Localization via `localize` middleware; append `?lang=ar` to API calls.
- Axios is configured in `resources/js/services/api.js` with bearer token.
- Role protection for SPA routes handled via Pinia `auth` store and router guards.

## Roadmap

- Replace basic styling with Vuexy Free Vue Admin components (Admin/Teacher) and Material Dashboard Vue components (Student).
- Add charts (Chart.js) for completion and exam statistics.
- Add Users management endpoint and UI for Admin.
