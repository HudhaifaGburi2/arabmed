<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>منصة علوم الطب العربي - Arab Medical Sciences Platform</title>
  <link rel="icon" type="image/x-icon" href="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjMyIiBoZWlnaHQ9IjMyIiByeD0iOCIgZmlsbD0iIzI1NjNlYiIvPgo8cGF0aCBkPSJNMTYgOGMtNC40MTggMC04IDMuNTgyLTggOHMzLjU4MiA4IDggOCA4LTMuNTgyIDgtOC0zLjU4Mi04LTgtOHptMCAxNGMtMy4zMTQgMC02LTIuNjg2LTYtNnMyLjY4Ni02IDYtNiA2IDIuNjg2IDYgNi0yLjY4NiA2LTYgNnoiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik0xNiAxMmMtMi4yMDkgMC00IDEuNzkxLTQgNHMxLjc5MSA0IDQgNCA0LTEuNzkxIDQtNC0xLjc5MS00LTQtNHoiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=" />
  
  <!-- Material Icons & Fonts -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  @vite(['resources/css/app.css','resources/js/landing/main-welcome.js'])
  
  <style>
    :root {
      --primary: #2563eb;
      --primary-dark: #1d4ed8;
      --secondary: #10b981;
      --accent: #f59e0b;
      --surface: #ffffff;
      --surface-variant: #f8fafc;
      --on-surface: #1e293b;
      --on-surface-variant: #64748b;
      --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    * { box-sizing: border-box; }
    
    body {
      font-family: 'Noto Sans Arabic', 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      margin: 0;
      padding: 0;
      min-height: 100vh;
      color: var(--on-surface);
      line-height: 1.6;
    }

    /* Material Design Components */
    .material-card {
      background: var(--surface);
      border-radius: 12px;
      box-shadow: var(--shadow);
      padding: 1.5rem;
      transition: all 0.3s ease;
      border: 1px solid rgba(0,0,0,0.05);
    }

    .material-card:hover {
      box-shadow: var(--shadow-lg);
      transform: translateY(-2px);
    }

    .material-btn {
      background: var(--primary);
      color: white;
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.875rem;
    }

    .material-btn:hover {
      background: var(--primary-dark);
      transform: translateY(-1px);
      box-shadow: var(--shadow);
    }

    .material-btn.secondary {
      background: var(--secondary);
    }

    .material-btn.outline {
      background: transparent;
      color: var(--primary);
      border: 2px solid var(--primary);
    }

    .material-btn.outline:hover {
      background: var(--primary);
      color: white;
    }

    /* Layout */
    .container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 1rem;
    }

    .grid {
      display: grid;
      gap: 1.5rem;
    }

    .grid-cols-1 { grid-template-columns: 1fr; }
    .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
    .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
    .grid-cols-4 { grid-template-columns: repeat(4, 1fr); }
    .grid-cols-12 { grid-template-columns: repeat(12, 1fr); }

    .col-span-1 { grid-column: span 1; }
    .col-span-2 { grid-column: span 2; }
    .col-span-3 { grid-column: span 3; }
    .col-span-4 { grid-column: span 4; }
    .col-span-6 { grid-column: span 6; }
    .col-span-8 { grid-column: span 8; }
    .col-span-12 { grid-column: span 12; }

    /* Hero Section */
    .hero {
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.9) 0%, rgba(16, 185, 129, 0.8) 100%),
                  url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8ZGVmcz4KICAgIDxwYXR0ZXJuIGlkPSJncmlkIiB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+CiAgICAgIDxwYXRoIGQ9Ik0gMTAwIDAgTCAwIDAgMCAxMDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgyNTUsMjU1LDI1NSwwLjEpIiBzdHJva2Utd2lkdGg9IjEiLz4KICAgIDwvcGF0dGVybj4KICA8L2RlZnM+CiAgPHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIgLz4KPC9zdmc+');
      color: white;
      padding: 4rem 0;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDUpIj48Y2lyY2xlIGN4PSI0MCIgY3k9IjQwIiByPSI0Ii8+PGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iNCIvPjxjaXJjbGUgY3g9IjAiIGN5PSIwIiByPSI0Ii8+PC9nPjwvc3ZnPg==');
      opacity: 0.3;
    }

    .hero-content {
      position: relative;
      z-index: 1;
    }

    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
      margin: 0 0 1rem;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .hero p {
      font-size: 1.25rem;
      margin: 0 0 2rem;
      opacity: 0.9;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }

    /* KPI Cards */
    .kpi-card {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1.5rem;
    }

    .kpi-content h3 {
      margin: 0 0 0.5rem;
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--on-surface-variant);
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .kpi-content .value {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary);
      margin: 0;
    }

    .kpi-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
    }

    .kpi-icon.students { background: rgba(37, 99, 235, 0.1); color: var(--primary); }
    .kpi-icon.courses { background: rgba(16, 185, 129, 0.1); color: var(--secondary); }
    .kpi-icon.videos { background: rgba(245, 158, 11, 0.1); color: var(--accent); }
    .kpi-icon.exams { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    /* Tables */
    .material-table {
      width: 100%;
      border-collapse: collapse;
      background: var(--surface);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--shadow);
    }

    .material-table th,
    .material-table td {
      padding: 1rem;
      text-align: start;
      border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .material-table th {
      background: var(--surface-variant);
      font-weight: 600;
      color: var(--on-surface);
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .material-table th:hover {
      background: rgba(37, 99, 235, 0.05);
    }

    .material-table tr:hover {
      background: rgba(37, 99, 235, 0.02);
    }

    /* Charts */
    .chart-container {
      position: relative;
      height: 300px;
      padding: 1rem;
    }

    /* Modals */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      backdrop-filter: blur(4px);
    }

    .modal-content {
      background: var(--surface);
      border-radius: 16px;
      padding: 2rem;
      max-width: 500px;
      width: 90%;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: var(--shadow-lg);
    }

    .modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .modal-header h2 {
      margin: 0;
      font-size: 1.25rem;
      font-weight: 600;
    }

    .close-btn {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: var(--on-surface-variant);
      padding: 0.25rem;
      border-radius: 4px;
    }

    .close-btn:hover {
      background: rgba(0,0,0,0.05);
    }

    /* Form Elements */
    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--on-surface);
    }

    .form-input {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 2px solid rgba(0,0,0,0.1);
      border-radius: 8px;
      font-size: 1rem;
      transition: all 0.2s ease;
      background: var(--surface);
    }

    .form-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-select {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 2px solid rgba(0,0,0,0.1);
      border-radius: 8px;
      font-size: 1rem;
      background: var(--surface);
      cursor: pointer;
    }

    .checkbox-group {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .grid-cols-2,
      .grid-cols-3,
      .grid-cols-4 {
        grid-template-columns: 1fr;
      }
      
      .col-span-6,
      .col-span-8 {
        grid-column: span 12;
      }
      
      .hero h1 {
        font-size: 2rem;
      }
      
      .hero p {
        font-size: 1rem;
      }
      
      .container {
        padding: 0 0.5rem;
      }
    }

    /* RTL Support */
    [dir="rtl"] .material-table th,
    [dir="rtl"] .material-table td {
      text-align: start;
    }

    [dir="rtl"] .kpi-card {
      flex-direction: row-reverse;
    }

    /* Loading States */
    .loading {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 2px solid rgba(255,255,255,0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Navigation */
    .nav {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 1rem 0;
      position: sticky;
      top: 0;
      z-index: 100;
      border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .nav-content {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--primary);
      text-decoration: none;
    }

    .nav-links {
      display: flex;
      gap: 1rem;
    }

    .section {
      padding: 3rem 0;
    }

    .section-header {
      text-align: center;
      margin-bottom: 3rem;
    }

    .section-header h2 {
      font-size: 2.5rem;
      font-weight: 700;
      margin: 0 0 1rem;
      color: var(--on-surface);
    }

    .section-header p {
      font-size: 1.125rem;
      color: var(--on-surface-variant);
      max-width: 600px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <div id="welcome-app"></div>
</body>
</html>