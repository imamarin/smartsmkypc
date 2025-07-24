<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class ResetPasswordController extends Controller
{
    public function showResetForm($token, Request $request)
    {
        try {
            $username = Crypt::decrypt($request->query('username'));
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        return view('pages.auth.reset-password', compact('token', 'username'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        try {
            $username = Crypt::decrypt($request->username);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Invalid Request');
        }

        $record = DB::table('password_resets')
            ->where('username', $username)
            ->where('token', $request->token)
            ->first();

        if (!$record || Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['token' => 'Token tidak valid atau sudah kadaluarsa.']);
        }

        $user = User::where('username', $username)->first();
        // $user = User::whereHas('staf', function ($q) use ($request) {
        //     $q->where('no_hp', $request->phone);
        // })->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        DB::table('password_resets')->where('username', $username)->delete();

        return redirect('/')->with('status', 'Password berhasil direset.');
    }
}
