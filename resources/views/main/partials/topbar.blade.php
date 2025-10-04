<header id="page-topbar">
    <div class="navbar-header">
                    <!-- Sidebar Toggle Button (left-most) -->
            <div class="me-2">
                <button type="button" class="btn header-item sidebar-toggle-btn" id="vertical-menu-btn">
                    <i class="bx bx-menu font-size-18"></i>
                </button>
            </div>
        <div class="d-flex">


            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-magnify"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">
        
                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                      data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="bell" class="icon-lg"></i>
                    <span class="badge bg-danger rounded-pill" id="notification-count">0</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">

                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0">Notifikasi Kasus</h6>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-sm btn-link p-0" id="mark-all-read-btn">Tandai Semua Dibaca</button>
                            </div>
                        </div>
                    </div>

                    <div data-simplebar style="max-height: 350px;" id="notifications-container">
                        <div class="text-center py-4" id="notifications-loading">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Memuat...</span>
                            </div>
                            <p class="small text-muted mt-2">Memuat notifikasi...</p>
                        </div>
                        
                        <div class="text-center py-5 d-none" id="notifications-empty">
                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title bg-light rounded-circle">
                                    <i class="bx bx-bell font-size-24 text-muted"></i>
                                </div>
                            </div>
                            <h6 class="font-size-15 text-muted">Tidak ada notifikasi</h6>
                            <p class="text-muted mb-0">Belum ada notifikasi baru</p>
                        </div>
                        
                        <div id="notifications-list">
                            <!-- Notifications will be loaded here -->
                        </div>
                    </div>

                    <div class="p-2 border-top d-grid">
                        <button class="btn btn-sm btn-link font-size-14 text-center" id="refresh-notifications-btn">
                            <i class="mdi mdi-refresh me-1"></i> <span>Refresh</span> 
                        </button>
                    </div>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect d-flex align-items-center" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user me-2" src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                        alt="Header Avatar" style="width: 32px; height: 32px; object-fit: cover;">
                    <span class="d-none d-xl-inline-block" key="t-henry">{{ auth()->user()->name ?? 'Guest' }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block ms-1"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="#"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Profile</span></a>
                    <a class="dropdown-item" href="#"><i class="bx bx-wallet font-size-16 align-middle me-1"></i> <span key="t-my-wallet">My Wallet</span></a>
                    <a class="dropdown-item d-block" href="#"><i class="bx bx-wrench font-size-16 align-middle me-1"></i> <span key="t-settings">Settings</span></a>
                    <a class="dropdown-item" href="#"><i class="bx bx-lock-open font-size-16 align-middle me-1"></i> <span key="t-lock-screen">Lock screen</span></a>
                    <div class="dropdown-divider"></div>
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> 
                                <span key="t-logout">Logout</span>
                            </button>
                        </form>
                    @else
                        <a class="dropdown-item" href="{{ route('login') }}">
                            <i class="bx bx-log-in font-size-16 align-middle me-1"></i>
                            <span key="t-login">Login</span>
                        </a>
                    @endauth
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i data-feather="settings" class="icon-lg"></i>
                </button>
            </div>

        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update clock every second
    setInterval(updateClock, 1000);
    updateClock();

    function updateClock() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        const timeString = `${hours}:${minutes}:${seconds}`;

        // Format Indonesian date
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric'
        };
        const dateString = now.toLocaleDateString('id-ID', options);
        
        const clockElement = document.getElementById('clock');
        if (clockElement) {
            clockElement.textContent = dateString + ' ' + timeString;
        }
    }
});
</script>