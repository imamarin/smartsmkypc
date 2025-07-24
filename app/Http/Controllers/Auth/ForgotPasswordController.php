<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('pages.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['username' => 'required']);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors(['message' => 'User not found']);
        }

        if (!$user->staf) {
            if (!$user->siswa) {
                $phone = $user->siswa->no_hp ?? false;
                $nama = $user->siswa->nama;
            } else {
                return back()->withErrors(['phone' => 'Nomor tidak ditemukan.']);
            }
        } else {
            $phone = $user->staf->no_hp ?? false;
            $nama = $user->staf->nama;
        }

        // $user = User::with('staf')
        //     ->whereHas('staf', function ($q) use ($request) {
        //         $q->where('no_hp', $request->phone);
        //     })->first();

        if ($phone == false) {
            return back()->withErrors(['phone' => 'Nomor tidak ditemukan.']);
        }



        $token = Str::random(60);

        DB::table('password_resets')->updateOrInsert(
            ['username' => $request->username],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        $username = Crypt::encrypt($request->username);
        $link = url("/reset-password/{$token}?username={$username}");

        // Kirim ke WhatsApp
        $phone = preg_replace('/^0/', '62', $phone);
        $phone = preg_replace('/^(\+?)(62)?/', '62', $phone);
        $response = Http::get('http://wa.smk-ypc.sch.id/send', [
            'number' => $phone,
            'text' => "Assalamulaikum Wr Wb.\n\nYth. $nama, \n\nKami menerima permintaan reset password.\nKlik link berikut untuk melanjutkan atau abaikan jika tidak melakukan permintaan reset:\n\n$link",
        ]);
        $status = $response->status();
        dd($response->body());
        if ($status != 200) {
            return back()->withErrors(['phone' => 'Tidak terkirim, silakan periksa kembali no whatasapp. pastikan depan no adalah 62 bukan 0.']);
        }

        return back()->with('status', 'Link reset password dikirim ke WhatsApp.');
    }
}
