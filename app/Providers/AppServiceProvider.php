<?php

namespace App\Providers;

use App\Models\HakAkses;
use App\Models\Menu;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {

                $idrole = UserRole::where('iduser', Auth::user()->id)->get()->pluck('idrole');

                $menuKategori = Menu::with('kategori')->whereHas('fitur', function ($query) use ($idrole) {
                    $query->whereHas('hakakses', function ($query) use ($idrole) {
                        $query->whereIn('idrole', $idrole);
                    });
                })->get();

                // $menuKategori = Menu::with('kategori')->get();

                $fiturMenu = [];
                foreach ($menuKategori as $menu) {
                    foreach ($menu->fitur as $fitur) {
                        $fiturMenu[$menu->menu][] = $fitur->fitur;
                    }
                }
                
                session(['fiturMenu' => $fiturMenu]);
                $view->with(
                    [
                        'menuKategori' => $menuKategori,
                        'fiturMenu' => $fiturMenu
                    ]
                );
            }
        });
    }
}
