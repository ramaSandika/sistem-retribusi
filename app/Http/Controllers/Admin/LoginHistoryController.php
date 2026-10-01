<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = LoginHistory::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('email_attempted', 'like', "%{$s}%")
                  ->orWhere('ip_address', 'like', "%{$s}%")
                  ->orWhereHas('user', function ($sub) use ($s) {
                      $sub->where('name', 'like', "%{$s}%");
                  });
            });
        }

        $histories = $query->latest('login_at')->paginate(20);
        return view('admin.login_histories.index', compact('histories'));
    }

    public function clear()
    {
        LoginHistory::truncate();
        return back()->with('success', 'Seluruh catatan riwayat login berhasil dibersihkan.');
    }
}
