<?php

namespace App\Http\Middleware;

use App\Models\Walikelas;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class WalikelasMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $aktivasi = Session::get('aktivasi');
        if (!$aktivasi) {
            return redirect()->route('raport-identitas.index')->with('warning', 'Anda belum melakukan aktivasi raport');
        }

        $walikelas = Walikelas::where('nip', Auth::user()->staf->nip)->whereHas('tahunajaran', function ($query) {
            $query->where('status', 1);
        })->first();
        if (!$walikelas) {
            return redirect()->back()->with('warning', 'Maaf, fitur ini hanya untuk walikelas');
        }


        return $next($request);
    }
}
