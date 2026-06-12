<?php
// Memanggil class induk
require_once "Tiket.php";

class TiketIMAX extends Tiket {
    
    // 1. Properti khusus (spesifik) untuk studio IMAX
    private $kacamata3D;
    private $efekGerakFitur;

    // 2. Constructor untuk menerima data
    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket, $kacamata3D, $efekGerakFitur) {
        
        // Membawa data global ke constructor class induk (Tiket)
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket);
        
        // Mengisi data spesifik untuk studio IMAX
        $this->kacamata3D = $kacamata3D;
        $this->efekGerakFitur = $efekGerakFitur;
    }

    // 3. Wajib: Membuat isi dari method abstrak hitungTotalHarga()
    public function hitungTotalHarga() {
        // Rumus: Harga dasar dikali jumlah kursi
        return $this->hargaDasarTiket * $this->jumlah_kursi;
    }

    // 4. Wajib: Membuat isi dari method abstrak tampilkanInfoFasilitas()
    public function tampilkanInfoFasilitas() {
        // Logika tambahan jika data efek gerak di database bernilai kosong/NULL
        $efek = $this->efekGerakFitur ? $this->efekGerakFitur : "Tidak ada";
        
        return "Studio IMAX | Kacamata 3D ID: {$this->kacamata3D} | Efek Gerak: {$efek}";
    }
}

?>