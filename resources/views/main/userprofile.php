<?php $session = \Config\Services::session(); ?>
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="mb-3">
            <div class="card shadow-sm" style="border-radius: 12px;">
                <div class="card-body">

                    <ul id="tab" class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#tab-form" role="tab" aria-selected="true">
                                <span class="d-block d-sm-none"><i class="fab fa-wpforms"></i></span>
                                <span class="d-none d-sm-block">Update Password</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content p-3 text-muted">
                        <div class="tab-pane active" id="tab-form" role="tabpanel">
                            <div class="card-body">
                                <form data-plugin="parsley" data-option="{}" id="form" novalidate>
                                    <input type="hidden" class="form-control" id="id" name="id" value="<?= $session->get('id') ?>">
                                    <?= csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label>Nama</label>
                                                <input type="text" class="form-control" id="user_web_name" name="user_web_name"
                                                    required autocomplete="off" placeholder="Nama lengkap user" value="<?= $session->get('name') ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label>Email</label>
                                                <input type="email" class="form-control" id="user_web_email"
                                                    name="user_web_email" required autocomplete="off" placeholder="email user" value="<?= $session->get('email') ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label>Username</label>
                                                <input type="text" class="form-control" id="user_web_username"
                                                    name="user_web_username" required autocomplete="off"
                                                    placeholder="username user untuk login" value="<?= $session->get('username') ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label>Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="user_web_password" name="user_web_password"
                                                        required minlength="8" autocomplete="new-password" placeholder="Masukkan password baru">
                                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                                <small id="passwordStrength" class="form-text text-muted"></small>
                                                <div id="passwordMeter rounded shadow-sm" class="progress mt-2" style="height: 8px;">
                                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col-md-12 text-center">
                                            <button class="btn btn-dark" type="reset">Reset</button>
                                            <button class="btn btn-primary" type="submit">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    const url = '<?= base_url() . "/" . uri_segment(0) . "/action/" . uri_segment(1) ?>';
    const url_ajax = '<?= base_url() . "/" . uri_segment(0) . "/ajax" ?>';

    var coreEvents;

    $(document).ready(function () {
        coreEvents = new CoreEvents();
        coreEvents.url = url;
        coreEvents.ajax = url_ajax;
        coreEvents.csrf = { "<?= csrf_token() ?>": "<?= csrf_hash() ?>" };

        coreEvents.insertHandler = {
            placeholder: 'Berhasil update password',
            afterAction: function(result) {
                let timerInterval;
                Swal.fire({
                    title: "Berhasil update password",
                    html: "Silahkan login kembali.<br>Anda akan logout dalam <b></b> detik.",
                    timer: 5000,
                    timerProgressBar: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                        const timer = Swal.getPopup().querySelector("b");
                        timerInterval = setInterval(() => {
                            timer.textContent = `${Math.ceil(Swal.getTimerLeft() / 1000)}`;
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        window.location.href = '<?= base_url() . "/auth/action/logout" ?>';
                    }
                });
            }
        }

        coreEvents.resetHandler = {
            action: function() {
                $('#passwordStrength').text('');
                $('#passwordMeter .progress-bar').css('width', '0%');
            }
        }

        $('#user_web_password').on('input paste', function() {
            let password = $(this).val();
            let strength = calcPassStrength(password);

            let strengthText = '';
            let strengthClass = '';
            let progressBarWidth = 0;

            if (strength >= 80) {
                strengthText = 'Password sangat kuat';
                strengthClass = 'bg-success';
                progressBarWidth = 100;
            } else if (strength >= 60) {
                strengthText = 'Password kuat';
                strengthClass = 'bg-info';
                progressBarWidth = 75;
            } else if (strength >= 40) {
                strengthText = 'Password cukup kuat';
                strengthClass = 'bg-warning';
                progressBarWidth = 50;
            } else {
                strengthText = 'Password sangat lemah';
                strengthClass = 'bg-danger';
                progressBarWidth = 25;
            }

            $('#passwordStrength').text(strengthText);
            $('.progress-bar')
                .removeClass('bg-success bg-info bg-warning bg-danger')
                .addClass(strengthClass)
                .css('width', progressBarWidth + '%');
        });

        $('#togglePassword').on('click', function() {
            const passwordField = $('#user_web_password');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });

        coreEvents.load();
    });

    function calcPassStrength(password) {
        let score = 0;
        if (password.length >= 8) score += 20;
        if (/[A-Z]/.test(password)) score += 20;
        if (/[a-z]/.test(password)) score += 20;
        if (/[0-9]/.test(password)) score += 20;
        if (/[\W_]/.test(password)) score += 20;
        return score;
    }
</script>
