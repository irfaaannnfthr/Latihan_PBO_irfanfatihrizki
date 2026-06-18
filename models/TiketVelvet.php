<?php
// ============================================================
// TAHAP 4 – Inheritance | TAHAP 5 – Polymorphism Overriding
// File: models/TiketVelvet.php
// ============================================================

require_once __DIR__ . '/Tiket.php';

class TiketVelvet extends Tiket
{
    // Tahap 4: Properti tambahan
    private bool $bantalSelimutPack;
    private bool $layananButler;

    public function __construct(
        int    $id_tiket,
        string $nama_film,
        string $jadwal_tayang,
        int    $jumlah_kursi,
        float  $hargaDasarTiket,
        bool   $bantalSelimutPack = false,
        bool   $layananButler     = false
    ) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket);
        $this->bantalSelimutPack = $bantalSelimutPack;
        $this->layananButler     = $layananButler;
    }

    // Tahap 5: Override hitungTotalHarga()
    // Rumus: (jumlah_kursi * hargaDasarTiket) * 1.50
    public function hitungTotalHarga(): float
    {
        return ($this->jumlah_kursi * $this->hargaDasarTiket) * 1.50;
    }

    public function tampilkanInfoFasilitas(): string
    {
        $bantal = $this->bantalSelimutPack ? '✅ Tersedia' : '❌ Tidak';
        $butler = $this->layananButler     ? '✅ Tersedia' : '❌ Tidak';
        return "Bantal & Selimut: <strong>{$bantal}</strong> | "
             . "Layanan Butler: <strong>{$butler}</strong>";
    }
}