<?php
// Memanggil class induk
require_once "Tiket.php";

class TiketRegular extends Tiket {
    
    // 1. Properti khusus (spesifik) untuk studio Regular
    private $tipeAudio;
    private $lokasiBaris;

    // 2. Constructor untuk menerima data
    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket, $tipeAudio, $lokasiBaris) {
        
        // Membawa data global ke constructor class induk (Tiket)
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket);
        
        // Mengisi data spesifik untuk class ini
        $this->tipeAudio = $tipeAudio;
        $this->lokasiBaris = $lokasiBaris;
    }

    // 3. Wajib: Membuat isi (body) dari method abstrak hitungTotalHarga()
    public function hitungTotalHarga() {
        // Rumus sederhana: Harga dasar dikali jumlah kursi
        $total = $this->hargaDasarTiket * $this->jumlah_kursi;
        return $total;
    }

    // 4. Wajib: Membuat isi (body) dari method abstrak tampilkanInfoFasilitas()
    public function tampilkanInfoFasilitas() {
        return "Studio Regular | Audio: {$this->tipeAudio} | Baris: {$this->lokasiBaris}";
    }
}

?>