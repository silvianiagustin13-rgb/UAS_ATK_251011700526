<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — OfficeStock</title>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Poppins:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            display: flex; align-items: center; justify-content: center;
            background: #0F2042;
            overflow: hidden;
        }

        .grid-bg {
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
            background-size: 42px 42px;
        }
        .glow {
            position: fixed; width: 460px; height: 460px;
            background: radial-gradient(circle, rgba(14,140,127,0.35) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none; z-index: 0;
            top: -120px; right: -120px;
        }
        .glow2 {
            position: fixed; width: 380px; height: 380px;
            background: radial-gradient(circle, rgba(242,169,59,0.18) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none; z-index: 0;
            bottom: -100px; left: -100px;
        }

        .login-wrapper {
            position: relative; z-index: 1;
            display: flex;
            width: 880px; max-width: 96vw;
            min-height: 530px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.5);
        }

        .login-left {
            flex: 1;
            background: linear-gradient(160deg, #14305C 0%, #0E8C7F 100%);
            padding: 48px 36px;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            color: white; text-align: center;
            position: relative; overflow: hidden;
        }
        .login-left::before {
            content: '';
            position: absolute; top: -60px; right: -60px;
            width: 180px; height: 180px;
            background: rgba(255,255,255,0.07);
            border-radius: 50%;
        }
        .login-left::after {
            content: '';
            position: absolute; bottom: -50px; left: -50px;
            width: 150px; height: 150px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }
        .icon-wrap {
            position: relative; z-index: 1;
            width: 84px; height: 84px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
        }
        .login-logo {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 2.1rem;
            color: white;
            position: relative; z-index: 1;
            margin-bottom: 8px;
        }
        .login-logo span { color: #F2A93B; }
        .login-tagline {
            font-size: 0.88rem;
            opacity: 0.82; line-height: 1.7;
            position: relative; z-index: 1;
            margin-bottom: 28px;
        }
        .feature-list { list-style: none; padding: 0; position: relative; z-index: 1; text-align: left; width: 100%; }
        .feature-list li {
            font-size: 0.82rem; font-weight: 600; opacity: 0.92;
            display: flex; align-items: center; gap: 8px; padding: 5px 0;
        }
        .feature-list li i {
            width: 22px; height: 22px;
            background: rgba(255,255,255,0.2);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem;
        }

        .login-right {
            flex: 1;
            background: white;
            padding: 52px 44px;
            display: flex; flex-direction: column; justify-content: center;
        }

        .logout-banner {
            background: #DDF4F0;
            border: 1.5px solid #0E8C7F;
            border-radius: 10px;
            padding: 12px 16px; margin-bottom: 22px;
            display: flex; align-items: center; gap: 10px;
        }
        .logout-banner i { color: #0E8C7F; font-size: 1.1rem; }
        .logout-banner span { font-size: 0.85rem; font-weight: 700; color: #0E8C7F; }

        .login-right h2 { font-family: 'Poppins', sans-serif; font-size: 1.6rem; font-weight: 800; color: #14305C; margin-bottom: 4px; }
        .login-subtitle { font-size: 0.88rem; color: #94A3B8; margin-bottom: 28px; }

        .input-wrap { position: relative; margin-bottom: 16px; }
        .input-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #0E8C7F; font-size: 0.9rem; transition: color 0.3s; z-index: 1;
        }
        .input-wrap input {
            width: 100%;
            padding: 13px 44px 13px 40px;
            border: 1.5px solid #DCE5F4;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 0.92rem;
            transition: all 0.3s;
            background: #F7FAFC;
            color: #14305C;
        }
        .input-wrap input:focus {
            border-color: #0E8C7F;
            box-shadow: 0 0 0 4px rgba(14,140,127,0.12);
            outline: none;
            background: white;
        }
        .input-wrap input::placeholder { color: #B8C4D6; }
        .toggle-pw {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #B8C4D6; cursor: pointer;
            font-size: 0.9rem; padding: 0; transition: color 0.2s;
        }
        .toggle-pw:hover { color: #0E8C7F; }

        .error-msg {
            background: #FFF0F0; color: #DC2626;
            border: 1.5px solid #FECACA;
            border-radius: 10px; padding: 10px 14px;
            font-size: 0.85rem; font-weight: 700; margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #14305C 0%, #0E8C7F 100%);
            color: white; border: none;
            padding: 14px; border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 1rem; font-weight: 800;
            cursor: pointer; transition: all 0.3s;
            box-shadow: 0 6px 18px rgba(20,48,92,0.35);
            margin-top: 6px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(20,48,92,0.45); }
        .btn-login.loading { pointer-events: none; opacity: 0.8; }

        .spinner {
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: white; border-radius: 50%;
            animation: spin 0.7s linear infinite; display: none;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .login-footer { margin-top: 20px; text-align: center; font-size: 0.75rem; color: #CBD5E1; }
        .login-hint { margin-top: 14px; text-align: center; font-size: 0.74rem; color: #94A3B8; background:#F7FAFC; border-radius:8px; padding:8px 10px; }

        @media (max-width: 600px) {
            .login-left { display: none; }
            .login-right { padding: 36px 24px; }
        }
    </style>
</head>
<body>

<div class="grid-bg"></div>
<div class="glow"></div>
<div class="glow2"></div>

<div class="login-wrapper">
    <div class="login-left">
        <div class="icon-wrap">
            <svg width="72" height="72" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="64" height="64" rx="14" fill="rgba(255,255,255,0.15)"/>
                <rect x="14" y="10" width="24" height="32" rx="3" fill="white" fill-opacity="0.95"/>
                <rect x="18" y="16" width="16" height="3" rx="1.5" fill="#14305C"/>
                <rect x="18" y="22" width="16" height="3" rx="1.5" fill="#0E8C7F"/>
                <rect x="18" y="28" width="10" height="3" rx="1.5" fill="#14305C"/>
                <rect x="36" y="20" width="14" height="30" rx="3" fill="#F2A93B"/>
                <circle cx="43" cy="27" r="3" fill="white"/>
                <rect x="40.5" y="34" width="5" height="10" rx="1" fill="white" fill-opacity="0.85"/>
            </svg>
        </div>
        <div class="login-logo">Office<span>Stock</span></div>
        <p class="login-tagline">
            Sistem Pengadaan Alat Tulis Kantor (ATK)<br>
            Kelola pengadaan barang kantor dengan rapi 🗂️
        </p>
        <ul class="feature-list">
            <li><i class="fas fa-check"></i> Kelola data pengadaan ATK (CRUD)</li>
            <li><i class="fas fa-check"></i> Upload foto / bukti barang</li>
            <li><i class="fas fa-check"></i> Cetak laporan pengadaan ke PDF</li>
            <li><i class="fas fa-check"></i> Akses aman dengan login</li>
        </ul>
    </div>

    <div class="login-right">

        <?php if (isset($_GET['logout'])): ?>
        <div class="logout-banner">
            <i class="fas fa-check-circle"></i>
            <span>Anda berhasil logout dari sistem.</span>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
        <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
        <?php endif; ?>

        <h2>Selamat Datang</h2>
        <p class="login-subtitle">Masuk ke Sistem Pengadaan ATK</p>

        <form action="login/proses_login.php" method="post" id="loginForm">
            <div class="input-wrap">
                <i class="fas fa-user input-icon"></i>
                <input type="text" name="username" placeholder="Username" required autocomplete="username">
            </div>
            <div class="input-wrap">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="password" id="pwInput" placeholder="Password" required autocomplete="current-password">
                <button type="button" class="toggle-pw" id="togglePw" onclick="togglePassword()">
                    <i class="fas fa-eye" id="pwIcon"></i>
                </button>
            </div>
            <button type="submit" class="btn-login" id="btnLogin">
                <span id="btnText"><i class="fas fa-sign-in-alt"></i> Masuk ke Dashboard</span>
                <div class="spinner" id="spinner"></div>
            </button>
        </form>


        <p class="login-footer">© 2026 OfficeStock — UAS Pemrograman Web 2</p>
    </div>
</div>

<script>
function togglePassword() {
    const inp  = document.getElementById('pwInput');
    const icon = document.getElementById('pwIcon');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

document.getElementById('loginForm').addEventListener('submit', function() {
    const btn     = document.getElementById('btnLogin');
    const txt     = document.getElementById('btnText');
    const spinner = document.getElementById('spinner');
    btn.classList.add('loading');
    txt.style.display = 'none';
    spinner.style.display = 'block';
});
</script>

</body>
</html>
