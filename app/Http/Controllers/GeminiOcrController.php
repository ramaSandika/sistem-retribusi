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
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
        ], [
            'image.required' => 'Silakan pilih gambar terlebih dahulu.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format gambar yang didukung: JPG, JPEG, PNG, WEBP.',
            'image.max' => 'Ukuran gambar maksimal adalah 10 MB.',
        ]);

        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey) || $apiKey === 'your_gemini_api_key_here') {
            return back()->with('error', 'API Key Google Gemini belum diatur di file .env (GEMINI_API_KEY).');
        }

        try {
            $file = $request->file('image');
            $mimeType = $file->getMimeType();
            $base64Image = base64_encode(file_get_contents($file->getRealPath()));

            // Data URI untuk preview gambar di view
            $imagePreview = 'data:' . $mimeType . ';base64,' . $base64Image;

            $promptText = "Ekstrak teks dari gambar ini dan kembalikan dalam format terstruktur JSON.";

            // Endpoint Gemini 1.5 Flash
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

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
                'originalFilename' => $file->getClientOriginalName(),
            ])->with('success', 'Gambar berhasil diproses oleh Gemini 1.5 Flash!');

        } catch (\Exception $e) {
            Log::error('Gemini OCR Exception: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
