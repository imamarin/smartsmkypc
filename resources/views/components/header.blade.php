<header id="page-topbar">
    <div class="navbar-header d-flex justify-content-end">
        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item user text-start d-flex align-items-center"
                id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                    alt="Header Avatar">
                <span class="ms-2 d-none d-sm-block user-item-desc">
                    <span class="user-name">{{ Auth::user()->staf->nama ?? '' }}</span>
                    <span class="user-sub-title">{{ Auth::user()->staf->nip ?? '' }}</span>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu-end pt-0">
                <div class="p-3 bg-primary border-bottom">
                    <h6 class="mb-0 text-white">{{ Auth::user()->staf->nama ?? '' }}</h6>
                    <p class="mb-0 font-size-11 text-white-50 fw-semibold">{{ Auth::user()->staf->nip ?? '' }}</p>
                </div>
                <a class="dropdown-item" href="pages-profile.html"><i
                        class="mdi mdi-account-circle text-muted font-size-16 align-middle me-1"></i> <span
                        class="align-middle">Profil</span></a>
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
                <a class="dropdown-item d-flex align-items-center" href="{{ route('pengaturan.index') }}"><i
                        class="mdi mdi-cog-outline text-muted font-size-16 align-middle me-1"></i> <span
                        class="align-middle">Pengaturan</span></a>
                {{--  <a class="dropdown-item" href="auth-lockscreen-basic.html"><i
                        class="mdi mdi-lock text-muted font-size-16 align-middle me-1"></i> <span
                        class="align-middle">Lock screen</span></a>  --}}
                <a class="dropdown-item" href="{{ route('logout') }}"><i
                        class="mdi mdi-logout text-muted font-size-16 align-middle me-1"></i> <span
                        class="align-middle">Keluar</span></a>
            </div>
        </div>
    </div>
</header>
