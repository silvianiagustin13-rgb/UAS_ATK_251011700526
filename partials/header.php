<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OfficeStock - Sistem Pengadaan ATK</title>

    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="stylesheet" href="assets/toastr.min.css">
    <link rel="stylesheet" href="assets/DataTables-1.13.3/css/jquery.dataTables.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="assets/jquery-3.6.1.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <script src="assets/toastr.min.js"></script>
    <script src="assets/DataTables-1.13.3/js/jquery.dataTables.min.js"></script>

    <style>
        :root {
            --navy-main:    #14305C;
            --navy-light:   #2C4D85;
            --navy-pale:    #EEF2F9;
            --amber-main:   #F2A93B;
            --amber-light:  #FDEBC9;
            --teal-main:    #0E8C7F;
            --teal-light:   #DDF4F0;
            --slate:        #64748B;
            --sidebar-bg:   #0F2042;
            --text-dark:    #14305C;
            --card-shadow:  0 8px 28px rgba(20,48,92,0.10);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0; padding: 0;
            background: linear-gradient(160deg, #EEF2F9 0%, #F7F9FC 50%, #E9F6F4 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .navbar-officestock {
            background: linear-gradient(90deg, #0F2042 0%, #14305C 55%, #0E8C7F 100%);
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            height: 64px;
            display: flex; align-items: center; padding: 0 24px;
            box-shadow: 0 4px 20px rgba(15,32,66,0.35);
        }
        .navbar-brand-text {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 1.35rem;
            color: white;
            text-decoration: none;
            letter-spacing: 0.3px;
        }
        .navbar-brand-text span { color: #F2A93B; }
        .navbar-right {
            margin-left: auto;
            display: flex; align-items: center; gap: 12px;
        }
        .welcome-badge {
            background: rgba(255,255,255,0.15);
            color: white;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            backdrop-filter: blur(4px);
        }
        .logout-btn {
            background: rgba(255,255,255,0.12);
            color: white;
            border: 1.5px solid rgba(255,255,255,0.4);
            padding: 6px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
        }
        .logout-btn:hover {
            background: #F2A93B;
            color: #14305C;
            border-color: #F2A93B;
        }

        .sidebar {
            position: fixed; top: 64px; left: 0;
            width: 230px; height: calc(100vh - 64px);
            background: var(--sidebar-bg);
            padding: 20px 0;
            overflow-y: auto;
            z-index: 999;
        }
        .sidebar-section {
            padding: 8px 16px 4px;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.3);
            text-transform: uppercase;
            margin-top: 8px;
        }
        .sidebar a {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.25s;
            border-left: 3px solid transparent;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(242,169,59,0.12);
            color: #F2A93B;
            border-left-color: #F2A93B;
        }
        .sidebar a i {
            width: 20px; text-align: center;
            font-size: 1rem;
        }
        .sidebar-footer {
            padding: 16px 20px;
            margin-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-footer p {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.3);
            margin: 0;
            text-align: center;
        }

        .main-content {
            margin-left: 230px;
            margin-top: 64px;
            padding: 28px;
            min-height: calc(100vh - 64px);
        }

        .page-header {
            background: white;
            border-radius: 16px;
            padding: 20px 28px;
            margin-bottom: 24px;
            box-shadow: var(--card-shadow);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
            border-left: 5px solid #F2A93B;
        }
        .page-header h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0;
        }
        .page-header h1 span { color: #0E8C7F; }

        .btn-os-primary {
            background: linear-gradient(135deg, #14305C, #0E8C7F);
            color: white; border: none;
            padding: 10px 22px; border-radius: 10px;
            font-weight: 700; font-size: 0.86rem;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 7px;
            box-shadow: 0 4px 14px rgba(20,48,92,0.3);
        }
        .btn-os-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(20,48,92,0.4);
            color: white;
        }
        .btn-os-amber {
            background: linear-gradient(135deg, #F2A93B, #E08E1B);
            color: white; border: none;
            padding: 10px 22px; border-radius: 10px;
            font-weight: 700; font-size: 0.86rem;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 7px;
            box-shadow: 0 4px 14px rgba(242,169,59,0.35);
        }
        .btn-os-amber:hover { transform: translateY(-2px); color: white; }
        .btn-os-danger {
            background: linear-gradient(135deg, #DC2626, #991B1B);
            color: white; border: none;
            padding: 10px 22px; border-radius: 10px;
            font-weight: 700; font-size: 0.86rem;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 7px;
            box-shadow: 0 4px 14px rgba(220,38,38,0.3);
        }
        .btn-os-danger:hover { transform: translateY(-2px); color: white; }

        .os-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }
        .os-card .card-body { padding: 24px; }

        .stat-card-new {
            border-radius: 16px;
            padding: 22px 22px 14px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .stat-card-new:hover { transform: translateY(-4px); }
        .stat-card-new::before {
            content: '';
            position: absolute; top: -40px; right: -40px;
            width: 120px; height: 120px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .stat-card-inner { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
        .stat-card-icon-wrap {
            width: 54px; height: 54px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; flex-shrink: 0;
        }
        .stat-card-info { flex: 1; }
        .stat-num { font-size: 2.1rem; font-weight: 800; line-height: 1; margin-bottom: 4px; }
        .stat-lbl { font-size: 0.82rem; font-weight: 600; opacity: 0.88; }
        .stat-card-bar {
            height: 4px; background: rgba(255,255,255,0.2);
            border-radius: 99px; margin-bottom: 12px; overflow: hidden;
        }
        .stat-bar-fill { height: 100%; border-radius: 99px; transition: width 1s ease; }
        .stat-card-link {
            font-size: 0.78rem; font-weight: 700;
            color: rgba(255,255,255,0.85); text-decoration: none;
            display: flex; align-items: center; gap: 6px;
        }
        .stat-card-link:hover { color: white; }

        .os-table { width: 100%; border-collapse: collapse; }
        .os-table thead tr {
            background: linear-gradient(135deg, #14305C, #0E8C7F);
            color: white;
        }
        .os-table thead th {
            padding: 14px 16px;
            font-size: 0.8rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.5px;
            border: none;
        }
        .os-table tbody tr { border-bottom: 1px solid #EEF2F9; transition: background 0.2s; }
        .os-table tbody tr:hover { background: #F7FAFC; }
        .os-table tbody td { padding: 12px 16px; vertical-align: middle; font-size: 0.87rem; color: var(--text-dark); }

        .badge-os {
            display: inline-block;
            padding: 4px 12px; border-radius: 8px;
            font-size: 0.74rem; font-weight: 700;
        }
        .badge-navy { background: #DCE5F4; color: #14305C; }
        .badge-teal { background: #DDF4F0; color: #0E8C7F; }
        .badge-amber { background: #FDEBC9; color: #B97A0E; }
        .badge-green { background: #DCFCE7; color: #16A34A; }
        .badge-red { background: #FFE4E4; color: #DC2626; }
        .badge-gray { background: #E5E7EB; color: #4B5563; }

        .btn-action {
            padding: 6px 14px; border-radius: 8px;
            font-size: 0.78rem; font-weight: 700;
            border: none; cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 5px;
            transition: all 0.25s;
        }
        .btn-edit { background: linear-gradient(135deg, #F2A93B, #E08E1B); color: white; }
        .btn-edit:hover { transform: scale(1.05); color: white; }
        .btn-delete { background: linear-gradient(135deg, #DC2626, #991B1B); color: white; }
        .btn-delete:hover { transform: scale(1.05); color: white; }

        .form-label { font-weight: 700; font-size: 0.85rem; color: var(--text-dark); margin-bottom: 5px; }
        .form-control, .form-select {
            border: 1.5px solid #DCE5F4;
            border-radius: 10px;
            padding: 10px 14px;
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0E8C7F;
            box-shadow: 0 0 0 4px rgba(14,140,127,0.12);
            outline: none;
        }
        textarea.form-control { resize: vertical; }

        .img-preview-wrap {
            border: 2px dashed #BFD0E8;
            border-radius: 12px;
            padding: 12px;
            text-align: center;
            background: #F7FAFC;
            min-height: 120px;
            display: flex; align-items: center; justify-content: center;
        }
        .img-preview-wrap img { max-width: 100%; max-height: 200px; border-radius: 10px; object-fit: cover; }

        .toast-title { font-family: 'Inter', sans-serif; font-weight: 800; }
        .toast-message { font-family: 'Inter', sans-serif; }
        #toast-container > .toast-success { background-color: #0E8C7F !important; border-left: 4px solid #F2A93B; }
        #toast-container > .toast-error { background-color: #DC2626 !important; border-left: 4px solid #F2A93B; }
        #toast-container > .toast-info { background-color: #14305C !important; border-left: 4px solid #F2A93B; }
        #toast-container > .toast-warning { background-color: #F2A93B !important; border-left: 4px solid #14305C; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: 0.3s; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 16px; }
        }
    </style>
</head>
<body>

<nav class="navbar-officestock">
    <a href="/UAS_ATK/index.php" class="navbar-brand-text" style="display:flex;align-items:center;gap:10px;">
        <svg width="34" height="34" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" style="flex-shrink:0;">
            <rect width="64" height="64" rx="14" fill="rgba(255,255,255,0.18)"/>
            <rect x="14" y="10" width="24" height="32" rx="3" fill="white" fill-opacity="0.95"/>
            <rect x="18" y="16" width="16" height="3" rx="1.5" fill="#14305C"/>
            <rect x="18" y="22" width="16" height="3" rx="1.5" fill="#0E8C7F"/>
            <rect x="18" y="28" width="10" height="3" rx="1.5" fill="#14305C"/>
            <rect x="36" y="20" width="14" height="30" rx="3" fill="#F2A93B"/>
            <circle cx="43" cy="27" r="3" fill="white"/>
            <rect x="40.5" y="34" width="5" height="10" rx="1" fill="white" fill-opacity="0.85"/>
        </svg>
        Office<span>Stock</span>
    </a>
    <div class="navbar-right">
        <span class="welcome-badge"><i class="fas fa-user-tie me-1"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="/UAS_ATK/login/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
    </div>
</nav>

<div class="sidebar">
    <div class="sidebar-section">Menu Utama</div>
    <a href="/UAS_ATK/index.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'class="active"' : ''; ?>>
        <i class="fas fa-th-large"></i> Dashboard
    </a>
    <a href="/UAS_ATK/pengadaan.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'pengadaan.php') ? 'class="active"' : ''; ?>>
        <i class="fas fa-boxes-stacked"></i> Data Pengadaan ATK
    </a>
    <a href="/UAS_ATK/report.php" target="_blank">
        <i class="fas fa-file-pdf"></i> Cetak Laporan PDF
    </a>
    <div class="sidebar-section">Pengaturan</div>
    <a href="/UAS_ATK/user.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'user.php') ? 'class="active"' : ''; ?>>
        <i class="fas fa-user-shield"></i> Manajemen User
    </a>
    <div class="sidebar-section">Akun</div>
    <a href="/UAS_ATK/login/logout.php">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
    <div class="sidebar-footer">
        <p>© 2026 OfficeStock<br>Sistem Pengadaan ATK<br>UAS Pemrograman Web 2</p>
    </div>
</div>

