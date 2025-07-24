<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function showResetForm($token, Request $request)
    {
        $phone = $request->query('phone');
        return view('pages.auth.reset-password', compact('token', 'phone'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $record = DB::table('password_resets')
            ->where('phone', $request->phone)
            ->where('token', $request->token)
            ->first();

        if (!$record || Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['token' => 'Token tidak valid atau sudah kadaluarsa.']);
        }

        $user = User::whereHas('staf', function ($q) use ($request) {
            $q->where('no_hp', $request->phone);
        })->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        DB::table('password_resets')->where('phone', $request->phone)->delete();

        return redirect('/')->with('status', 'Password berhasil direset.');
    }
}
