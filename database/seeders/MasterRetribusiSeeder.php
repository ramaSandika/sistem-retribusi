<?php

namespace Database\Seeders;

use App\Models\MasterRetribusi;
use Illuminate\Database\Seeder;

class MasterRetribusiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode_rekening' => '4.1.01.09.01',
                'nama_retribusi' => 'Pajak Reklame Papan/Billboard/Videotron/ Megatron',
                'opd_name' => 'Badan Pendapatan Daerah',
                'kategori' => 'Pajak Daerah',
                'target_anggaran' => 1084000000,
            ],
            [
                'kode_rekening' => '4.1.01.09.02',
                'nama_retribusi' => 'Pajak Reklame Kain',
                'opd_name' => 'Badan Pendapatan Daerah',
                'kategori' => 'Pajak Daerah',
                'target_anggaran' => 388000000,
            ],
            [
                'kode_rekening' => '4.1.01.09.05',
                'nama_retribusi' => 'Pajak Reklame Berjalan',
                'opd_name' => 'Badan Pendapatan Daerah',
                'kategori' => 'Pajak Daerah',
                'target_anggaran' => 19500000,
            ],
            [
                'kode_rekening' => '4.1.01.12.01',
                'nama_retribusi' => 'Pajak Air Tanah',
                'opd_name' => 'Badan Pendapatan Daerah',
                'kategori' => 'Pajak Daerah',
                'target_anggaran' => 40000000,
            ],
            [
                'kode_rekening' => '4.1.01.13.01',
                'nama_retribusi' => 'Pajak Sarang Burung Walet',
                'opd_name' => 'Badan Pendapatan Daerah',
                'kategori' => 'Pajak Daerah',
                'target_anggaran' => 30000000,
            ],
            [
                'kode_rekening' => '4.1.01.14.12',
                'nama_retribusi' => 'Pajak Granit/Andesit',
                'opd_name' => 'Badan Pendapatan Daerah',
                'kategori' => 'Pajak Daerah',
                'target_anggaran' => 50000000,
            ],
            [
                'kode_rekening' => '4.1.01.15.01',
                'nama_retribusi' => 'PBB-P2 (Pajak Bumi dan Bangunan Perdesaan & Perkotaan)',
                'opd_name' => 'Badan Pendapatan Daerah',
                'kategori' => 'Pajak Daerah',
                'target_anggaran' => 13000000000,
            ],
            [
                'kode_rekening' => '4.1.01.16.01',
                'nama_retribusi' => 'BPHTB-Pemindahan Hak',
                'opd_name' => 'Badan Pendapatan Daerah',
                'kategori' => 'Pajak Daerah',
                'target_anggaran' => 30000000000,
            ],
            [
                'kode_rekening' => '4.1.01.19.01',
                'nama_retribusi' => 'PBJT-Restoran / Makanan dan Minuman',
                'opd_name' => 'Badan Pendapatan Daerah',
                'kategori' => 'PBJT',
                'target_anggaran' => 14600000000,
            ],
            [
                'kode_rekening' => '4.1.01.19.02',
                'nama_retribusi' => 'PBJT-Tenaga Listrik',
                'opd_name' => 'Badan Pendapatan Daerah',
                'kategori' => 'PBJT',
                'target_anggaran' => 19200000000,
            ],
            [
                'kode_rekening' => '4.1.01.19.03',
                'nama_retribusi' => 'PBJT-Jasa Perhotelan',
                'opd_name' => 'Badan Pendapatan Daerah',
                'kategori' => 'PBJT',
                'target_anggaran' => 9000000000,
            ],
            [
                'kode_rekening' => '4.1.01.19.04',
                'nama_retribusi' => 'PBJT-Jasa Parkir',
                'opd_name' => 'Dinas Perhubungan',
                'kategori' => 'PBJT',
                'target_anggaran' => 1000000000,
            ],
            [
                'kode_rekening' => '4.1.02.01.01',
                'nama_retribusi' => 'Retribusi Parkir Tepi Jalan Umum',
                'opd_name' => 'Dinas Perhubungan',
                'kategori' => 'Retribusi Jasa Umum',
                'target_anggaran' => 500000000,
            ],
            [
                'kode_rekening' => '4.1.02.01.02',
                'nama_retribusi' => 'Retribusi Pengujian Kendaraan Bermotor (Kir)',
                'opd_name' => 'Dinas Perhubungan',
                'kategori' => 'Retribusi Jasa Umum',
                'target_anggaran' => 200000000,
            ],
            [
                'kode_rekening' => '4.1.02.02.01',
                'nama_retribusi' => 'Retribusi Pelayanan Pasar Daerah',
                'opd_name' => 'Dinas Perdagangan',
                'kategori' => 'Retribusi Jasa Usaha',
                'target_anggaran' => 450000000,
            ],
            [
                'kode_rekening' => '4.1.02.03.01',
                'nama_retribusi' => 'Retribusi Persetujuan Bangunan Gedung (PBG)',
                'opd_name' => 'Dinas Perkim',
                'kategori' => 'Retribusi Perizinan Tertentu',
                'target_anggaran' => 800000000,
            ],
            [
                'kode_rekening' => '4.1.02.04.01',
                'nama_retribusi' => 'Retribusi Pelayanan Persampahan & Kebersihan',
                'opd_name' => 'Dinas Lingkungan Hidup',
                'kategori' => 'Retribusi Jasa Umum',
                'target_anggaran' => 350000000,
            ],
        ];

        foreach ($data as $item) {
            MasterRetribusi::updateOrCreate(
                ['kode_rekening' => $item['kode_rekening']],
                $item
            );
        }
    }
}
