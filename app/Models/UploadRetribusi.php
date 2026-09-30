<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadRetribusi extends Model {
    protected $fillable = [
        'user_id',
        'tahun',
        'periode',
        'unit_opd',
        'keterangan',
        'file_path',
        'status',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function details() {
        return $this->hasMany(RealisasiRetribusi::class, 'upload_id');
    }
}