<?php

namespace App\Exports;

use App\Models\RealisasiRetribusi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RealisasiExport implements FromQuery, WithHeadings, WithMapping
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        /** @var User $user */
        $user = Auth::user() ?? \App\Models\User::first();
        $query = RealisasiRetribusi::query()->with('user');

        if ($user && !$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        if ($this->request->filled('tahun')) {
            $query->where('tahun', $this->request->tahun);
        }
        if ($this->request->filled('periode')) {
            $query->where('periode', $this->request->periode);
        }
        if ($this->request->filled('search')) {
            $s = $this->request->search;
            $query->where(function ($q) use ($s) {
                $q->where('kode_rekening', 'like', "%{$s}%")
                  ->orWhere('nama_retribusi', 'like', "%{$s}%");
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Rekening',
            'Uraian / Nama Retribusi',
            'Target Anggaran (Rp)',
            'Nilai Realisasi (Rp)',
            'Capaian (%)',
            'Realisasi Tahun Lalu (Rp)',
            'Periode',
            'Tahun Anggaran',
            'OPD / Instansi',
        ];
    }

    public function map(mixed $row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $row->kode_rekening,
            $row->nama_retribusi,
            $row->anggaran,
            $row->nilai,
            $row->persentase,
            $row->realisasi_lalu,
            $row->periode,
            $row->tahun,
            $row->user->unit_opd ?? $row->user->name ?? '-',
        ];
    }
}