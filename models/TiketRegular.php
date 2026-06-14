<?php
require_once "Tiket.php";

class TiketRegular extends Tiket {
    private $tipeAudio;
    private $lokasiBaris;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket, $tipeAudio, $lokasiBaris) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket);
        $this->tipeAudio = $tipeAudio;
        $this->lokasiBaris = $lokasiBaris;
    }

    // TAHAP 5: Overriding Method
    public function hitungTotalHarga() {
        // Sesuai soal: jumlah_kursi * hargaDasarTiket (tanpa biaya tambahan)
        return $this->jumlah_kursi * $this->hargaDasarTiket;
    }

    public function tampilkanInfoFasilitas() {
        return "Studio Regular | Audio: {$this->tipeAudio} | Baris: {$this->lokasiBaris}";
    }
}
?>