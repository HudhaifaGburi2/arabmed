# Frontend Asset Structure - Arab-Med Platform

## 📁 Recommended Folder Structure

```
resources/
├── css/
│   ├── app.css                    # Main CSS entry point
│   ├── theme.css                  # Material Dashboard theme & variables
│   ├── rtl.css                    # RTL support for Arabic
│   ├── layouts/
│   │   ├── header.css             # Navigation & header styles
│   │   ├── sidebar.css            # Admin/Teacher sidebar
│   │   ├── footer.css             # Footer styles
│   │   └── auth.css               # Login/register layouts
│   ├── pages/
│   │   ├── welcome.css            # Landing page styles
│   │   ├── dashboard.css          # Student/Admin dashboard
│   │   ├── courses.css            # Course listing & details
│   │   ├── videos.css             # Video player & library
│   │   ├── exams.css              # Exam interface & results
│   │   ├── admin.css              # Admin panel specific
│   │   └── reports.css            # Analytics & reports
│   └── components/
│       ├── cards.css              # KPI cards, course cards
│       ├── modals.css             # Modal dialogs
│       ├── tables.css             # Data tables with sorting
│       ├── forms.css              # Form elements & validation
│       ├── charts.css             # Chart.js styling
│       ├── buttons.css            # Button variants
│       └── utilities.css          # Helper classes
├── js/
│   ├── app.js                     # Main JS entry point
│   ├── bootstrap.js               # Laravel Echo, Axios setup
│   ├── layouts/
│   │   ├── header.js              # Navigation interactions
│   │   ├── sidebar.js             # Sidebar toggle & menu
│   │   └── auth.js                # Login/logout logic
│   ├── pages/
│   │   ├── welcome.js             # Landing page interactions
│   │   ├── dashboard.js           # Dashboard widgets & charts
│   │   ├── courses.js             # Course management
│   │   ├── videos.js              # Video player controls
│   │   ├── exams.js               # Exam taking interface
│   │   ├── admin.js               # Admin panel logic
│   │   └── reports.js             # Analytics dashboard
│   ├── components/
│   │   ├── forms/
│   │   │   ├── CourseForm.vue     # Course creation/edit
│   │   │   ├── VideoForm.vue      # Video upload/edit
│   │   │   ├── ExamForm.vue       # Exam builder
│   │   │   └── UserForm.vue       # User management
│   │   ├── ui/
│   │   │   ├── Modal.vue          # Reusable modal
│   │   │   ├── DataTable.vue      # Sortable table
│   │   │   ├── KpiCard.vue        # Statistics card
│   │   │   ├── ProgressBar.vue    # Progress indicator
│   │   │   └── Chart.vue          # Chart wrapper
│   │   └── layout/
│   │       ├── Navbar.vue         # Navigation component
│   │       ├── Sidebar.vue        # Admin sidebar
│   │       └── Breadcrumb.vue     # Breadcrumb navigation
│   ├── services/
│   │   ├── api.js                 # Axios API client
│   │   ├── auth.js                # Authentication service
│   │   ├── storage.js             # LocalStorage wrapper
│   │   └── validation.js          # Form validation rules
│   └── utils/
│       ├── helpers.js             # Common utility functions
│       ├── formatters.js          # Date/number formatting
│       ├── constants.js           # App constants
│       └── rtl.js                 # RTL text direction helpers
├── images/
│   ├── logos/
│   │   ├── logo.svg               # Main logo
│   │   ├── logo-dark.svg          # Dark theme logo
│   │   └── favicon.ico            # Browser favicon
│   ├── banners/
│   │   ├── hero-bg.jpg            # Landing page hero
│   │   ├── course-placeholder.jpg # Default course image
│   │   └── medical-bg.jpg         # Medical themed background
│   ├── icons/
│   │   ├── medical/               # Medical specialty icons
│   │   ├── ui/                    # UI element icons
│   │   └── flags/                 # Language flags
│   └── demo/
│       ├── instructors/           # Demo instructor photos
│       ├── courses/               # Demo course thumbnails
│       └── certificates/          # Certificate templates
└── fonts/
    ├── NotoSansArabic/           # Arabic font family
    ├── Inter/                     # Latin font family
    └── MaterialIcons/             # Material Design icons
```
