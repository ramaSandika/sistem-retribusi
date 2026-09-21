<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        $opdList = [
            'Badan Pendapatan Daerah',
            'Dinas Perhubungan',
            'Dinas Perdagangan',
            'Dinas Perkim',
            'Dinas Lingkungan Hidup',
            'Dinas Kesehatan',
            'Dinas Pariwisata & Kebudayaan',
        ];

        return view('users.index', compact('users', 'opdList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user_opd',
            'opd_name' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'opd_name' => $request->opd_name,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'CREATE_USER',
            'details' => "Admin mendaftarkan akun pengguna baru {$user->name} ({$user->email}) untuk {$user->opd_name}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', "Akun pengguna {$user->name} berhasil ditambahkan.");
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|string|min:6',
        ]);

        $targetUser = User::findOrFail($id);
        $targetUser->update([
            'password' => Hash::make($request->new_password),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'RESET_PASSWORD',
            'details' => "Admin mereset kata sandi akun {$targetUser->name} ({$targetUser->email})",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', "Kata sandi pengguna {$targetUser->name} berhasil direset.");
    }

    public function destroy($id)
    {
        if ($id == Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $targetUser = User::findOrFail($id);
        $name = $targetUser->name;

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => 'DELETE_USER',
            'details' => "Admin menghapus akun pengguna {$name} ({$targetUser->email})",
            'ip_address' => request()->ip(),
        ]);

        $targetUser->delete();

        return redirect()->back()->with('success', "Akun pengguna {$name} berhasil dihapus.");
    }
}
