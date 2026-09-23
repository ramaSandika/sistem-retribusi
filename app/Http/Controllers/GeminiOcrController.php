<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiOcrController extends Controller
{
    /**
     * Tampilkan halaman antarmuka upload OCR.
     */
    public function index()
    {
        return view('ocr-test');
    }

    /**
     * Proses file gambar dan kirim ke Google Gemini API (gemini-1.5-flash).
     */
    public function process(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpeg,png,jpg,webp,pdf|max:20480',
        ], [
            'image.required' => 'Silakan pilih berkas dokumen atau gambar terlebih dahulu.',
            'image.file' => 'Berkas yang diunggah tidak valid.',
            'image.mimes' => 'Format yang didukung: PDF, JPG, JPEG, PNG, WEBP.',
            'image.max' => 'Ukuran berkas maksimal adalah 20 MB.',
        ]);

        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey) || $apiKey === 'your_gemini_api_key_here') {
            return back()->with('error', 'API Key Google Gemini belum diatur di file .env (GEMINI_API_KEY).');
        }

        try {
            $file = $request->file('image');
            $mimeType = $file->getMimeType();
            $base64Image = base64_encode(file_get_contents($file->getRealPath()));

            // Data URI untuk preview gambar di view (jika bukan PDF)
            $isPdf = str_contains($mimeType, 'pdf');
            $imagePreview = $isPdf ? null : ('data:' . $mimeType . ';base64,' . $base64Image);

            // Daftar model yang didukung secara berurutan jika salah satu sedang sibuk (high demand / 503)
            $candidateModels = array_unique(array_filter([
                env('GEMINI_MODEL'),
                'gemini-3.5-flash',
                'gemini-3.6-flash',
                'gemini-3.5-flash-lite',
                'gemini-3.7-flash',
            ]));

            $promptText = "Ekstrak ringkasan tabel dan data penting dari dokumen/berkas ini ke dalam format JSON objek sederhana.";

            $response = null;
            $lastErrorMessage = 'Gagal menghubungi Google Gemini API.';

            foreach ($candidateModels as $model) {
                $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

                try {
                    $res = Http::withHeaders([
                        'Content-Type' => 'application/json',
                    ])->timeout(120)->connectTimeout(15)->post($endpoint, [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => $promptText
                                    ],
                                    [
                                        'inline_data' => [
                                            'mime_type' => $mimeType,
                                            'data' => $base64Image,
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.1,
                            'maxOutputTokens' => 4096,
                        ]
                    ]);

                    if ($res->successful()) {
                        $response = $res;
                        break;
                    } else {
                        $errData = $res->json();
                        $lastErrorMessage = $errData['error']['message'] ?? ('HTTP ' . $res->status());
                        Log::warning("Gemini model {$model} gagal: {$lastErrorMessage}. Mencoba model cadangan berikutnya...");
                    }
                } catch (\Illuminate\Http\Client\ConnectionException $ce) {
                    $lastErrorMessage = "Timeout saat menghubungi model {$model}.";
                    Log::warning("Gemini model {$model} timeout: " . $ce->getMessage());
                    continue;
                } catch (\Exception $ex) {
                    $lastErrorMessage = $ex->getMessage();
                    Log::warning("Gemini model {$model} error: " . $ex->getMessage());
                    continue;
                }
            }

            if (!$response) {
                return back()->with('error', 'Semua server AI Gemini saat ini sedang sibuk (High Demand). Silakan coba 1-2 menit lagi: ' . $lastErrorMessage)->with('imagePreview', $imagePreview);
            }

            $resultData = $response->json();
            $rawText = $resultData['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // Bersihkan markdown code fence jika Gemini mengembalikan ```json ... ```
            $cleanJsonString = trim($rawText);
            if (str_starts_with($cleanJsonString, '```')) {
                $cleanJsonString = preg_replace('/^```(?:json)?\s*/i', '', $cleanJsonString);
                $cleanJsonString = preg_replace('/\s*```$/', '', $cleanJsonString);
            }

            // Uji apakah output adalah JSON valid
            $parsedJson = json_decode($cleanJsonString, true);

            return view('ocr-test', [
                'rawResult' => $rawText,
                'jsonResult' => $parsedJson,
                'isJson' => !is_null($parsedJson),
                'imagePreview' => $imagePreview,
                'isPdf' => $isPdf,
                'originalFilename' => $file->getClientOriginalName(),
            ])->with('success', 'Dokumen/Berkas berhasil diproses dan diekstrak oleh Google Gemini AI!');

        } catch (\Exception $e) {
            Log::error('Gemini OCR Exception: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
