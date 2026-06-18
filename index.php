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

// Konfigurasi warna per studio
$studioConfig = [
    'Regular' => ['emoji' => '🎬', 'warna' => '#2563eb', 'bg' => '#eff6ff'],
    'IMAX'    => ['emoji' => '🎥', 'warna' => '#16a34a', 'bg' => '#f0fdf4'],
    'Velvet'  => ['emoji' => '👑', 'warna' => '#9333ea', 'bg' => '#faf5ff'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Tiket Bioskop</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        header {
            background: linear-gradient(135deg, #1e293b, #334155);
            color: #fff;
            padding: 30px;
            text-align: center;
        }
        header h1 { font-size: 1.8rem; }
        header p  { margin-top: 6px; color: #cbd5e1; font-size: .9rem; }

        main { max-width: 1100px; margin: 32px auto; padding: 0 20px 60px; }

        /* Studio Section */
        .studio-section {
            background: #fff;
            border-radius: 12px;
            margin-bottom: 32px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
        }

        .studio-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 24px;
            color: #fff;
        }
        .studio-header h2  { font-size: 1.2rem; }
        .studio-header .badge {
            margin-left: auto;
            background: rgba(255,255,255,.25);
            border-radius: 20px;
            padding: 4px 14px;
            font-size: .8rem;
            font-weight: 600;
        }

        /* Table */
        .tbl-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: .88rem; }

        thead th {
            background: #f8fafc;
            padding: 12px 14px;
            text-align: left;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #f8fafc; }

        .total-harga { font-weight: 700; font-size: .95rem; }
        .fasilitas   { font-size: .82rem; line-height: 1.7; }
        .harga-dasar { color: #64748b; font-size: .82rem; }

        footer {
            text-align: center;
            color: #94a3b8;
            font-size: .8rem;
            padding-bottom: 20px;
        }
    </style>
</head>
<body>

<header>
    <h1>🎦 Sistem Manajemen Tiket Bioskop</h1>
    <p>Praktikum Pemrograman Berorientasi Objek (PBO) – PHP OOP</p>
</header>

<main>

<?php foreach ($studioConfig as $jenis => $cfg): ?>
<?php $tikets = $tiketPerStudio[$jenis]; ?>

<section class="studio-section">

    <!-- Header Studio -->
    <div class="studio-header" style="background:<?= $cfg['warna'] ?>;">
        <span style="font-size:1.5rem"><?= $cfg['emoji'] ?></span>
        <h2>Studio <?= $jenis ?></h2>
        <span class="badge"><?= count($tikets) ?> Tiket</span>
    </div>

    <!-- Tabel Data -->
    <div class="tbl-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Film</th>
                    <th>Jadwal Tayang</th>
                    <th>Kursi</th>
                    <th>Harga Dasar/Kursi</th>
                    <th>Fasilitas Studio</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($tikets)): ?>
                <tr>
                    <td colspan="7" style="text-align:center;color:#94a3b8;padding:24px;">
                        Tidak ada data tiket.
                    </td>
                </tr>
            <?php else: ?>
            <?php foreach ($tikets as $tiket): ?>
                <tr>
                    <td><?= $tiket->getIdTiket() ?></td>
                    <td><strong><?= htmlspecialchars($tiket->getNamaFilm()) ?></strong></td>
                    <td><?= date('d M Y, H:i', strtotime($tiket->getJadwalTayang())) ?></td>
                    <td style="text-align:center"><?= $tiket->getJumlahKursi() ?></td>
                    <td class="harga-dasar"><?= rupiah($tiket->getHargaDasarTiket()) ?></td>

                    <!-- Polymorphism: tampilkanInfoFasilitas() -->
                    <td class="fasilitas"><?= $tiket->tampilkanInfoFasilitas() ?></td>

                    <!-- Polymorphism: hitungTotalHarga() -->
                    <td class="total-harga" style="color:<?= $cfg['warna'] ?>">
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

</main>

<footer>
    <p>Simulasi UAS Praktikum PBO &bull; Sistem Tiket Bioskop &bull; PHP OOP</p>
</footer>

</body>
</html>