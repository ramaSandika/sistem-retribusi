<?php

namespace App\Http\Controllers;

use App\Models\RealisasiRetribusi;
use App\Models\UploadRetribusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {
    public function index(Request $request) {
        /** @var \App\Models\User $user */
        $user = Auth::user() ?? \App\Models\User::first();
        
        $tahun = $request->get('tahun', date('Y'));
        $periode = $request->get('periode');

        $query = RealisasiRetribusi::query()->where('tahun', $tahun);
        $uploadQuery = UploadRetribusi::query()->where('tahun', $tahun);

        if ($periode) {
            $query->where('periode', $periode);
            $uploadQuery->where('periode', $periode);
        }

        // Hitung total hanya untuk rincian objek agar tidak double counting dengan baris induk
        $rincianQuery = (clone $query)->where(function ($q) {
            $q->whereNull('level_rekening')
              ->orWhereNotIn('level_rekening', ['kelompok', 'jenis', 'objek']);
        });

        $totalAnggaran = (clone $rincianQuery)->sum('anggaran');
        $totalRealisasi = (clone $rincianQuery)->sum('nilai');
        $totalLalu = (clone $rincianQuery)->sum('realisasi_lalu');
        $persenCapaian = $totalAnggaran > 0 ? round(($totalRealisasi / $totalAnggaran) * 100, 2) : 0;
        $totalFile = $uploadQuery->count();

        // Data grafik perkembangan realisasi per periode
        $grafikData = (clone $rincianQuery)
            ->selectRaw('periode, sum(anggaran) as total_anggaran, sum(nilai) as total_realisasi')
            ->groupBy('periode')
            ->get();

        // 5 Dokumen upload terbaru
        $latestUploads = $uploadQuery->with('user')->latest()->take(5)->get();

        // Kategori retribusi utama (4.1.02.01 Jasa Umum, 4.1.02.02 Jasa Usaha, 4.1.02.03 Perizinan)
        $kategoriStat = [
            'jasa_umum' => (clone $rincianQuery)->where('kode_rekening', 'like', '4.1.02.01%')->sum('nilai'),
            'jasa_usaha' => (clone $rincianQuery)->where('kode_rekening', 'like', '4.1.02.02%')->sum('nilai'),
            'perizinan' => (clone $rincianQuery)->where('kode_rekening', 'like', '4.1.02.03%')->sum('nilai'),
        ];

        return view('dashboard.index', compact(
            'totalAnggaran',
            'totalRealisasi',
            'persenCapaian',
            'totalLalu',
            'totalFile',
            'grafikData',
            'latestUploads',
            'kategoriStat',
            'tahun',
            'periode'
        ));
    }
}