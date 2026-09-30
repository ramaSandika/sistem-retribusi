<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadRetribusi;
use App\Models\RealisasiRetribusi;
use App\Services\GeminiOcrService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller {
    protected GeminiOcrService $geminiOcrService;

    public function __construct(GeminiOcrService $geminiOcrService) {
        $this->geminiOcrService = $geminiOcrService;
    }

    public function index() {
        return redirect()->route('upload.create');
    }

    public function create() {
        $user = Auth::user() ?? \App\Models\User::first();
        $recentUploads = UploadRetribusi::latest()->take(5)->get();

        return view('upload.create', compact('recentUploads'));
    }

    public function store(Request $request) {
        set_time_limit(300); // 5 menit untuk parsing dokumen APBD tebal
        $request->validate([
            'file_pdf' => 'required|mimes:pdf|max:30720', // Maks 30MB untuk PDF lampiran APBD
            'tahun' => 'required|digits:4',
            'periode' => 'required|string',
            'unit_opd' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user() ?? \App\Models\User::first();
        $opd = $request->unit_opd ?: ($user->unit_opd ?? 'BAPENDA / OPD Terkait');

        // Deteksi Duplikasi
        $exists = UploadRetribusi::where('tahun', $request->tahun)
                    ->where('periode', $request->periode)
                    ->when(!$user->isAdmin(), function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->where('status', 'Success')
                    ->exists();

        if ($exists && !$request->has('force_replace')) {
            return back()->withInput()->with('error', "Dokumen realisasi untuk periode {$request->periode} Tahun {$request->tahun} sudah pernah diupload dan diverifikasi. Centang 'Timpa data' jika ingin memperbarui.");
        }

        $path = $request->file('file_pdf')->store('uploads/retribusi');
        $fullPath = storage_path('app/' . $path);
        if (!file_exists($fullPath)) {
            $fullPath = storage_path('app/private/' . $path);
        }

        $upload = UploadRetribusi::create([
            'user_id' => $user->id,
            'tahun' => $request->tahun,
            'periode' => $request->periode,
            'unit_opd' => $opd,
            'keterangan' => $request->keterangan,
            'file_path' => $path,
            'status' => 'Processing',
        ]);

        try {
            // Panggil Gemini Multimodal OCR
            $parsedData = $this->geminiOcrService->extractRetribusiFromPdf($fullPath);

            if (empty($parsedData)) {
                $upload->update(['status' => 'Failed']);
                return back()->with('error', 'Gemini AI tidak mendeteksi pos akun 4.1.02 (Retribusi Daerah) pada file ini. Pastikan file PDF mencakup halaman retribusi.');
            }

            // Simpan hasil ke session untuk preview & edit
            session(['parsed_data_' . $upload->id => $parsedData]);

            return redirect()->route('upload.preview', $upload->id)->with('success', 'Gemini AI berhasil mengekstrak ' . count($parsedData) . ' baris rekening retribusi. Silakan validasi data di bawah.');
        } catch (\Exception $e) {
            $upload->update(['status' => 'Failed']);
            return back()->withInput()->with('error', 'Gagal memproses OCR Gemini AI: ' . $e->getMessage());
        }
    }

    public function preview($id) {
        $upload = UploadRetribusi::findOrFail($id);
        $parsedData = session('parsed_data_' . $id, []);

        if (empty($parsedData)) {
            // Jika session hilang, coba ambil data yang sudah tersimpan jika status success
            if ($upload->status === 'Success') {
                $parsedData = $upload->details()->get()->toArray();
            }
        }

        return view('upload.preview', compact('upload', 'parsedData'));
    }

    public function confirm(Request $request, $id) {
        $upload = UploadRetribusi::findOrFail($id);
        
        // Ambil data dari form input (editable table), atau fallback ke session
        $items = $request->input('items', []);

        if (empty($items)) {
            $items = session('parsed_data_' . $id, []);
        }

        if (empty($items)) {
            return redirect()->route('upload.create')->with('error', 'Data validasi tidak ditemukan atau telah kedaluwarsa. Silakan upload ulang dokumen.');
        }

        // Hapus detail lama jika upload ini sebelumnya sudah punya detail (mode replace)
        $upload->details()->delete();

        foreach ($items as $data) {
            $kode = trim($data['kode_rekening'] ?? '');
            $nama = trim($data['nama_retribusi'] ?? '');
            if (empty($kode) || empty($nama)) {
                continue;
            }

            $anggaran = (float) str_replace([',', ' '], '', $data['anggaran'] ?? 0);
            $realisasi = (float) str_replace([',', ' '], '', $data['nilai'] ?? ($data['realisasi'] ?? 0));
            $realisasiLalu = (float) str_replace([',', ' '], '', $data['realisasi_lalu'] ?? 0);
            $persen = $anggaran > 0 ? round(($realisasi / $anggaran) * 100, 2) : (float) ($data['persentase'] ?? 0);

            RealisasiRetribusi::create([
                'upload_id' => $upload->id,
                'user_id' => $upload->user_id,
                'kode_rekening' => $kode,
                'nama_retribusi' => $nama,
                'anggaran' => $anggaran,
                'nilai' => $realisasi,
                'persentase' => $persen,
                'realisasi_lalu' => $realisasiLalu,
                'level_rekening' => $data['level_rekening'] ?? null,
                'periode' => $upload->periode,
                'tahun' => $upload->tahun,
            ]);
        }

        $upload->update(['status' => 'Success']);
        session()->forget('parsed_data_' . $id);

        return redirect()->route('retribusi.index')->with('success', 'Data realisasi retribusi BAPENDA berhasil diverifikasi dan disimpan ke database.');
    }
}