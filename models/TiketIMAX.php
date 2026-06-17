<?php
// ============================================================
// TAHAP 4 – Inheritance | 
// File: models/TiketIMAX.php
// ============================================================

require_once __DIR__ . '/Tiket.php';

class TiketIMAX extends Tiket
{
    private string $kacamata3dId;
    private bool   $efekGerakFitur;

    public function __construct(
        int    $id_tiket,
        string $nama_film,
        string $jadwal_tayang,
        int    $jumlah_kursi,
        float  $hargaDasarTiket,
        string $kacamata3dId   = '-',
        bool   $efekGerakFitur = false
    ) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket);
        $this->kacamata3dId   = $kacamata3dId;
        $this->efekGerakFitur = $efekGerakFitur;
    }

    // TAHAP 5: Override → (jumlah_kursi * hargaDasarTiket) + 35.000
    public function hitungTotalHarga(): float
    {
        return ($this->jumlah_kursi * $this->hargaDasarTiket) + 35000;
    }

    public function tampilkanInfoFasilitas(): string
    {
        $efek = $this->efekGerakFitur ? '✅ Aktif' : '❌ Tidak';
        return "ID Kacamata 3D: <strong>{$this->kacamata3dId}</strong> | "
             . "Efek Gerak (4DX): <strong>{$efek}</strong>";
    }
}