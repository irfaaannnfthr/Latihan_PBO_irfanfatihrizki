<?php
// ============================================================
// TAHAP 4 – Inheritance | TAHAP 5 – Polymorphism Overriding
// File: models/TiketRegular.php
// ============================================================

require_once __DIR__ . '/Tiket.php';

class TiketRegular extends Tiket
{
    // Tahap 4: Properti tambahan
    private string $tipeAudio;
    private string $lokasiBaris;

    public function __construct(
        int    $id_tiket,
        string $nama_film,
        string $jadwal_tayang,
        int    $jumlah_kursi,
        float  $hargaDasarTiket,
        string $tipeAudio   = '-',
        string $lokasiBaris = '-'
    ) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket);
        $this->tipeAudio   = $tipeAudio;
        $this->lokasiBaris = $lokasiBaris;
    }

    // Tahap 5: Override hitungTotalHarga()
    // Rumus: jumlah_kursi * hargaDasarTiket (tarif standar murni)
    public function hitungTotalHarga(): float
    {
        return $this->jumlah_kursi * $this->hargaDasarTiket;
    }

    public function tampilkanInfoFasilitas(): string
    {
        return "Tipe Audio: <strong>{$this->tipeAudio}</strong> | "
             . "Lokasi Baris: <strong>{$this->lokasiBaris}</strong>";
    }
}