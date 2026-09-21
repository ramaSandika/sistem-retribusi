<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterRetribusi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_rekening',
        'nama_retribusi',
        'opd_name',
        'kategori',
        'target_anggaran',
    ];
}
