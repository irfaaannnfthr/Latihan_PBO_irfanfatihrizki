<?php
// ============================================================
// TAHAP 6 – View dengan PHP
// File: index.php
// ============================================================

require_once __DIR__ . '/Koneksi/koneksi.php';
require_once __DIR__ . '/models/Tiket.php';
require_once __DIR__ . '/models/TiketRegular.php';
require_once __DIR__ . '/models/TiketIMAX.php';
require_once __DIR__ . '/models/TiketVelvet.php';

$conn = getConnection();

// Ambil semua data dari tabel_tiket, dikelompokkan per jenis studio
$sql    = "SELECT * FROM tabel_tiket ORDER BY jenis_studio, id_tiket";
$result = $conn->query($sql);

// Kelompokkan objek per studio
$tiketPerStudio = ['Regular' => [], 'IMAX' => [], 'Velvet' => []];

while ($row = $result->fetch_assoc()) {
    switch ($row['jenis_studio']) {
        case 'Regular':
            $tiketPerStudio['Regular'][] = new TiketRegular(
                (int)   $row['id_tiket'],
                        $row['nama_film'],
                        $row['jadwal_tayang'],
                (int)   $row['jumlah_kursi'],
                (float) $row['harga_dasar_tiket'],
                        $row['tipe_audio']   ?? '-',
                        $row['lokasi_baris'] ?? '-'
            );
            break;

        case 'IMAX':
            $tiketPerStudio['IMAX'][] = new TiketIMAX(
                (int)   $row['id_tiket'],
                        $row['nama_film'],
                        $row['jadwal_tayang'],
                (int)   $row['jumlah_kursi'],
                (float) $row['harga_dasar_tiket'],
                        $row['kacamata_3d_id']    ?? '-',
                (bool)  $row['efek_gerak_fitur']
            );
            break;

        case 'Velvet':
            $tiketPerStudio['Velvet'][] = new TiketVelvet(
                (int)   $row['id_tiket'],
                        $row['nama_film'],
                        $row['jadwal_tayang'],
                (int)   $row['jumlah_kursi'],
                (float) $row['harga_dasar_tiket'],
                (bool)  $row['bantal_selimut_pack'],
                (bool)  $row['layanan_butler']
            );
            break;
    }
}

$conn->close();

// Helper format rupiah
function rupiah(float $angka): string {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Konfigurasi warna & Ikon
$studioConfig = [
    'Regular' => [
        'icon'  => 'ph-film-strip', 
        'warna' => '#3b82f6', 
        'glow'  => 'rgba(59, 130, 246, 0.6)',
        'desc'  => 'Standard Digital Cinema Experience'
    ],
    'IMAX'    => [
        'icon'  => 'ph-video-camera', 
        'warna' => '#10b981', 
        'glow'  => 'rgba(16, 185, 129, 0.6)',
        'desc'  => 'Immersive Giant Screen & Audio'
    ],
    'Velvet'  => [
        'icon'  => 'ph-crown', 
        'warna' => '#8b5cf6', 
        'glow'  => 'rgba(139, 92, 246, 0.6)',
        'desc'  => 'Premium Luxury Bed & Butler Service'
    ],
];
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinemaOS - Admin Dashboard</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        /* ============================================================
           CSS VARIABLES & THEMING
           ============================================================ */
        :root {
            /* Tema Gelap (Default) */
            --bg-grad-1: #070b14;
            --bg-grad-2: #13101c;
            --bg-grad-3: #050a12;
            --glass-bg: rgba(20, 25, 35, 0.4);
            --glass-panel: rgba(20, 25, 35, 0.6);
            --glass-border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --row-hover: rgba(255, 255, 255, 0.04);
            --accent: #8b5cf6;
            --accent-glow: rgba(139, 92, 246, 0.5);
            --grid-color: rgba(255, 255, 255, 0.02);
            --sidebar-width: 280px;
        }

        [data-theme="light"] {
            /* Tema Terang */
            --bg-grad-1: #f8fafc;
            --bg-grad-2: #e2e8f0;
            --bg-grad-3: #ffffff;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-panel: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(0, 0, 0, 0.08);
            --text-main: #0f172a;
            --text-muted: #475569;
            --row-hover: rgba(0, 0, 0, 0.03);
            --accent: #6d28d9;
            --accent-glow: rgba(109, 40, 217, 0.3);
            --grid-color: rgba(0, 0, 0, 0.03);
        }

        /* ============================================================
           BASE & ANIMATED BACKGROUND
           ============================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--text-main);
            background: linear-gradient(-45deg, var(--bg-grad-1), var(--bg-grad-2), var(--bg-grad-3));
            background-size: 400% 400%;
            animation: gradientBG 20s ease infinite;
            height: 100vh;
            overflow: hidden; /* Mencegah scroll pada body, scroll hanya di konten */
            display: flex;
            transition: color 0.5s ease;
            position: relative;
        }

        /* Tech Grid Pattern Overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background-image: 
                linear-gradient(to right, var(--grid-color) 1px, transparent 1px),
                linear-gradient(to bottom, var(--grid-color) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Custom Scrollbar untuk area konten */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--glass-border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* ============================================================
           LAYOUT: SIDEBAR
           ============================================================ */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--glass-panel);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-right: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: transform 0.3s ease;
        }

        .brand {
            padding: 30px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--glass-border);
        }
        .brand i { font-size: 2.2rem; color: var(--accent); filter: drop-shadow(0 0 10px var(--accent-glow)); }
        .brand h2 { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.5px; }

        .nav-menu {
            flex-grow: 1;
            padding: 25px 15px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            overflow-y: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .nav-item i { font-size: 1.4rem; }
        .nav-item:hover { background: var(--row-hover); color: var(--text-main); }
        .nav-item.active {
            background: rgba(139, 92, 246, 0.1);
            color: var(--accent);
            border: 1px solid rgba(139, 92, 246, 0.2);
            box-shadow: inset 0 0 15px rgba(139, 92, 246, 0.05);
        }

        .user-profile {
            padding: 20px 25px;
            border-top: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .avatar {
            width: 45px; height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #3b82f6);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: #fff; font-size: 1.2rem;
            box-shadow: 0 4px 10px var(--accent-glow);
        }
        .user-info h4 { font-size: 0.95rem; font-weight: 600; margin-bottom: 2px;}
        .user-info p { font-size: 0.75rem; color: var(--text-muted); }

        /* ============================================================
           LAYOUT: MAIN CONTENT & NAVBAR
           ============================================================ */
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            z-index: 10;
        }

        .navbar {
            height: 85px;
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--glass-panel);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            z-index: 40;
        }

        .nav-left { display: flex; align-items: center; gap: 20px; }
        .page-title { font-size: 1.4rem; font-weight: 600; }
        .menu-toggle { display: none; font-size: 1.8rem; cursor: pointer; color: var(--text-main); background: none; border: none; }

        .nav-right { display: flex; align-items: center; gap: 20px; }

        .search-bar {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            padding: 10px 20px;
            border-radius: 30px;
            width: 300px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }
        .search-bar i { color: var(--text-muted); font-size: 1.2rem; }
        .search-bar:focus-within { width: 350px; border-color: var(--accent); box-shadow: 0 0 15px var(--accent-glow); }
        .search-bar input { border: none; background: transparent; outline: none; width: 100%; color: var(--text-main); font-family: inherit; }
        
        .icon-btn {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            width: 45px; height: 45px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-main); font-size: 1.3rem;
            transition: all 0.3s ease;
        }
        .icon-btn:hover { background: var(--row-hover); transform: translateY(-2px); }

        .content-area {
            flex-grow: 1;
            padding: 40px;
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        /* ============================================================
           CARDS & TABLES (Glassmorphism)
           ============================================================ */
        .header-banner {
            margin-bottom: 40px;
            padding: 30px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(59, 130, 246, 0.05));
            border: 1px solid var(--glass-border);
            display: flex; justify-content: space-between; align-items: center;
        }
        .header-banner h1 { font-size: 2rem; margin-bottom: 8px;}
        .header-banner p { color: var(--text-muted); }

        .studio-section {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            margin-bottom: 40px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            animation: fadeIn 0.6s ease forwards;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .studio-header {
            padding: 25px 30px;
            display: flex; align-items: center; gap: 20px;
            border-bottom: 1px solid var(--glass-border);
            position: relative; overflow: hidden;
        }

        .studio-icon-wrapper {
            width: 50px; height: 50px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; color: #fff; z-index: 1;
        }

        .studio-title-area h2 { font-size: 1.4rem; font-weight: 700; margin-bottom: 4px; }
        .studio-title-area p { color: var(--text-muted); font-size: 0.85rem; }
        
        .badge {
            margin-left: auto; background: rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.05); border-radius: 30px;
            padding: 6px 16px; font-size: 0.85rem; font-weight: 600;
            color: #fff; display: flex; align-items: center; gap: 8px; z-index: 1;
        }

        .tbl-wrap { padding: 10px 20px 20px; overflow-x: auto; }
        table { width: 100%; border-collapse: separate; border-spacing: 0 8px; font-size: 0.9rem; }

        thead th { padding: 12px 20px; text-align: left; color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }
        tbody tr { transition: all 0.3s ease; }
        tbody td {
            padding: 16px 20px; background: var(--row-hover);
            border-top: 1px solid transparent; border-bottom: 1px solid transparent;
        }
        tbody td:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; border-left: 1px solid transparent; }
        tbody td:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; border-right: 1px solid transparent; }
        
        tbody tr:hover td { background: rgba(255,255,255,0.06); border-color: var(--glass-border); }
        tbody tr:hover { transform: translateY(-2px); }

        .col-id { font-family: monospace; font-size: 1.05rem; color: var(--text-muted); }
        .film-name { font-weight: 600; font-size: 1.05rem; }
        .jadwal-tanggal { display: block; font-weight: 600; }
        .jadwal-jam { font-size: 0.75rem; color: var(--text-muted); }
        .total-harga { font-weight: 700; font-size: 1.1rem; text-align: right; }

        /* ============================================================
           RESPONSIVE DESIGN (MOBILE)
           ============================================================ */
        @media (max-width: 1024px) {
            .sidebar { position: fixed; left: -100%; }
            .sidebar.active { left: 0; box-shadow: 20px 0 50px rgba(0,0,0,0.5); }
            .menu-toggle { display: block; }
            .search-bar { width: 200px; }
            .search-bar:focus-within { width: 250px; }
        }
        @media (max-width: 768px) {
            .navbar { padding: 0 20px; }
            .content-area { padding: 20px; }
            .search-bar { display: none; } /* Hide search on mobile header to save space */
            .header-banner { flex-direction: column; align-items: flex-start; gap: 15px; }
        }
    </style>
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="brand">
        <i class="ph-fill ph-popcorn"></i>
        <h2>CinemaOS</h2>
    </div>
    
    <nav class="nav-menu">
        <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin: 10px 0 5px 15px;">Menu Utama</span>
        <a href="#" class="nav-item active"><i class="ph ph-squares-four"></i> Dashboard</a>
        <a href="#" class="nav-item"><i class="ph ph-ticket"></i> Transaksi Tiket</a>
        <a href="#" class="nav-item"><i class="ph ph-armchair"></i> Kelola Studio</a>
        <a href="#" class="nav-item"><i class="ph ph-film-slate"></i> Daftar Film</a>
        
        <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin: 20px 0 5px 15px;">Sistem</span>
        <a href="#" class="nav-item"><i class="ph ph-chart-line-up"></i> Laporan</a>
        <a href="#" class="nav-item"><i class="ph ph-gear"></i> Pengaturan</a>
    </nav>

    <div class="user-profile">
        <div class="avatar">AD</div>
        <div class="user-info">
            <h4>Admin Bioskop</h4>
            <p>Superadmin Mode</p>
        </div>
        <i class="ph ph-sign-out" style="margin-left: auto; color: var(--text-muted); cursor: pointer; font-size: 1.2rem;"></i>
    </div>
</aside>

<div class="main-wrapper">
    
    <nav class="navbar">
        <div class="nav-left">
            <button class="menu-toggle" id="menuToggle"><i class="ph ph-list"></i></button>
            <div class="page-title">Dashboard Overview</div>
        </div>
        
        <div class="nav-right">
            <div class="search-bar">
                <i class="ph ph-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Cari judul film..." onkeyup="filterTabel()">
            </div>
            
            <button class="icon-btn"><i class="ph ph-bell"></i></button>
            <button class="icon-btn" id="themeToggle"><i class="ph-fill ph-moon" id="themeIcon"></i></button>
        </div>
    </nav>

    <main class="content-area" id="contentArea">
        
        <div class="header-banner glass-element">
            <div>
                <h1>Selamat Datang, Admin 👋</h1>
                <p>Berikut adalah ringkasan seluruh tiket yang terjual di berbagai dimensi studio.</p>
            </div>
            <button style="background: var(--accent); color: white; border: none; padding: 12px 24px; border-radius: 12px; font-family: inherit; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px var(--accent-glow);">
                <i class="ph ph-plus"></i> Tiket Baru
            </button>
        </div>

        <?php foreach ($studioConfig as $jenis => $cfg): ?>
        <?php $tikets = $tiketPerStudio[$jenis]; ?>

        <section class="studio-section" id="section-<?= strtolower($jenis) ?>">
            <div class="studio-header">
                <div style="position:absolute; top:0; left:0; right:0; bottom:0; background:<?= $cfg['warna'] ?>; opacity:0.05; z-index:0;"></div>
                
                <div class="studio-icon-wrapper" style="background: linear-gradient(135deg, <?= $cfg['warna'] ?>, #000);">
                    <i class="ph-fill <?= $cfg['icon'] ?>"></i>
                </div>
                
                <div class="studio-title-area">
                    <h2 style="color: <?= $cfg['warna'] ?>; text-shadow: 0 0 10px <?= $cfg['glow'] ?>;">Studio <?= $jenis ?></h2>
                    <p><?= $cfg['desc'] ?></p>
                </div>
                
                <span class="badge" id="count-<?= strtolower($jenis) ?>">
                    <i class="ph-fill ph-ticket"></i> <?= count($tikets) ?> Tiket
                </span>
            </div>

            <div class="tbl-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Judul Film</th>
                            <th>Waktu Tayang</th>
                            <th style="text-align:center">Kursi</th>
                            <th>Harga Dasar</th>
                            <th>Fasilitas</th>
                            <th style="text-align:right">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($tikets)): ?>
                        <tr>
                            <td colspan="7" style="text-align:center; color:var(--text-muted); padding:40px; background: transparent;">
                                <i class="ph-duotone ph-ghost" style="font-size: 2.5rem; opacity: 0.5; margin-bottom: 10px;"></i><br>
                                <em>Belum ada tiket terjual.</em>
                            </td>
                        </tr>
                    <?php else: ?>
                    <?php foreach ($tikets as $tiket): ?>
                        <tr class="data-row">
                            <td class="col-id"><i class="ph ph-barcode"></i> <?= str_pad($tiket->getIdTiket(), 4, '0', STR_PAD_LEFT) ?></td>
                            <td class="film-name"><?= htmlspecialchars($tiket->getNamaFilm()) ?></td>
                            <td>
                                <span class="jadwal-tanggal"><?= date('d M Y', strtotime($tiket->getJadwalTayang())) ?></span>
                                <span class="jadwal-jam"><i class="ph ph-clock"></i> <?= date('H:i', strtotime($tiket->getJadwalTayang())) ?> WIB</span>
                            </td>
                            <td style="text-align:center;">
                                <span style="background:var(--bg-grad-1); padding:4px 12px; border-radius:8px; border:1px solid var(--glass-border); font-weight:600; font-size:0.85rem;">
                                    <?= $tiket->getJumlahKursi() ?> Pax
                                </span>
                            </td>
                            <td style="color:var(--text-muted); font-size:0.85rem;"><?= rupiah($tiket->getHargaDasarTiket()) ?></td>

                            <td style="font-size:0.8rem; color:var(--text-muted); line-height: 1.5;">
                                <i class="ph-fill ph-check-circle" style="color: <?= $cfg['warna'] ?>;"></i> <?= $tiket->tampilkanInfoFasilitas() ?>
                            </td>

                            <td class="total-harga" style="color:<?= $cfg['warna'] ?>; text-shadow: 0 0 10px <?= $cfg['glow'] ?>;">
                                <?= rupiah($tiket->hitungTotalHarga()) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <?php endforeach; ?>
        
        <footer style="text-align: center; color: var(--text-muted); font-size: 0.85rem; margin-top: 40px; padding-bottom: 20px;">
            &copy; 2026 CinemaOS. Sistem PBO PHP Terpadu.
        </footer>
        
    </main>
</div>

<script>
    // --- FITUR SIDEBAR MOBILE TOGGLE ---
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

    // Tutup sidebar jika klik di luar (area main content) pada layar kecil
    document.getElementById('contentArea').addEventListener('click', () => {
        if(window.innerWidth <= 1024 && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
        }
    });

    // --- FITUR DARK/LIGHT MODE ---
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const htmlEl = document.documentElement;
    
    if(localStorage.getItem('theme') === 'light') {
        htmlEl.setAttribute('data-theme', 'light');
        themeIcon.className = 'ph-fill ph-sun';
        themeIcon.style.color = '#f59e0b';
    }

    themeToggle.addEventListener('click', () => {
        let currentTheme = htmlEl.getAttribute('data-theme');
        if(currentTheme === 'dark') {
            htmlEl.setAttribute('data-theme', 'light');
            themeIcon.className = 'ph-fill ph-sun';
            themeIcon.style.color = '#f59e0b';
            localStorage.setItem('theme', 'light');
        } else {
            htmlEl.setAttribute('data-theme', 'dark');
            themeIcon.className = 'ph-fill ph-moon';
            themeIcon.style.color = 'var(--text-main)'; 
            localStorage.setItem('theme', 'dark');
        }
    });

    // --- FITUR PENCARIAN (REAL-TIME FILTER) ---
    function filterTabel() {
        let input = document.getElementById("searchInput");
        let filter = input.value.toLowerCase();
        let sections = document.querySelectorAll(".studio-section");

        sections.forEach(section => {
            let trs = section.querySelectorAll("tbody tr.data-row");
            let visibleCount = 0;

            trs.forEach(tr => {
                let tdFilm = tr.getElementsByTagName("td")[1]; // Kolom Judul Film
                if (tdFilm) {
                    let txtValue = tdFilm.textContent || tdFilm.innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        tr.style.display = "";
                        visibleCount++;
                    } else {
                        tr.style.display = "none";
                    }
                }
            });

            // Update Angka di Badge
            let badge = section.querySelector(".badge");
            badge.innerHTML = `<i class="ph-fill ph-ticket"></i> ${visibleCount} Tiket`;
            
            // Tampilkan/Sembunyikan Section
            if(visibleCount === 0 && filter !== "") {
                section.style.display = "none";
            } else {
                section.style.display = "block";
            }
        });
    }
</script>

</body>
</html>