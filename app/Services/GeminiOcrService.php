<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiOcrService {
    protected string $apiKey;
    protected array $fallbackModels;

    public function __construct() {
        $this->apiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY', '');
        // Prioritas model yang terbukti aktif di akun Anda
        $this->fallbackModels = [
            'gemini-3.5-flash',
            'gemini-3.1-flash-lite',
            'gemini-3.8-flash'
        ];
    }

    /**
     * Ekstraksi dokumen APBD khusus rekening 4.1.02 (Retribusi Daerah).
     * Menggunakan Gemini AI Multimodal asli dengan visual pemindaian tabel akurat.
     *
     * @param string $pdfFilePath Lokasi file PDF di storage
     * @return array
     * @throws \Exception
     */
    public function extractRetribusiFromPdf(string $pdfFilePath): array {
        if (empty($this->apiKey)) {
            throw new \Exception("GEMINI_API_KEY belum dikonfigurasi di file .env");
        }

        if (!file_exists($pdfFilePath)) {
            throw new \Exception("File PDF tidak ditemukan: " . $pdfFilePath);
        }

        $pdfBytes = file_get_contents($pdfFilePath);
        $base64Pdf = base64_encode($pdfBytes);

        $prompt = <<<PROMPT
Kamu ahli akuntansi pemerintah daerah Indonesia. Dari PDF laporan realisasi APBD ini, ekstrak HANYA rekening kode 4.1.02 (Retribusi Daerah) beserta semua sub-rinciannya.

ATURAN:
- Ambil semua baris dengan kode rekening awalan "4.1.02" (termasuk 4.1.02.01, 4.1.02.02, 4.1.02.03 dan sub-rinciannya)
- ABAIKAN rekening lain (4.1.01 Pajak, 4.1.03, 4.1.04, belanja 5.x, pembiayaan 6.x)
- Nilai rupiah: "850.000.000,00" → 850000000 | "42,67" → 42.67 | strip/kosong → 0
- level_rekening: 4.1.02=kelompok, 4.1.02.01=jenis, 4.1.02.01.x=objek, lebih dalam=rincian

Output JSON saja, tanpa teks lain:
{"retribusi":[{"kode_rekening":"4.1.02.01","nama_retribusi":"...","anggaran":0,"realisasi":0,"persentase":0,"realisasi_lalu":0,"level_rekening":"jenis"}]}
PROMPT;

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => 'application/pdf',
                                'data' => $base64Pdf
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.0,
                'response_mime_type' => 'application/json'
            ]
        ];

        return $this->callGeminiApi($payload);
    }

    /**
     * Eksekutor pemanggilan API dengan Multi-Model Fallback
     */
    protected function callGeminiApi(array $payload): array {
        $lastError = '';

        foreach ($this->fallbackModels as $model) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->apiKey}";

            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json'
                ])->timeout(240)->post($url, $payload);

                if ($response->successful()) {
                    $rawText = $response->json('candidates.0.content.parts.0.text') ?? '';
                    if (!empty($rawText)) {
                        return $this->parseResponseJson($rawText);
                    }
                }

                $status = $response->status();
                $msg = $response->json('error.message') ?? $response->body();
                $lastError = "Model {$model} [HTTP {$status}]: {$msg}";
                Log::warning("Gemini API failover: {$lastError}");
            } catch (\Throwable $e) {
                $lastError = "Exception on {$model}: " . $e->getMessage();
                Log::warning($lastError);
            }
        }

        throw new \Exception("Gagal memproses AI OCR: " . $lastError);
    }

    /**
     * Parsing dan pembersihan hasil JSON
     */
    protected function parseResponseJson(string $rawText): array {
        $decoded = json_decode($rawText, true);

        if (!is_array($decoded)) {
            $cleaned = preg_replace('/^```(?:json)?\s*/i', '', trim($rawText));
            $cleaned = preg_replace('/```$/i', '', $cleaned);
            $decoded = json_decode($cleaned, true);
        }

        if (!is_array($decoded)) {
            Log::error('Gagal decode JSON dari Gemini: ' . $rawText);
            throw new \Exception("Format respons dari AI tidak valid sebagai JSON data.");
        }

        $items = $decoded['retribusi'] ?? ($decoded['data'] ?? $decoded);

        if (!is_array($items)) {
            return [];
        }

        $validItems = [];
        foreach ($items as $item) {
            $kode = trim($item['kode_rekening'] ?? '');
            if (str_starts_with($kode, '4.1.02')) {
                $anggaran = (float) ($item['anggaran'] ?? 0);
                $realisasi = (float) ($item['realisasi'] ?? 0);
                $persen = (float) ($item['persentase'] ?? 0);

                if ($persen == 0 && $anggaran > 0) {
                    $persen = round(($realisasi / $anggaran) * 100, 2);
                }

                $validItems[] = [
                    'kode_rekening' => $kode,
                    'nama_retribusi' => trim($item['nama_retribusi'] ?? ''),
                    'anggaran' => $anggaran,
                    'nilai' => $realisasi,
                    'realisasi' => $realisasi,
                    'persentase' => $persen,
                    'realisasi_lalu' => (float) ($item['realisasi_lalu'] ?? 0),
                    'level_rekening' => $item['level_rekening'] ?? $this->detectLevel($kode),
                ];
            }
        }

        return $validItems;
    }

    protected function detectLevel(string $kode): string {
        $parts = explode('.', $kode);
        $count = count($parts);
        if ($count <= 3) return 'kelompok';
        if ($count == 4) return 'jenis';
        if ($count == 5) return 'objek';
        return 'rincian';
    }
}
