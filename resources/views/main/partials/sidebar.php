<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <?php
            $session = \Config\Services::session();
            $module_active = uri_segment(0);
            $menu_active = uri_segment(1);
            ?>
            <ul class="metismenu list-unstyled" id="side-menu" style="min-height: 400px">
                <!-- <li class="menu-title" data-key="t-menu">
                    <?= lang('Files.Menu') ?>
                </li> -->

                <li class="<?= (($module_active == 'main' || $module_active == '') ? 'mm-active' : '') ?>">
                    <a href="<?= base_url() ?>">
                        <i data-feather="home"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <?php
                function group_by($array, $by)
                {
                    $groups = array();
                    foreach ($array as $key => $value) {
                        $groups[$value->$by][] = $value;
                    }
                    return $groups;
                }

                $module = group_by($session->get('menu'), 'module_name');
                foreach ($module as $key => $_module) {
                    $iconMap = [
                        'Administrator' => 'sliders',
                        'Maintenance Data' => 'database',
                        'Manajemen Data' => 'folder',
                        'Maintenance Transaksi' => 'repeat',
                        'Transaksi Top Up' => 'credit-card',
                        'Eksekutif' => 'user-check',
                        'Verifikasi Transaksi' => 'check-circle',
                        'SimPeg' => 'users',
                        'Complain' => 'alert-circle',
                        'Aktivitas' => 'activity',
                        'Log Data' => 'file-text',
                        'Inventaris' => 'archive',
                        'Keuangan' => 'dollar-sign'
                    ];
                    $icon = isset($iconMap[$key]) ? $iconMap[$key] : 'grid';

                    echo '<li>
                        <a href="javascript: void(0);" class="' . (count($_module) > 0 ? "has-arrow" : "") . '">
                            <i data-feather="' . $icon . '"></i>
                            <span data-key="t-apps">' . $key . '</span>
                        </a>';

                    echo count($_module) > 0 ? '<ul class="sub-menu" aria-expanded="false">' : '';
                    $grouped = group_by($_module, 'menu_parent');

                    foreach ($grouped as $_key => $_grouped) {
                        if ($_key == "") {
                            foreach ($_grouped as $__key => $menu) {
                                $url = base_url() . '/' . $menu->module_url . '/' . $menu->menu_url;
                                if ($menu->module_url . '/' . $menu->menu_url == 'perintis/mantrayek' || $menu->module_url . '/' . $menu->menu_url == 'kspn/mantrayek') {
                                    $badgenew = '<span class="badge badge-soft-danger badge-pill float-right">New!</span>';
                                } else {
                                    $badgenew = '';
                                }
                                echo '<li class="' . (($menu_active == $menu->menu_url && $module_active == $menu->module_url) ? "active" : "") . '">
                                        <a href="' . $url . '" class="">
                                            <span data-key="t-calendar">' . $menu->menu_name . '</span>' . $_key . '
                                            ' . $badgenew . '
                                        </a>
                                    </li>';
                            }
                        } else {
                            echo '<li class="' . ((count(array_filter($_grouped, function ($arr) use ($menu_active) {
                                return  strtolower($arr->menu_url) == strtolower($menu_active);
                            })) > 0) ? 'active' : '') . '">
                                    <a href="javascript: void(0);" class="has-arrow">
                                        <span data-key="t-contacts">' . $_key . '</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">';
                            foreach ($_grouped as $__key => $menu) {
                                echo '<li class="' . (($menu_active == $menu->module_url) ? 'active' : '') . '">
                                                <a href="' . base_url() . '/' . $menu->module_url . '/' . $menu->menu_url . '" class="">
                                                    <span class="nav-text">' . $menu->menu_name . '</span>
                                                </a>
                                            </li>';
                            }
                            echo '</ul>
                                </li>';
                        }
                    }

                    echo count($_module) > 0 ? '</ul>' : '';
                }

                ?>
            </ul>
        </div>
    </div>
</div>