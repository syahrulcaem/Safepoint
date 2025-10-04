<?php $session = \Config\Services::session(); ?>
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex align-items-center">
            <!-- LOGO -->
            <div class="d-none d-md-flex justify-content-start navbar-brand-box">
                <a href="<?= base_url() ?>" class="logo logo-dark">
                    <span class="logo-sm" style="overflow: hidden;">
                        <img src="<?= base_url(getenv('prop.applogo')) ?>" alt="" height="20">
                    </span>
                    <span class="logo-lg" style="overflow: hidden;">
                        <img src="<?= base_url(getenv('prop.applogofull')) ?>" alt="" height="25">
                    </span>
                </a>

                <a href="<?= base_url() ?>" class="logo logo-light">
                    <span class="logo-sm" style="overflow: hidden;">
                        <img src="<?= base_url(getenv('prop.applogo')) ?>" alt="" height="20">
                    </span>
                    <span class="logo-lg" style="overflow: hidden;">
                        <img src="<?= base_url(getenv('prop.applogofull')) ?>" alt="" height="25">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            <div class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <h4 class="page-title mb-0 mt-2 font-size-18">Dashboard SimPeg as <?= $session->get('role_name') ?></h4>
                </div>
            </div>
        </div>

        <div class="d-flex">
            <?php
            $lang = $session->get('lang');
            $role_code = $session->get('role_code');
            $menu = $session->get('menu');
            for ($i = 0; $i < count($menu); $i++) {
                if ($menu[$i]->menu_id = '49' && $menu[$i]->id = '112') {
                    $operasional_view = $menu[$i]->v;
                    $operasional_insert = $menu[$i]->i;
                    $operasional_edit = $menu[$i]->e;
                    $operasional_deleted = $menu[$i]->d;
                    $operasional_otorisasi = $menu[$i]->o;

                    $operasional_user_web_role_id = $menu[$i]->user_web_role_id;
                } else {
                    $operasional_view = 0;
                    $operasional_insert = 0;
                    $operasional_edit = 0;
                    $operasional_deleted = 0;
                    $operasional_otorisasi = 0;

                    $operasional_user_web_role_id = '';
                }
            }
            ?>
            <div class="dropdown d-lg-block d-none">
                <button type="button" class="btn header-item ">
                    <span class="badge rounded-2 font-size-12" style="background-color: #e57b1fff;">
                        <span id="clock"></span>
                    </span><br>
                    <span class="font-size-12">Log In sebagai <?= $session->get('role_name') ?></span>
                </button>
            </div>
            <!-- notif -->
            <div class="dropdown d-inline-block" id="notif">
                <button type="button" class="btn header-item noti-icon position-relative" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="bell" class="icon-lg"></i>
                    <span class="badge bg-danger rounded-pill" id="notif-count" style="display: none;"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> Notifikasi </h6>
                            </div>
                            <div class="col-auto">
                                <a href="#!" class="small text-reset text-decoration-underline" id="notif-unread" style="display: none;"></a>
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;" id="notif-content">
                    </div>
                    <div class="p-2 border-top d-grid">
                    </div>
                </div>
            </div>
            <!-- end notif -->
            <!-- <?php if ($role_code == 'bpw') { ?>
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon position-relative" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="bell" class="icon-lg"></i>
                        <span class="badge bg-danger rounded-pill" id="noti-count1"></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0"> Notifikasi Validasi SPDA </h6>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 230px;" id="noti-data" data-simplebar-auto-hide="true"></div>
                        <div class="p-2 border-top d-grid">
                            <a class="btn btn-sm btn-link font-size-14 text-center" href="<?= base_url('operasional/spda') ?>">
                                <i class="mdi mdi-arrow-right-circle me-1"></i> <span><?= lang('Files.View_More') ?>..</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } else if ($role_code == 'pop' || $role_code == 'ppo') { ?>
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon position-relative" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i data-feather="bell" class="icon-lg"></i>
                        <span class="badge bg-danger rounded-pill" id="noti-count1"></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0"> Notifikasi SPDA </h6>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 230px;" id="noti-data" data-simplebar-auto-hide="true"></div>
                        <div class="p-2 border-top d-grid">
                            <a class="btn btn-sm btn-link font-size-14 text-center" href="<?= base_url('operasional/spda') ?>">
                                <i class="mdi mdi-arrow-right-circle me-1"></i> <span><?= lang('Files.View_More') ?>..</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?> -->
            <div class="dropdown d-inline-block" style="float:right;">
                <button type="button" class="btn header-item" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle img-fluid header-profile-user" style="background-color: transparent; width: 40px; height: 40px; object-fit: cover;" src="<?= $session->get('karyawan_file_foto') ? base_url($session->get('karyawan_file_foto')) : base_url('assets/img/guest.png') ?>" alt="Header Avatar">
                    <!-- <img class="rounded-circle header-profile-user" style="background-color: transparent; height: 40px;" src="<?= base_url() ?>/assets/img/guest.png" alt="Header Avatar"> -->
                    <span class="d-inline d-xl-inline-block ms-1 fw-medium font-size-10"><?= $session->get('name') ?></span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <div class="card">
                        <div class="card-body text-center">
                            <!-- <img class="rounded-circle header-profile-user" style="background-color: transparent; height: 40px;" src="<?= base_url() ?>/assets/img/guest.png" alt="Header Avatar"> -->
                            <img class="rounded-circle img-fluid header-profile-user" style="background-color: transparent; width: 40px; height: 40px; object-fit: cover;" src="<?= $session->get('karyawan_file_foto') ? base_url($session->get('karyawan_file_foto')) : base_url('assets/img/guest.png') ?>" alt="Header Avatar">
                            <h5 class="card-title"><?= $session->get('name') ?></h5>
                            <span class="text-muted"><?= $session->get('role_name') ?></span><br>
                            <span class="text-muted">(<?= $session->get('username') ?>)</span>
                        </div>
                    </div>
                    <a class="dropdown-item" href="<?= base_url() ?>/main/userprofile">
                        <i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i>
                        <?= lang('Files.Change_Password') ?>
                    </a>
                    <!-- manual book -->
                    <!-- <a class="dropdown-item" href="<?= base_url() ?>/main/manualbook">
                        <i class="mdi mdi-book-open-variant font-size-16 align-middle me-1"></i>
                        <?= lang('Files.Manual_Book') ?>
                    </a> -->
                    <!-- <div class="dropdown d-none d-sm-inline-block"> -->
                    <!-- <button type="button" class="btn header-item" id="mode-setting-btn">
                        <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                        <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                    </button> -->
                    <!-- </div> -->
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?= base_url() ?>/auth/action/logout"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i>
                        <?= lang('Files.Logout') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
<?php if ($role_code == 'bpw' && $operasional_insert == '1') { ?>
    <style>
        #sig-canvas,
        #sig-canvas-manager,
        #bptd-canvas {
            border: 2px dotted #CCCCCC;
            border-radius: 15px;
            cursor: crosshair;
            /* disable scroll when touch */
            touch-action: none;
        }
    </style>
    <div class="modal fade" id="verifikasi-modal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <form id="form-verifikasi" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Verifikasi SPDA</h5>
                    </div>
                    <div class="modal-body">
                        <div id="verifikasi-modal-body">

                        </div>
                        <div>
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <p><b>Pegawai BPTD</b></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <canvas id="bptd-canvas"></canvas>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 text-center mb-3">
                                    <div class="btn btn-info btn-sm" id="bptd-submitBtn">Simpan Tanda Tangan</div>
                                    <div class="btn btn-default btn-sm" id="bptd-clearBtn">
                                        <i class="fa fa-eraser"></i> Hapus
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center mb-3">
                                    <i class="fa fa-check" id="bptd-check" style="display:none; color: green;"> Tanda Tangan Tersimpan</i>
                                </div>
                                <div hidden>
                                    <div class="col-md-12">
                                        <textarea id="bptd-dataUrl" name="bptd-dataUrl" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" id="bptdSubmit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
        <?php if ($operasional_otorisasi == "1") { ?>
            <script src="<?= base_url() ?>/assets/js/sig-bptd.js"></script>
        <?php } ?>
    </div>
<?php } else if ($role_code == 'pop' || $role_code == 'ppo') { ?>
    <div class="modal fade" id="spda-detail-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="form-spda-detail" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail SPDA</h5>
                    </div>
                    <div class="modal-body" id="spda-detail-modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } ?>
<form id="form_export_pdf" action="<?= base_url() ?>/main/action/spda_pdf" method="post" target="_blank">
    <?= csrf_field(); ?>
    <input type="hidden" name="id_pdf" id="id_pdf" />
</form>

<!-- INTERNAL SCRIPT -->
<script src="<?= base_url() ?>/assets/js/moment-with-locales.js"></script>
<script type="text/javascript">
    const topbar_base_url = '<?= base_url() ?>';
    const topbar_url = '<?= base_url() . "/" . uri_segment(0) . "/action/" . uri_segment(1) ?>';
    const topbar_segment = '<?= uri_segment(0) ?>';

    $(document).ready(function() {
        // Update clock every second
        setInterval(updateClock, 1000);
        updateClock();

        // Fetch notifications on load and when notif dropdown is clicked
        fetchNotifications();
        $(document)
            .on('click', '#notif', function() {
                fetchNotifications();
            })
            .on('click', '.notification-item', function(event) {
                event.preventDefault();
                let $this = $(this);
                let data = {
                    id: $this.data('id'),
                    "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                };

                $.ajax({
                    url: "<?= base_url('main/action/readNotification') ?>",
                    method: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            let detail_id = $this.data('detail');
                            let url = $this.attr('href');
                            sessionStorage.setItem('detail_id_from_notif', detail_id);
                            window.location.assign(url);
                        } else {
                            Swal.fire('Perhatian!', response.message, 'warning');
                        }
                    },
                    error: function() {
                        Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
                    }
                });
                return false;
            })
            .on('click', '#notif-unread', function(event) {
                event.preventDefault();
                let data = {
                    "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                };

                $.ajax({
                    url: "<?= base_url('main/action/readAllNotifications') ?>",
                    method: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            fetchNotifications();
                        } else {
                            Swal.fire('Perhatian!', response.message, 'warning');
                        }
                    },
                    error: function() {
                        Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
                    }
                });
                return false;
            });

        // Fetch SPDA pending if role is bptd, pop, or ppo
        if (<?= $role_code == 'bpw' ? 'true' : 'false' ?> || <?= $role_code == 'pop' || $role_code == 'ppo' ? 'true' : 'false' ?>) {
            getSpdaPending();
        }

        // Verifikasi SPDA form submit
        $('#form-verifikasi')
            .submit(function(e) {
                e.preventDefault();
                let $this = $(this);
                let data = $this.serialize() + "&<?= csrf_token() ?>=<?= csrf_hash() ?>";

                Swal.fire({
                    title: "Verifikasi SPDA ?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Simpan",
                    cancelButtonText: "Batal",
                    reverseButtons: true
                }).then(function(result) {
                    var bptdUrl = $('#bptd-dataUrl').val();
                    if (result.value && bptdUrl !== '') {
                        Swal.fire({
                            title: "",
                            icon: "info",
                            text: "Proses menyimpan data, mohon ditunggu...",
                            didOpen: function() {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            headers: {
                                'customref': '<?= base_url('operasional/spda') ?>'
                            },
                            url: "<?= base_url('operasional/action/spda_saveverif') ?>",
                            type: 'post',
                            data: data,
                            dataType: 'json',
                            success: function(result) {
                                Swal.close();
                                if (result.success) {
                                    var bptdCanvas = document.getElementById('bptd-canvas');
                                    bptdCanvas.width = bptdCanvas.width;

                                    $('#bptd-dataUrl').val('');
                                    $('#verifikasi-modal').modal('hide');
                                    getSpdaPending();

                                    Swal.fire({
                                        title: "Berhasil memverifikasi SPDA. Export PDF ?",
                                        icon: "success",
                                        showCancelButton: true,
                                        confirmButtonText: "Export",
                                        cancelButtonText: "Tutup",
                                        reverseButtons: true
                                    }).then(function(result) {
                                        if (result.value) {
                                            $('#form_export_pdf').submit();
                                        }
                                    });
                                } else {
                                    Swal.fire('Error', result.message, 'error');
                                }
                            },
                            error: function() {
                                Swal.close();
                                Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                            }
                        });
                    } else {
                        Swal.fire('Perhatian!', 'Tanda tangan belum diisi', 'warning');
                    }
                });
            })
            .on('reset', function() {
                var bptdCanvas = document.getElementById('bptd-canvas');
                bptdCanvas.width = bptdCanvas.width;

                $('#bptd-dataUrl').val('');
                $('#bptd-dataUrl').html('');
                document.getElementById("bptd-check").style.display = "none";
            });
    });

    function updateClock() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        const timeString = `${hours}:${minutes}:${seconds}`;

        moment.locale('id');
        document.getElementById('clock').textContent = moment(now).format('dddd, DD MMMM YYYY') + ' ' + timeString;
    }

    function getSpdaPending() {
        $.ajax({
            url: "<?= base_url('operasional/ajax/getSpdaPending') ?>",
            type: "GET",
            dataType: "JSON",
            success: function(rs) {
                if (rs.success) {
                    data = rs.data;
                    var count = Object.keys(data).length;
                    if (count > 0) {
                        $('#noti-count1').html(count);
                        $('#noti-count2').html('Belum divalidasi (' + count + ')');
                        $('#noti-data').find('.simplebar-content').html('');
                    } else {
                        $('#noti-count1').html('0');
                        $('#noti-count2').html('Belum divalidasi (0)');
                        $('#noti-data').find('.simplebar-content').html('<p class="text-center">Tidak ada data</p>');
                    }

                    for (let i = 0; i < count; i++) {
                        created_at = data[i].created_at; //2023-07-27 15:20:30
                        fromNow = moment(created_at, "YYYY-MM-DD HH:mm:ss").fromNow();

                        spdastatus = data[i].spda_status;
                        if (spdastatus == '0') {
                            var spda_status = '<span class="badge bg-danger">Dalam Perjalanan</span>';
                        } else if (spdastatus == '1') {
                            var spda_status = '<span class="badge bg-warning">Belum Verifikasi</span>';
                        } else if (spdastatus == '2') {
                            var spda_status = '<span class="badge bg-success">Sudah Verifikasi</span>';
                        }

                        if (<?= $role_code == 'bpw' ? 'true' : 'false' ?>) {
                            var btnRoleNotif = `<a class="text-reset notification-item" data-id="` + data[i].id + `" onclick="spdaOtorisasi(` + data[i].id + `)">`;
                        } else {
                            var btnRoleNotif = `<a class="text-reset notification-item" data-id="` + data[i].id + `" onclick="spdaDetail(` + data[i].id + `)">`;
                        }
                        var notif_data = ``;
                        notif_data = `
                        ${btnRoleNotif}
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <img src="<?= base_url() ?>/assets/img/DISHUB-Logo.png" class="rounded-circle avatar-sm" alt="user-pic" style="height: 35px;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">` + data[i].po_name + `</h6>
                                    ` + spda_status + `
                                    <div class="font-size-13 text-muted">
                                        <p class="mb-1"><strong>Trayek : </strong>` + data[i].route_name + `</p>
                                        <p class="mb-1"><strong>Trip : </strong>` + data[i].trip_name + `</p>
                                        <p class="mb-1"><strong>Pengemudi : </strong>` + data[i].driver_name + `</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>` + fromNow + `</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        `;

                        $('#noti-data').find('.simplebar-content').append(notif_data);

                    }
                } else {
                    $('#noti-count1').html('');
                    $('#noti-count2').html('Belum divalidasi (0)');
                    $('#noti-data').find('.simplebar-content').html('<p class="text-center">Tidak ada data</p>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error get data from ajax');
            }
        });
    }

    function spdaDetail(id) {
        let $this = $(this);
        let data = {
            id: id,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }

        Swal.fire({
            title: "",
            icon: "info",
            text: "Proses mengambil data, mohon ditunggu...",
            didOpen: function() {
                Swal.showLoading()
            }
        });

        $.ajax({
            headers: {
                'customref': '<?= base_url('operasional/spda') ?>'
            },
            url: "<?= base_url('operasional/action/spda_detail') ?>",
            type: 'POST',
            data: data,
            dataType: 'html',
            success: function(result) {
                Swal.close();
                $('#spda-detail-modal-body').html(result);
                $('#spda-detail-modal').modal('show');
            },
            error: function() {
                Swal.close();
                Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
            }
        });
    }

    function spdaOtorisasi(id) {
        let $this = $(this);
        let data = {
            id: id,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }
        $('#id_pdf').val(id);
        Swal.fire({
            title: "",
            icon: "info",
            text: "Proses mengambil data, mohon ditunggu...",
            didOpen: function() {
                Swal.showLoading()
            }
        });

        $.ajax({
            headers: {
                'customref': '<?= base_url('operasional/spda') ?>'
            },
            url: "<?= base_url('operasional/action/spda_detail') ?>",
            type: 'POST',
            data: data,
            dataType: 'html',
            success: function(result) {
                Swal.close();
                $('#verifikasi-modal-body').html(result);
                $('#verifikasi-modal').modal('show');
            },
            error: function() {
                Swal.close();
                Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
            }
        });
    }

    function exportPDF() {
        $('#form_export_pdf').submit(function(e) {
            e.preventDefault();
            let $this = $(this);
            var data = $this.serialize();
            data += "&<?= csrf_token() ?>=<?= csrf_hash() ?>";

            Swal.fire({
                title: "",
                icon: "info",
                text: "Proses mengambil data, mohon ditunggu...",
                didOpen: function() {
                    Swal.showLoading()
                }
            });

            $.ajax({
                headers: {
                    'customref': '<?= base_url('operasional/spda') ?>'
                },
                url: "<?= base_url('operasional/action/spda_pdf') ?>",
                type: 'post',
                data: data,
                dataType: 'json',
                success: function(result) {
                    Swal.close();
                    if (result.success) {
                        Swal.fire({
                            title: "Berhasil export PDF",
                            icon: "success",
                            showCancelButton: true,
                            confirmButtonText: "Download",
                            cancelButtonText: "Tutup",
                            reverseButtons: true
                        }).then(function(result) {
                            if (result.value) {
                                window.open(result.url, '_blank');
                            }
                        });
                    } else {
                        Swal.fire('Error', result.message, 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                }
            });
        });
        $('#form_export_pdf').submit();
    }

    function fetchNotifications() {
        $.ajax({
            url: "<?= base_url('main/action/getNotifications') ?>",
            method: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $("#notif-content").html(`<div class="p-4 d-flex justify-content-center align-items-center text-center" >
                                            <i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  
                                        </div>`);
            },
            success: function(response) {
                if (response.success) {
                    $("#notif-content").css({
                        "max-height": "230px",
                        "overflow-y": "auto"
                    });
                    $("#notif-content").html('');
                    $("#notif-content").html(response.data);
                    if (response.n_data > 0) {
                        $("#notif-count, #notif-unread").show();
                        if (response.n_data > 9) {
                            $("#notif-count").text('9+');
                        } else {
                            $("#notif-count").text(response.n_data);
                        }
                        $("#notif-unread").text('Tandai telah dibaca (' + response.n_data + ')');
                    } else {
                        $("#notif-count, #notif-unread").hide();
                    }
                } else {
                    $("#notif-content").html(`
                    <div class="font-size-13 text-muted text-center">
                        <p class="mb-1">Tidak ada notifikasi</p>
                    </div>`);
                }
            },
            error: function() {
                // $("#notif-loading").remove();
                $("#notif-content").html(`
                <div class="font-size-13 text-muted text-center">
                    <p class="mb-1 text-danger">Gagal memuat notifikasi</p>
                </div>
            `);
            }
        });
    }
</script>