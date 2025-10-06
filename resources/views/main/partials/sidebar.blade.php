<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/img/logosph.png') }}" alt="SafePoint" class="sidebar-logo me-2"
                    style="height: 40px; width: auto;">
                <span class="sidebar-title fw-bold text-dark" style="font-size: 16px;">SafePoint</span>
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                @if (auth()->check())
                    @php
                        $userRole = auth()->user()->role;
                    @endphp

                    {{-- OPERATOR & SUPERADMIN MENU --}}
                    @if (in_array($userRole, ['OPERATOR', 'SUPERADMIN']))
                        <li class="{{ request()->routeIs('dashboard') ? 'mm-active' : '' }}">
                            <a href="{{ route('dashboard') }}" class="waves-effect">
                                <i class="bx bx-home-circle"></i>
                                <span key="t-dashboard">Dashboard</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs(['cases.*']) ? 'mm-active' : '' }}">
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bx-shield-alt-2"></i>
                                <span key="t-cases">Kasus</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="{{ request()->routeIs('cases.index') ? 'mm-active' : '' }}">
                                    <a href="{{ route('cases.index') }}" key="t-cases-list">Daftar Kasus</a>
                                </li>
                            </ul>
                        </li>

                        @if ($userRole === 'SUPERADMIN')
                            <li class="{{ request()->routeIs(['units.*', 'users.*']) ? 'mm-active' : '' }}">
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bx-group"></i>
                                    <span key="t-management">Manajemen</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li class="{{ request()->routeIs('units.index') ? 'mm-active' : '' }}">
                                        <a href="{{ route('units.index') }}" key="t-units-list">Daftar Unit</a>
                                    </li>
                                    <li class="{{ request()->routeIs('units.create') ? 'mm-active' : '' }}">
                                        <a href="{{ route('units.create') }}" key="t-units-create">Tambah Unit</a>
                                    </li>
                                    <li class="{{ request()->routeIs('users.index') ? 'mm-active' : '' }}">
                                        <a href="{{ route('users.index') }}" key="t-users-list">Daftar Pengguna</a>
                                    </li>
                                    <li class="{{ request()->routeIs('users.create') ? 'mm-active' : '' }}">
                                        <a href="{{ route('users.create') }}" key="t-users-create">Tambah Pengguna</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endif

                    {{-- PIMPINAN MENU --}}
                    @if ($userRole === 'PIMPINAN')
                        <li class="{{ request()->routeIs('pimpinan.dashboard') ? 'mm-active' : '' }}">
                            <a href="{{ route('pimpinan.dashboard') }}" class="waves-effect">
                                <i class="bx bx-home-circle"></i>
                                <span key="t-dashboard">Dashboard</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('pimpinan.case.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('pimpinan.dashboard') }}" class="waves-effect">
                                <i class="bx bx-shield-alt-2"></i>
                                <span key="t-cases">Kasus Ditugaskan</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('pimpinan.petugas*') ? 'mm-active' : '' }}">
                            <a href="{{ route('pimpinan.petugas') }}" class="waves-effect">
                                <i class="bx bx-group"></i>
                                <span key="t-petugas">Kelola Petugas</span>
                            </a>
                        </li>
                    @endif

                    {{-- PETUGAS MENU --}}
                    @if ($userRole === 'PETUGAS')
                        <li class="{{ request()->routeIs('petugas.dashboard') ? 'mm-active' : '' }}">
                            <a href="{{ route('petugas.dashboard') }}" class="waves-effect">
                                <i class="bx bx-home-circle"></i>
                                <span key="t-dashboard">Dashboard</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('petugas.case.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('petugas.dashboard') }}" class="waves-effect">
                                <i class="bx bx-task"></i>
                                <span key="t-tasks">Tugas Saya</span>
                            </a>
                        </li>
                    @endif

                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
