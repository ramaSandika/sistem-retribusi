<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\MasterRetribusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterRetribusiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $opd = $request->input('opd');

        $query = MasterRetribusi::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_rekening', 'like', "%{$search}%")
                  ->orWhere('nama_retribusi', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        if ($opd && $opd !== 'Semua OPD') {
            $query->where('opd_name', $opd);
        }

        $items = $query->orderBy('kode_rekening')->paginate(15);
        $opdList = MasterRetribusi::select('opd_name')->distinct()->pluck('opd_name');

        return view('master.index', compact('items', 'user', 'opdList', 'search', 'opd'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_rekening' => 'required|string|unique:master_retribusis,kode_rekening',
            'nama_retribusi' => 'required|string|max:255',
            'opd_name' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'target_anggaran' => 'nullable|numeric|min:0',
        ]);

        $item = MasterRetribusi::create([
            'kode_rekening' => trim($request->kode_rekening),
            'nama_retribusi' => trim($request->nama_retribusi),
            'opd_name' => $request->opd_name ?: 'Badan Pendapatan Daerah',
            'kategori' => $request->kategori ?: 'Pajak & Retribusi Daerah',
            'target_anggaran' => $request->target_anggaran ?: 0,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'CREATE_MASTER_RETRIBUSI',
            'details' => "Menambahkan master kode rekening {$item->kode_rekening} ({$item->nama_retribusi})",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', "Master kode rekening {$item->kode_rekening} berhasil ditambahkan!");
    }

    public function update(Request $request, $id)
    {
        $item = MasterRetribusi::findOrFail($id);

        $request->validate([
            'kode_rekening' => "required|string|unique:master_retribusis,kode_rekening,{$id}",
            'nama_retribusi' => 'required|string|max:255',
            'opd_name' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'target_anggaran' => 'nullable|numeric|min:0',
        ]);

        $item->update([
            'kode_rekening' => trim($request->kode_rekening),
            'nama_retribusi' => trim($request->nama_retribusi),
            'opd_name' => $request->opd_name ?: $item->opd_name,
            'kategori' => $request->kategori ?: $item->kategori,
            'target_anggaran' => $request->target_anggaran ?: 0,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'UPDATE_MASTER_RETRIBUSI',
            'details' => "Memperbarui master kode rekening {$item->kode_rekening} ({$item->nama_retribusi})",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', "Master kode rekening {$item->kode_rekening} berhasil diperbarui!");
    }

    public function destroy(Request $request, $id)
    {
        $item = MasterRetribusi::findOrFail($id);
        $kode = $item->kode_rekening;
        $nama = $item->nama_retribusi;
        $item->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'DELETE_MASTER_RETRIBUSI',
            'details' => "Menghapus master kode rekening {$kode} ({$nama})",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', "Master kode rekening {$kode} berhasil dihapus.");
    }
}
