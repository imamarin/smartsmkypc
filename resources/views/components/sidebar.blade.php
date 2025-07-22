<div class="vertical-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-smk.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/smkypc-light.png') }}" alt="" style="width: 160px; height: 50px;">
            </span>
        </a>

        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-lg">
                <img src="{{ asset('assets/images/smkypc-dark.png') }}" alt="" style="width: 160px; height: 50px;">
            </span>
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-smk.png') }}" alt="" height="22">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn" style="color: white">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                @php
                    $tampilkanKategori = [];
                @endphp
                @foreach ($menuKategori as $menu)
                    @if (!in_array($menu->kategori->id, $tampilkanKategori))
                        @php
                            $isActiveSubmenu = $menuKategori
                                ->where('idkategori', $menu->kategori->id)
                                ->contains(
                                    fn($submenu) => request()->is(ltrim($submenu->url, '/')) ||
                                        request()->is(ltrim($submenu->url, '/') . '*'),
                                );
                        @endphp

                        @if ($menuKategori->where('idkategori', $menu->kategori->id)->count() > 1)
                            <li class="{{ $isActiveSubmenu ? 'mm-active' : '' }}">
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i class="icon nav-icon" data-feather="{{ $menu->kategori->icon }}"></i>
                                    <span class="menu-item" data-key="t-contacts">{{ $menu->kategori->kategori }}</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @foreach ($menuKategori->where('idkategori', $menu->kategori->id) as $submenu)
                                        <li
                                            class="{{ request()->is(ltrim($submenu->url, '/') . '*') ? 'mm-active' : '' }}">
                                            <a href="{{ $submenu->url }}"
                                                data-key="t-user-grid">{{ $submenu->menu }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            @if($menu->menu != "Honorarium Pegawai")
                            <li class="{{ request()->is(ltrim($menu->url, '/') . '*') ? 'mm-active' : '' }}">
                                <a href="{{ $menu->url }}">
                                    <i class="icon nav-icon" data-feather="{{ $menu->kategori->icon }}"></i>
                                    <span class="menu-item" data-key="t-analytics">{{ $menu->menu }}</span>
                                </a>
                            </li>
                            @endif
                        @endif
                        @php
                            // Menandai kategori ini sudah ditampilkan
                            $tampilkanKategori[] = $menu->kategori->id;
                        @endphp
                    @endif
                @endforeach


            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>

