<?php

namespace App\Http\Controllers;

use App\Models\RealisasiRetribusi;
use App\Exports\RealisasiExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RealisasiController extends Controller {
    public function index(Request $request) {
        /** @var \App\Models\User $user */
        $user = Auth::user() ?? \App\Models\User::first();
        $query = RealisasiRetribusi::query()->with('user');

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('kode_rekening', 'like', "%{$s}%")
                  ->orWhere('nama_retribusi', 'like', "%{$s}%");
            });
        }

        $data = $query->paginate(50);
        return view('realisasi.index', compact('data'));
    }

    /**
     * Simpan perubahan edit massal (keseluruhan tabel) langsung dari halaman Data Realisasi
     */
    public function batchUpdate(Request $request) {
        $items = $request->input('items', []);

        foreach ($items as $id => $val) {
            $record = RealisasiRetribusi::find($id);
            if ($record) {
                $anggaran = (float) str_replace([',', ' '], '', $val['anggaran'] ?? 0);
                $realisasi = (float) str_replace([',', ' '], '', $val['nilai'] ?? 0);
                $realisasiLalu = (float) str_replace([',', ' '], '', $val['realisasi_lalu'] ?? 0);
                $persen = $anggaran > 0 ? round(($realisasi / $anggaran) * 100, 2) : 0;

                $record->update([
                    'kode_rekening' => trim($val['kode_rekening'] ?? $record->kode_rekening),
                    'nama_retribusi' => trim($val['nama_retribusi'] ?? $record->nama_retribusi),
                    'anggaran' => $anggaran,
                    'nilai' => $realisasi,
                    'persentase' => $persen,
                    'realisasi_lalu' => $realisasiLalu,
                ]);
            }
        }

        return back()->with('success', 'Perubahan data realisasi retribusi berhasil disimpan!');
    }

    /**
     * Rename / Edit data spesifik per baris
     */
    public function update(Request $request, $id) {
        $record = RealisasiRetribusi::findOrFail($id);

        $validated = $request->validate([
            'kode_rekening' => 'required|string|max:50',
            'nama_retribusi' => 'required|string|max:255',
            'anggaran' => 'required|numeric',
            'nilai' => 'required|numeric',
            'realisasi_lalu' => 'nullable|numeric',
        ]);

        $anggaran = (float) $validated['anggaran'];
        $realisasi = (float) $validated['nilai'];
        $persen = $anggaran > 0 ? round(($realisasi / $anggaran) * 100, 2) : 0;

        $record->update([
            'kode_rekening' => trim($validated['kode_rekening']),
            'nama_retribusi' => trim($validated['nama_retribusi']),
            'anggaran' => $anggaran,
            'nilai' => $realisasi,
            'persentase' => $persen,
            'realisasi_lalu' => (float) ($validated['realisasi_lalu'] ?? 0),
        ]);

        return back()->with('success', "Data retribusi '{$record->nama_retribusi}' ({$record->kode_rekening}) berhasil diperbarui / di-rename!");
    }

    /**
     * Hapus satu baris data
     */
    public function destroy($id) {
        $record = RealisasiRetribusi::findOrFail($id);
        $record->delete();

        return back()->with('success', 'Baris data retribusi berhasil dihapus.');
    }

    public function export(Request $request) {
        $namaFile = 'Rekap_Realisasi_Retribusi_' . ($request->tahun ?: date('Y')) . '_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new RealisasiExport($request), $namaFile);
    }
}