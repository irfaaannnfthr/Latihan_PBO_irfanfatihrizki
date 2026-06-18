<?php
// 1. Memanggil file Koneksi dan semua Model
require_once "Koneksi/Koneksi.php";
require_once "models/TiketRegular.php";
require_once "models/TiketIMAX.php";
require_once "models/TiketVelvet.php"

// 2. Membuat objek koneksi dan mengambil data dari database
$koneksiObj = new Koneksi();
$db = $koneksiObj->getKoneksi();

// MENYESUAIKAN: Nama tabel di database kamu adalah 'tabel_tiket'
$query = "SELECT * FROM tabel_tiket"; 
$stmt = $db->prepare($query);
$stmt->execute();
$dataTiket = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Menyiapkan array penampung untuk masing-masing studio
$listRegular = [];
$listIMAX = [];
$listVelvet = [];

// 4. Looping data dari database dan mengubahnya menjadi Object (Polimorfisme)
foreach ($dataTiket as $row) {
    // MENYESUAIKAN: Nama kolom kategori di database kamu adalah 'jenis_studio'
    $tipe = $row['jenis_studio']; 

    if ($tipe == 'Regular') {
        $listRegular[] = new TiketRegular(
            $row['id_tiket'], 
            $row['nama_film'], 
            $row['jadwal_tayang'], 
            $row['jumlah_kursi'], 
            $row['harga_dasar_tiket'], // MENYESUAIKAN: harga_dasar_tiket
            $row['tipe_audio'], 
            $row['lokasi_baris']
        );
    } elseif ($tipe == 'IMAX') {
        $listIMAX[] = new TiketIMAX(
            $row['id_tiket'], 
            $row['nama_film'], 
            $row['jadwal_tayang'], 
            $row['jumlah_kursi'], 
            $row['harga_dasar_tiket'], // MENYESUAIKAN: harga_dasar_tiket
            $row['kacamata_3d_id'],     // MENYESUAIKAN: kacamata_3d_id
            $row['efek_gerak_fitur_bantal_selimut_pack'] // MENYESUAIKAN: nama kolom pack digabung
        );
    } elseif ($tipe == 'VELVET' || $tipe == 'Velvet') { 
        $listVelvet[] = new TiketVelvet(
            $row['id_tiket'], 
            $row['nama_film'], 
            $row['jadwal_tayang'], 
            $row['jumlah_kursi'], 
            $row['harga_dasar_tiket'], // MENYESUAIKAN: harga_dasar_tiket
            $row['efek_gerak_fitur_bantal_selimut_pack'], // MENYESUAIKAN: nama kolom pack digabung
            $row['layanan_butler']
        );
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tiket Bioskop - Polimorfisme</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #212529;
            margin-bottom: 30px;
        }
        h2 {
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 5px;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        th {
            background-color: #0d6efd;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f1f3f5;
        }
        .fasilitas {
            font-style: italic;
            color: #6c757d;
        }
        .total-harga {
            font-weight: bold;
            color: #198754;
        }
        .empty-msg {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>

    <h1>Daftar Pemesanan Tiket Bioskop</h1>

    <!-- Tabel Studio Regular -->
    <h2>Studio Regular</h2>
    <?php if (empty($listRegular)): ?>
        <p class="empty-msg">Belum ada data pemesanan untuk Studio Regular.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID Tiket</th>
                <th>Nama Film</th>
                <th>Jadwal Tayang</th>
                <th>Jumlah Kursi</th>
                <th>Harga Dasar</th>
                <th>Total Harga</th>
                <th>Info & Fasilitas</th>
            </tr>
            <?php foreach ($listRegular as $tiket): ?>
            <tr>
                <td><?= htmlspecialchars($tiket->getIdTiket()) ?></td>
                <td><?= htmlspecialchars($tiket->getNamaFilm()) ?></td>
                <td><?= htmlspecialchars($tiket->getJadwalTayang()) ?></td>
                <td><?= htmlspecialchars($tiket->getJumlahKursi()) ?></td>
                <td>Rp <?= number_format($tiket->getHargaDasarTiket(), 0, ',', '.') ?></td>
                <td class="total-harga">Rp <?= number_format($tiket->hitungTotalHarga(), 0, ',', '.') ?></td>
                <td class="fasilitas"><?= htmlspecialchars($tiket->tampilkanInfoFasilitas()) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- Tabel Studio IMAX -->
    <h2>Studio IMAX</h2>
    <?php if (empty($listIMAX)): ?>
        <p class="empty-msg">Belum ada data pemesanan untuk Studio IMAX.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID Tiket</th>
                <th>Nama Film</th>
                <th>Jadwal Tayang</th>
                <th>Jumlah Kursi</th>
                <th>Harga Dasar</th>
                <th>Total Harga</th>
                <th>Info & Fasilitas</th>
            </tr>
            <?php foreach ($listIMAX as $tiket): ?>
            <tr>
                <td><?= htmlspecialchars($tiket->getIdTiket()) ?></td>
                <td><?= htmlspecialchars($tiket->getNamaFilm()) ?></td>
                <td><?= htmlspecialchars($tiket->getJadwalTayang()) ?></td>
                <td><?= htmlspecialchars($tiket->getJumlahKursi()) ?></td>
                <td>Rp <?= number_format($tiket->getHargaDasarTiket(), 0, ',', '.') ?></td>
                <td class="total-harga">Rp <?= number_format($tiket->hitungTotalHarga(), 0, ',', '.') ?></td>
                <td class="fasilitas"><?= htmlspecialchars($tiket->tampilkanInfoFasilitas()) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- Tabel Studio VELVET -->
    <h2>Studio VELVET</h2>
    <?php if (empty($listVelvet)): ?>
        <p class="empty-msg">Belum ada data pemesanan untuk Studio VELVET.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID Tiket</th>
                <th>Nama Film</th>
                <th>Jadwal Tayang</th>
                <th>Jumlah Kursi</th>
                <th>Harga Dasar</th>
                <th>Total Harga</th>
                <th>Info & Fasilitas</th>
            </tr>
            <?php foreach ($listVelvet as $tiket): ?>
            <tr>
                <td><?= htmlspecialchars($tiket->getIdTiket()) ?></td>
                <td><?= htmlspecialchars($tiket->getNamaFilm()) ?></td>
                <td><?= htmlspecialchars($tiket->getJadwalTayang()) ?></td>
                <td><?= htmlspecialchars($tiket->getJumlahKursi()) ?></td>
                <td>Rp <?= number_format($tiket->getHargaDasarTiket(), 0, ',', '.') ?></td>
                <td class="total-harga">Rp <?= number_format($tiket->hitungTotalHarga(), 0, ',', '.') ?></td>
                <td class="fasilitas"><?= htmlspecialchars($tiket->tampilkanInfoFasilitas()) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</body>
</html>