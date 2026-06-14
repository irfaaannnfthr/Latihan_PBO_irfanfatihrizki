<?php
require_once "Tiket.php";

class TiketIMAX extends Tiket {
    private $kacamata3D;
    private $efekGerakFitur;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket, $kacamata3D, $efekGerakFitur) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket);
        $this->kacamata3D = $kacamata3D;
        $this->efekGerakFitur = $efekGerakFitur;
    }

    // TAHAP 5: Overriding Method
    public function hitungTotalHarga() {
        // Sesuai soal: (jumlah_kursi * hargaDasarTiket) + 35000
        $totalHargaDasar = $this->jumlah_kursi * $this->hargaDasarTiket;
        return $totalHargaDasar + 35000;
    }

    public function tampilkanInfoFasilitas() {
        $efek = $this->efekGerakFitur ? $this->efekGerakFitur : "Tidak ada";
        return "Studio IMAX | Kacamata 3D ID: {$this->kacamata3D} | Efek Gerak: {$efek}";
    }
}
?>