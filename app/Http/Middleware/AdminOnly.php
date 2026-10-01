<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request , Closure ): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak! Menu tersebut khusus untuk Administrator BAPENDA.');
        }

        return ();
    }
}
