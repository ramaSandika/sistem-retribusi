<?php

namespace App\Jobs;

use App\Models\UploadRetribusi;
use App\Services\GeminiOcrService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessOcrJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;
    public int $tries = 2;

    protected int $uploadId;
    protected string $fullPath;

    public function __construct(int $uploadId, string $fullPath)
    {
        $this->uploadId = $uploadId;
        $this->fullPath = $fullPath;
    }

    public function handle(GeminiOcrService $geminiOcrService): void
    {
        $upload = UploadRetribusi::find($this->uploadId);
        if (!$upload) return;

        try {
            // Update status ke Processing
            $upload->update(['status' => 'Processing']);
            Cache::put("ocr_status_{$this->uploadId}", ['status' => 'processing', 'message' => 'AI sedang membaca dokumen...'], 600);

            $parsedData = $geminiOcrService->extractRetribusiFromPdf($this->fullPath);

            if (empty($parsedData)) {
                $upload->update(['status' => 'Failed']);
                Cache::put("ocr_status_{$this->uploadId}", [
                    'status' => 'failed',
                    'message' => 'Gemini AI tidak mendeteksi pos 4.1.02 (Retribusi Daerah). Pastikan file PDF mencakup halaman retribusi.'
                ], 600);
                return;
            }

            // Simpan ke Cache (bukan session, karena di background)
            Cache::put("ocr_data_{$this->uploadId}", $parsedData, 3600);
            $upload->update(['status' => 'Ready']);

            Cache::put("ocr_status_{$this->uploadId}", [
                'status'  => 'done',
                'message' => 'Berhasil mengekstrak ' . count($parsedData) . ' baris rekening retribusi.',
                'count'   => count($parsedData),
            ], 600);

            Log::info("OCR selesai untuk upload #{$this->uploadId}: " . count($parsedData) . " baris");
        } catch (\Throwable $e) {
            $upload->update(['status' => 'Failed']);
            Cache::put("ocr_status_{$this->uploadId}", [
                'status'  => 'failed',
                'message' => 'Gagal OCR: ' . $e->getMessage(),
            ], 600);
            Log::error("OCR gagal upload #{$this->uploadId}: " . $e->getMessage());
        }
    }

    public function failed(\Throwable $e): void
    {
        $upload = UploadRetribusi::find($this->uploadId);
        if ($upload) $upload->update(['status' => 'Failed']);
        Cache::put("ocr_status_{$this->uploadId}", [
            'status'  => 'failed',
            'message' => 'Job gagal: ' . $e->getMessage(),
        ], 600);
    }
}
