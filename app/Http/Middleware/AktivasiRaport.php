<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AktivasiRaport
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
        return $next($request);
    }
}
