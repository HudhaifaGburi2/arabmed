<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>منصة علوم الطب العربي</title>
  @vite(['resources/css/app.css','resources/js/landing/main-welcome.js'])
  <style>
    body { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Arial; background:#0f172a; color:#e5e7eb }
    .hero{ background: linear-gradient(135deg,#1e293b 0%,#0f172a 100%); padding:3rem 1rem; text-align:center }
    .hero h1{ font-size:2rem; margin:0 0 .5rem }
    .hero p{ color:#cbd5e1 }
    .container{ max-width:1200px; margin:0 auto; padding:1rem }
    .card{ background:#111827; border:1px solid #1f2937; border-radius:.75rem; padding:1rem }
    .grid{ display:grid; grid-template-columns:repeat(12,1fr); gap:1rem }
    .kpi{ display:flex; align-items:center; justify-content:space-between }
    .kpi .value{ font-size:1.75rem; font-weight:700 }
    .btn{ background:#2563eb; color:#fff; border:none; padding:.5rem .75rem; border-radius:.375rem; cursor:pointer }
    a.btn{ text-decoration:none }
  </style>
</head>
<body>
  <div id="welcome-app"></div>
  <!-- Vue app renders the landing content -->
</body>
</html>