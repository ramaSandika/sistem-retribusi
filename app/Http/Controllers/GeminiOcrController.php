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

            $promptText = "Ekstrak semua informasi penting, tabel, angka, dan teks dari dokumen/gambar ini dan kembalikan dalam format JSON terstruktur yang rapi.";

            // Model Gemini (Gunakan gemini-3.6-flash terbaru yang didukung API key)
            $model = env('GEMINI_MODEL', 'gemini-3.6-flash');
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($endpoint, [
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
                    'temperature' => 0.2,
                ]
            ]);

            if ($response->failed()) {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? 'Gagal menghubungi Google Gemini API.';
                Log::error('Gemini OCR API Error: ' . json_encode($errorBody));
                return back()->with('error', 'Gemini API Error: ' . $errorMessage)->with('imagePreview', $imagePreview);
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
