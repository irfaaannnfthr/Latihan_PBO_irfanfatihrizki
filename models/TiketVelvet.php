<?php
require_once "Tiket.php";

class TiketVelvet extends Tiket {
    private $bantalSelimutPack;
    private $layananButler;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket, $bantalSelimutPack, $layananButler) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket);
        $this->bantalSelimutPack = $bantalSelimutPack;
        $this->layananButler = $layananButler;
    }

    // TAHAP 5: Overriding Method
    public function hitungTotalHarga() {
        // Sesuai soal: (jumlah_kursi * hargaDasarTiket) * 1.50
        $totalHargaDasar = $this->jumlah_kursi * $this->hargaDasarTiket;
        return $totalHargaDasar * 1.50;
    }

    public function tampilkanInfoFasilitas() {
        return "Studio VELVET | Fasilitas: {$this->bantalSelimutPack} | Layanan Butler: {$this->layananButler}";
    }
}
?>