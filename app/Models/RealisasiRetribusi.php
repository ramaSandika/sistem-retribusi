<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealisasiRetribusi extends Model {
    protected $fillable = [
        'upload_id',
        'user_id',
        'kode_rekening',
        'nama_retribusi',
        'anggaran',
        'nilai',
        'persentase',
        'realisasi_lalu',
        'level_rekening',
        'periode',
        'tahun',
    ];

    public function upload() {
        return $this->belongsTo(UploadRetribusi::class, 'upload_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}