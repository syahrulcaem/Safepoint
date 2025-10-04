<!-- ############ NAVBAR START-->
<div id="header" class="page-header ">
    <div class="navbar navbar-expand-lg">
        <!-- brand -->
        <a href="<?=base_url()?>/main" class="navbar-brand d-lg-none">
            
            <img src="<?=base_url();?>/assets/img/logo.png">

        </a>
        <!-- / brand -->
        <!-- Navbar collapse -->
        
        <ul class="nav navbar-menu order-1 order-lg-2">
            <li class="nav-item d-none d-sm-block">
                <a class="nav-link px-2" data-toggle="fullscreen" data-plugin="fullscreen">
                    <i data-feather="maximize"></i>
                </a>
            </li>
            
            <!-- User dropdown menu -->
            <li class="nav-item dropdown">
                <a href="#" data-toggle="dropdown" class="nav-link d-flex align-items-center px-2 text-color">
                    <span class="avatar w-24" style="margin: -2px;"><img src="<?=base_url()?>/assets/img/a1.jpg" alt="..."></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right w mt-3 animate fadeIn">
                    <a class="dropdown-item" href="page.profile.html">
                        <span>Jacqueline Reid</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?=base_url()?>/auth/action/logout">Sign out</a>
                </div>
            </li>
            <!-- Navarbar toggle btn -->
            <li class="nav-item d-lg-none">
                <a href="#" class="nav-link px-2" data-toggle="collapse" data-toggle-class data-target="#navbarToggler">
                    <i data-feather="search"></i>
                </a>
            </li>
            <li class="nav-item d-lg-none">
                <a class="nav-link px-1" data-toggle="modal" data-target="#aside">
                    <i data-feather="menu"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- ############ NAVBAR END-->
