<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Dashboard' ?> — ShopAdmin</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #ede9fe;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-active: #4f46e5;
            --body-bg: #f1f5f9;
            --card-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px -1px rgba(0,0,0,.1);
        }

        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--body-bg); }

        /* ── Sidebar ─────────────────────────── */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1040;
            overflow-y: auto;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            display: flex; align-items: center; gap: 12px;
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-brand .brand-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff;
        }
        .sidebar-brand h5 { color: #fff; margin: 0; font-size: 16px; font-weight: 700; }
        .sidebar-brand small { color: var(--sidebar-text); font-size: 11px; }

        .sidebar-section {
            padding: 20px 16px 8px;
            font-size: 10px; font-weight: 700;
            letter-spacing: .08em;
            color: #475569;
            text-transform: uppercase;
        }

        .sidebar nav a {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 16px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 10px;
            margin: 2px 8px;
            font-size: 14px; font-weight: 500;
            transition: all .2s;
        }
        .sidebar nav a:hover {
            background: rgba(255,255,255,.07);
            color: #fff;
        }
        .sidebar nav a.active {
            background: var(--primary);
            color: #fff;
        }
        .sidebar nav a .badge {
            margin-left: auto;
            background: rgba(255,255,255,.15);
        }
        .sidebar nav a.active .badge {
            background: rgba(255,255,255,.3);
        }
        .sidebar nav a i { width: 20px; text-align: center; font-size: 16px; }

        /* ── Main layout ──────────────────────── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* ── Topnav ───────────────────────────── */
        .topnav {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 24px;
            height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .topnav .page-title { font-size: 18px; font-weight: 700; color: #0f172a; margin: 0; }
        .topnav .breadcrumb { margin: 0; font-size: 12px; }

        .topnav .admin-info {
            display: flex; align-items: center; gap: 10px;
        }
        .admin-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 14px;
        }

        /* ── Content ──────────────────────────── */
        .content-area { padding: 24px; flex: 1; }

        /* ── Cards ────────────────────────────── */
        .card {
            border: none;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 700;
            padding: 16px 20px;
        }

        .stat-card {
            border-radius: 14px;
            border: none;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }
        .stat-card .stat-icon {
            width: 52px; height: 52px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }
        .stat-card .stat-label {
            font-size: 12px; font-weight: 600;
            text-transform: uppercase; letter-spacing: .05em;
            color: #64748b;
        }
        .stat-card .stat-value {
            font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1;
        }
        .stat-card .stat-trend {
            font-size: 12px; font-weight: 600;
        }

        /* ── Tables ───────────────────────────── */
        .table th {
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .06em;
            color: #64748b; background: #f8fafc;
        }
        .table td { vertical-align: middle; font-size: 14px; }
        .product-img-thumb {
            width: 44px; height: 44px;
            border-radius: 10px;
            object-fit: cover;
            background: #e2e8f0;
        }
        .product-img-placeholder {
            width: 44px; height: 44px;
            border-radius: 10px;
            background: #e2e8f0;
            display: flex; align-items: center; justify-content: center;
            color: #94a3b8; font-size: 20px;
        }

        /* ── Status badges ────────────────────── */
        .status-badge {
            padding: 4px 12px;
            border-radius: 99px;
            font-size: 12px; font-weight: 600;
        }
        .status-pending    { background:#fef9c3; color:#854d0e; }
        .status-processing { background:#dbeafe; color:#1e40af; }
        .status-shipped    { background:#e0e7ff; color:#3730a3; }
        .status-delivered  { background:#dcfce7; color:#166534; }
        .status-cancelled  { background:#fee2e2; color:#991b1b; }

        /* ── Flash messages ───────────────────── */
        .flash-message {
            position: fixed; top: 80px; right: 24px;
            z-index: 9999; min-width: 280px;
            animation: slideIn .3s ease;
        }
        @keyframes slideIn {
            from { transform: translateX(120%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }

        /* ── Stock badge ──────────────────────── */
        .stock-ok   { color: #16a34a; font-weight: 700; }
        .stock-low  { color: #d97706; font-weight: 700; }
        .stock-zero { color: #dc2626; font-weight: 700; }

        /* ── Responsive ───────────────────────── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
        }
    </style>
</head>
<body>
