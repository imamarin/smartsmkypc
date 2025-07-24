<?php

namespace App\Http\Controllers;

use App\Models\TokenMengajar;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class TokenMengajarController extends Controller
{
    //
    public function generateToken($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $token = strtoupper(Str::random(6));
        $expiredAt = now()->addMinutes(15);

        TokenMengajar::create([
            'idjadwalmengajar' => $id,
            'token' => $token,
            'expired_at' => $expiredAt,
            'status' => 'aktif',
        ]);

        return redirect()->back()->with('success', 'Token sudah dibuat');
    }
}
