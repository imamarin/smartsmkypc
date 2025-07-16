<header id="page-topbar">
    <div class="navbar-header d-flex justify-content-end">
        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item user text-start d-flex align-items-center"
                id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @php
                    $nama = Auth::user()->staf->nama ?? Auth::user()->siswa->nama;
                    $inisial = collect(explode(' ', $nama))
                        ->filter(fn($kata) => !empty($kata))
                        ->take(2)
                        ->map(fn($kata) => mb_substr($kata, 0, 1))
                        ->implode('');
                    $inisial = strtoupper($inisial);
                @endphp
                <span class="rounded-circle border border-1 border-white bg-primary text-white d-flex justify-content-center align-items-center" style="width: 40px; height: 40px; font-size: 1.2rem;">
                    {{ $inisial }}
                </span>
                <span class="ms-2 d-none d-sm-block user-item-desc">
                    <span class="user-name text-white">{{ Auth::user()->staf->nama ?? Auth::user()->siswa->nama }}</span>
                    <span class="user-sub-title text-white">{{ Auth::user()->staf->nip ?? Auth::user()->siswa->nisn }}</span>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu-end pt-0">
                <div class="p-3 bg-primary border-bottom">
                    <h6 class="mb-0 text-white">{{ Auth::user()->staf->nama ?? Auth::user()->siswa->nama }}</h6>
                    <p class="mb-0 font-size-11 text-white fw-semibold">{{ Auth::user()->staf->nip ?? Auth::user()->siswa->nisn }}</p>
                </div>
                @php
                    $user = Auth::user()->load('user_role.role');
                    $roles = $user->user_role->pluck('role.role')->toArray();
                @endphp
                @if(in_array('Siswa', $roles))
                <a class="dropdown-item" href="{{ route('siswa.profil') }}"><i
                        class="mdi mdi-account-circle text-muted font-size-16 align-middle me-1"></i> <span
                        class="align-middle">Profil</span></a>
                @else
                <a class="dropdown-item" href="{{ route('profil-staf.edit', Crypt::encrypt(Auth::user()->id)) }}"><i
                        class="mdi mdi-account-circle text-muted font-size-16 align-middle me-1"></i> <span
                        class="align-middle">Profil</span></a>
                <a class="dropdown-item" href="{{ route('staf.honorarium') }}"><i
                        class="mdi mdi-account-cash-outline text-muted font-size-16 align-middle me-1"></i> <span
                        class="align-middle">Honor</span></a>
                {{--  <a class="dropdown-item" href="apps-chat.html"><i
                        class="mdi mdi-message-text-outline text-muted font-size-16 align-middle me-1"></i>
                    <span class="align-middle">Messages</span></a>  --}}
                {{--  <a class="dropdown-item" href="apps-kanban-board.html"><i
                        class="mdi mdi-calendar-check-outline text-muted font-size-16 align-middle me-1"></i>
                    <span class="align-middle">Taskboard</span></a>  --}}
                {{--  <a class="dropdown-item" href="pages-faqs.html"><i
                        class="mdi mdi-lifebuoy text-muted font-size-16 align-middle me-1"></i> <span
                        class="align-middle">Help</span></a>  --}}
                <div class="dropdown-divider"></div>
                {{--  <a class="dropdown-item" href="pages-profile.html"><i
                        class="mdi mdi-wallet text-muted font-size-16 align-middle me-1"></i> <span
                        class="align-middle">Balance : <b>$6951.02</b></span></a>  --}}
                {{-- <a class="dropdown-item d-flex align-items-center" href="{{ route('pengaturan.index') }}"><i
                        class="mdi mdi-cog-outline text-muted font-size-16 align-middle me-1"></i> <span
                        class="align-middle">Pengaturan</span></a> --}}
                {{--  <a class="dropdown-item" href="auth-lockscreen-basic.html"><i
                        class="mdi mdi-lock text-muted font-size-16 align-middle me-1"></i> <span
                        class="align-middle">Lock screen</span></a>  --}}
                @endif
                <a class="dropdown-item" href="{{ route('logout') }}"><i
                        class="mdi mdi-logout text-muted font-size-16 align-middle me-1"></i> <span
                        class="align-middle">Keluar</span></a>
            </div>
        </div>
    </div>
</header>
