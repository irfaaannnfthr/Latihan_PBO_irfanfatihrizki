<?php
// Memanggil class induk
require_once "Tiket.php";

class TiketVelvet extends Tiket {
    
    // 1. Properti khusus (spesifik) untuk studio Velvet
    private $bantalSelimutPack;
    private $layananButler;

    // 2. Constructor untuk menerima data
    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket, $bantalSelimutPack, $layananButler) {
        
        // Membawa data global ke constructor class induk (Tiket)
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket);
        
        // Mengisi data spesifik untuk studio Velvet
        $this->bantalSelimutPack = $bantalSelimutPack;
        $this->layananButler = $layananButler;
    }

    // 3. Wajib: Membuat isi dari method abstrak hitungTotalHarga()
    public function hitungTotalHarga() {
        // Rumus: Harga dasar dikali jumlah kursi
        return $this->hargaDasarTiket * $this->jumlah_kursi;
    }

    // 4. Wajib: Membuat isi dari method abstrak tampilkanInfoFasilitas()
    public function tampilkanInfoFasilitas() {
        return "Studio VELVET | Fasilitas: {$this->bantalSelimutPack} | Layanan Butler: {$this->layananButler}";
    }
}

?>