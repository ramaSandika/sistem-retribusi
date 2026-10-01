<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadRetribusi;
use App\Models\RealisasiRetribusi;
use App\Services\GeminiOcrService;
use App\Jobs\ProcessOcrJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        $recentUploads = UploadRetribusi::latest()->take(5)->get();
        return view('upload.create', compact('recentUploads'));
    }

    /**
     * Store: simpan file, langsung dispatch background job, redirect ke halaman loading
     */
    public function store(Request $request) {
        $request->validate([
            'file_pdf'  => 'required|mimes:pdf|max:30720',
            'tahun'     => 'required|digits:4',
            'periode'   => 'required|string',
            'unit_opd'  => 'nullable|string|max:255',
            'keterangan'=> 'nullable|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user() ?? \App\Models\User::first();
        $opd  = $request->unit_opd ?: ($user->unit_opd ?? 'BAPENDA / OPD Terkait');

        // Deteksi Duplikasi
        $exists = UploadRetribusi::where('tahun', $request->tahun)
                    ->where('periode', $request->periode)
                    ->where('status', 'Success')
                    ->exists();

        if ($exists && !$request->has('force_replace')) {
            return back()->withInput()->with('error',
                "Dokumen realisasi untuk periode {$request->periode} Tahun {$request->tahun} sudah ada. Centang 'Timpa data' jika ingin memperbarui."
            );
        }

        $path     = $request->file('file_pdf')->store('uploads/retribusi');
        $fullPath = storage_path('app/' . $path);
        if (!file_exists($fullPath)) {
            $fullPath = storage_path('app/private/' . $path);
        }

        $upload = UploadRetribusi::create([
            'user_id'    => $user->id,
            'tahun'      => $request->tahun,
            'periode'    => $request->periode,
            'unit_opd'   => $opd,
            'keterangan' => $request->keterangan,
            'file_path'  => $path,
            'status'     => 'Processing',
        ]);

        // Set status awal di cache
        Cache::put("ocr_status_{$upload->id}", [
            'status'  => 'processing',
            'message' => 'File diterima, AI sedang mempersiapkan analisis...',
        ], 600);

        // Dispatch ke background queue
        ProcessOcrJob::dispatch($upload->id, $fullPath);

        // Redirect langsung ke halaman loading — tidak perlu tunggu
        return redirect()->route('upload.processing', $upload->id)
            ->with('info', 'File berhasil diunggah! AI sedang memproses, mohon tunggu sebentar...');
    }

    /**
     * Halaman loading / progress saat OCR berjalan di background
     */
    public function processing($id) {
        $upload = UploadRetribusi::findOrFail($id);
        return view('upload.processing', compact('upload'));
    }

    /**
     * API endpoint: polling status OCR (dipanggil setiap 3 detik dari JS)
     */
    public function ocrStatus($id) {
        $upload = UploadRetribusi::findOrFail($id);
        $cached = Cache::get("ocr_status_{$id}", ['status' => 'processing', 'message' => 'Sedang memproses...']);

        // Jika cache hilang tapi upload sudah done/failed, sesuaikan
        if (!Cache::has("ocr_status_{$id}")) {
            if ($upload->status === 'Ready') {
                $cached = ['status' => 'done', 'message' => 'Selesai diproses'];
            } elseif ($upload->status === 'Failed') {
                $cached = ['status' => 'failed', 'message' => 'Proses OCR gagal'];
            }
        }

        return response()->json($cached);
    }

    /**
     * Preview halaman setelah OCR selesai — data diambil dari Cache
     */
    public function preview($id) {
        $upload = UploadRetribusi::findOrFail($id);

        // Coba dari Cache (hasil background job)
        $parsedData = Cache::get("ocr_data_{$id}", []);

        // Fallback ke session (backward compat)
        if (empty($parsedData)) {
            $parsedData = session('parsed_data_' . $id, []);
        }

        // Fallback ke data DB jika status Success
        if (empty($parsedData) && $upload->status === 'Success') {
            $parsedData = $upload->details()->get()->toArray();
        }

        return view('upload.preview', compact('upload', 'parsedData'));
    }

    /**
     * Confirm: simpan data dari preview ke database permanen
     */
    public function confirm(Request $request, $id) {
        $upload = UploadRetribusi::findOrFail($id);

        $items = $request->input('items', []);

        if (empty($items)) {
            $items = Cache::get("ocr_data_{$id}", []);
        }

        if (empty($items)) {
            $items = session('parsed_data_' . $id, []);
        }

        if (empty($items)) {
            return redirect()->route('upload.create')
                ->with('error', 'Data validasi tidak ditemukan atau telah kedaluwarsa. Silakan upload ulang dokumen.');
        }

        $upload->details()->delete();

        foreach ($items as $data) {
            $kode = trim($data['kode_rekening'] ?? '');
            $nama = trim($data['nama_retribusi'] ?? '');
            if (empty($kode) || empty($nama)) continue;

            $anggaran      = (float) str_replace([',', ' '], '', $data['anggaran'] ?? 0);
            $realisasi     = (float) str_replace([',', ' '], '', $data['nilai'] ?? ($data['realisasi'] ?? 0));
            $realisasiLalu = (float) str_replace([',', ' '], '', $data['realisasi_lalu'] ?? 0);
            $persen        = $anggaran > 0 ? round(($realisasi / $anggaran) * 100, 2) : (float) ($data['persentase'] ?? 0);

            RealisasiRetribusi::create([
                'upload_id'      => $upload->id,
                'user_id'        => $upload->user_id,
                'kode_rekening'  => $kode,
                'nama_retribusi' => $nama,
                'anggaran'       => $anggaran,
                'nilai'          => $realisasi,
                'persentase'     => $persen,
                'realisasi_lalu' => $realisasiLalu,
                'level_rekening' => $data['level_rekening'] ?? null,
                'periode'        => $upload->periode,
                'tahun'          => $upload->tahun,
            ]);
        }

        $upload->update(['status' => 'Success']);
        Cache::forget("ocr_data_{$id}");
        Cache::forget("ocr_status_{$id}");
        session()->forget('parsed_data_' . $id);

        return redirect()->route('retribusi.index')
            ->with('success', 'Data realisasi retribusi BAPENDA berhasil diverifikasi dan disimpan ke database.');
    }
}