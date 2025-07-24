<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('pages.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['phone' => 'required']);

        $user = User::with('staf')
            ->whereHas('staf', function ($q) use ($request) {
                $q->where('no_hp', $request->phone);
            })->first();

        if (!$user || !$user->staf) {
            return back()->withErrors(['phone' => 'Nomor tidak ditemukan.']);
        }

        $phone = $user->staf->no_hp;
        $nama = $user->staf->nama;

        $token = Str::random(60);

        DB::table('password_resets')->updateOrInsert(
            ['phone' => $phone],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        $link = url("/reset-password/{$token}?phone={$phone}");

        // Kirim ke WhatsApp
        Http::get('http://wa.smk-ypc.sch.id/send', [
            'number' => $phone,
            'text' => "Assalamulaikum $nama, \n\nKami menerima permintaan reset password.\nKlik link berikut untuk melanjutkan:\n\n$link",
        ]);

        return back()->with('status', 'Link reset password dikirim ke WhatsApp.');
    }
}
