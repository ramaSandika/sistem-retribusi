<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\RealisasiRetribusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealisasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();

        $tahun = $request->input('tahun', 2026);
        $opd = $request->input('opd');
        $search = $request->input('search');
        $periode = $request->input('periode');

        $query = RealisasiRetribusi::query();

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        if (!$isAdmin) {
            $query->where('opd_name', $user->opd_name);
        } elseif ($opd && $opd !== 'Semua OPD') {
            $query->where('opd_name', $opd);
        }

        if ($periode && $periode !== 'Semua Periode') {
            $query->where('periode', 'like', "%{$periode}%");
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_rekening', 'like', "%{$search}%")
                  ->orWhere('nama_retribusi', 'like', "%{$search}%")
                  ->orWhere('opd_name', 'like', "%{$search}%");
            });
        }

        $totalNilai = (clone $query)->sum('nilai');
        $totalRecord = (clone $query)->count();

        $data = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $opdList = [
            'Dinas Perhubungan',
            'Dinas Perdagangan',
            'Dinas Perkim',
            'Dinas Lingkungan Hidup',
            'Dinas Kesehatan',
        ];

        return view('realisasi.index', compact(
            'data',
            'user',
            'isAdmin',
            'tahun',
            'opd',
            'search',
            'periode',
            'totalNilai',
            'totalRecord',
            'opdList'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_rekening' => 'required|string',
            'nama_retribusi' => 'required|string',
            'periode' => 'nullable|string',
            'tahun' => 'nullable|integer',
            'nilai' => 'required|numeric',
            'foto_bukti' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $item = RealisasiRetribusi::findOrFail($id);
        $user = Auth::user();

        $oldNilai = $item->nilai;
        $fotoBuktiName = $item->foto_bukti;

        if ($request->hasFile('foto_bukti')) {
            $fotoPath = public_path('uploads/foto_bukti');
            if (!file_exists($fotoPath)) {
                mkdir($fotoPath, 0755, true);
            }
            $file = $request->file('foto_bukti');
            $newFotoName = 'foto_' . time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move($fotoPath, $newFotoName);

            // Clean up old file if not used elsewhere
            if ($fotoBuktiName && file_exists($fotoPath . '/' . $fotoBuktiName)) {
                $usedElsewhere = RealisasiRetribusi::where('foto_bukti', $fotoBuktiName)->where('id', '!=', $id)->exists();
                if (!$usedElsewhere) {
                    @unlink($fotoPath . '/' . $fotoBuktiName);
                }
            }
            $fotoBuktiName = $newFotoName;
        }

        $item->update([
            'kode_rekening' => $request->kode_rekening,
            'nama_retribusi' => $request->nama_retribusi,
            'periode' => $request->filled('periode') ? $request->periode : $item->periode,
            'tahun' => $request->filled('tahun') ? (int)$request->tahun : $item->tahun,
            'nilai' => $request->nilai,
            'foto_bukti' => $fotoBuktiName,
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => 'UPDATE_DATA',
            'details' => "Memperbarui data realisasi ID {$item->id} ({$item->kode_rekening}): Rp " . number_format($oldNilai, 0, ',', '.') . " -> Rp " . number_format($request->nilai, 0, ',', '.'),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Data realisasi retribusi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = RealisasiRetribusi::findOrFail($id);
        $user = Auth::user();

        // Delete physical photo file if not referenced elsewhere
        if ($item->foto_bukti) {
            $fotoPath = public_path('uploads/foto_bukti/' . $item->foto_bukti);
            $usedElsewhere = RealisasiRetribusi::where('foto_bukti', $item->foto_bukti)->where('id', '!=', $id)->exists();
            if (!$usedElsewhere && file_exists($fotoPath)) {
                @unlink($fotoPath);
            }
        }

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => 'DELETE_DATA',
            'details' => "Menghapus item realisasi {$item->kode_rekening} ({$item->nama_retribusi}) senilai Rp " . number_format($item->nilai, 0, ',', '.'),
            'ip_address' => request()->ip(),
        ]);

        $item->delete();

        return redirect()->back()->with('success', 'Data realisasi retribusi & berkas fisik berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:realisasi_retribusis,id',
        ]);

        $user = Auth::user();
        $items = RealisasiRetribusi::whereIn('id', $request->selected_ids)->get();

        $count = $items->count();
        $totalNilai = $items->sum('nilai');

        foreach ($items as $item) {
            if ($item->foto_bukti) {
                $fotoPath = public_path('uploads/foto_bukti/' . $item->foto_bukti);
                $usedElsewhere = RealisasiRetribusi::where('foto_bukti', $item->foto_bukti)
                                                    ->whereNotIn('id', $request->selected_ids)
                                                    ->exists();
                if (!$usedElsewhere && file_exists($fotoPath)) {
                    @unlink($fotoPath);
                }
            }
            $item->delete();
        }

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => 'BULK_DELETE',
            'details' => "Menghapus massal {$count} data realisasi retribusi senilai total Rp " . number_format($totalNilai, 0, ',', '.'),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', "{$count} data realisasi retribusi berhasil dihapus secara permanen.");
    }

    public function printReport(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();

        $tahun = $request->input('tahun', 2026);
        $opd = $request->input('opd');

        $query = RealisasiRetribusi::where('tahun', $tahun);

        if (!$isAdmin) {
            $query->where('opd_name', $user->opd_name);
        } elseif ($opd && $opd !== 'Semua OPD') {
            $query->where('opd_name', $opd);
        }

        $records = $query->orderBy('opd_name')->get();
        $totalNilai = $records->sum('nilai');

        return view('realisasi.print', compact('records', 'totalNilai', 'tahun', 'opd', 'user'));
    }

    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();

        $tahun = $request->input('tahun', 2026);
        $opd = $request->input('opd');
        $search = $request->input('search');

        $query = RealisasiRetribusi::query();

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        if (!$isAdmin) {
            $query->where('opd_name', $user->opd_name);
        } elseif ($opd && $opd !== 'Semua OPD') {
            $query->where('opd_name', $opd);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_rekening', 'like', "%{$search}%")
                  ->orWhere('nama_retribusi', 'like', "%{$search}%")
                  ->orWhere('opd_name', 'like', "%{$search}%");
            });
        }

        $records = $query->orderBy('opd_name')->get();
        $totalNilai = $records->sum('nilai');

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => 'EXPORT_EXCEL',
            'details' => "Mengekspor data realisasi retribusi tahun {$tahun} ke format Excel terstruktur",
            'ip_address' => $request->ip(),
        ]);

        $filename = "Laporan_Realisasi_Retribusi_{$tahun}_" . date('Ymd_His') . ".xls";

        $headers = [
            "Content-Type" => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Expires" => "0"
        ];

        $html = view('realisasi.excel', compact('records', 'totalNilai', 'tahun', 'opd', 'user'))->render();

        return response($html, 200, $headers);
    }
}
