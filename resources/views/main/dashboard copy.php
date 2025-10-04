<?php
$session = \Config\Services::session();
// print_r('<pre>');
// print_r($session->get()); 
// print_r('</pre>');
// die;
$role_code = $session->get('role_code');
$spv_id = $session->get('spv_id');
$defaultPassword = $session->get('password');
?>

<head>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>
<style>
  .row {
    display: flex;
    flex-wrap: wrap;
  }

  .card {
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  .card-body {
    flex-grow: 1;
  }

  #map {
    height: 600px;
  }

  .popup-ringkasan {
    font-size: 12px;
    line-height: 1.4;
    max-width: 220px;
  }

  #chart_lokasi_barang {
    width: 100%;
    height: 500px;
  }

  #bar_chart {
    width: 100%;
    height: 400px;
  }

  .page-title-box {
    display: none !important;
  }

  .group-input {
    border: solid #d8d8d8 1px;
    border-radius: 3px;
    align-items: center;
  }

  .maplibregl-ctrl-top-right,
  .maplibregl-ctrl-bottom-left {
    display: none !important;
  }

  .separator {
    display: inline-block;
    width: 1px;
    height: 25px;
    background-color: #aaaaaa;
    margin: 0 8px;
  }

  #carouselExampleCaptions .carousel-control-prev,
  #carouselExampleCaptions .carousel-control-next {
    top: calc(50% + 10px);
    transform: translateY(-50%);
  }

  /* Animasi untuk card muncul */
  .fade-in {
    opacity: 0;
    animation: fadeIn 1s forwards;
  }

  @keyframes fadeIn {
    0% {
      opacity: 0;
    }

    100% {
      opacity: 1;
    }
  }
  
  /* Styles for catatan karyawan card */
  #catatan-karyawan-card {
    transition: all 0.2s ease;
  }
  
  .note-item {
    cursor: pointer;
    border-left: 3px solid transparent;
    margin-bottom: 0.75rem;
    padding: 0.25rem 0;

  }
  
  .note-item:hover {
    border-left-color: #5156be;
    background-color: rgba(81, 86, 190, 0.05); 
    transform: translateX(2px);
  }
  
  .note-item .card {
    margin-bottom: 0;
    border: 1px solid rgba(0,0,0,.085);
    box-shadow: 0 1px 3px rgba(0,0,0,.05);
    transition: all 0.2s ease;
  }
  
  .note-item:hover .card {
    border-color: rgba(81, 86, 190, 0.2);
    box-shadow: 0 2px 5px rgba(81, 86, 190, 0.1);
  }
  
  .note-item-all {
    cursor: pointer;
    transition: all 0.2s ease;
    padding: 0.5rem;
    border-radius: 0.375rem;
    margin-top: 0.5rem;
    border-top: 1px solid rgba(0,0,0,.085);
  }
  
  .note-item-all:hover {
    background-color: rgba(81, 86, 190, 0.05);
    transform: translateX(4px);
  }
  

  .note-item .badge {
    padding: 0.35em 0.65em;
    font-size: 0.75em;
  }
  

  /* Disable hover effects for Surat Edaran list items */
  .activity-list.list-surat-edaran {
    transition: background-color .15s ease, transform .12s ease, box-shadow .12s ease;
  }
  .activity-list.list-surat-edaran:hover {
    background-color: rgba(0,0,0,0.02);
    transform: translateX(2px);
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
  }
  .list-surat-edaran.hover-shadow:hover {
    background: transparent !important;
    box-shadow: none !important;
    border-left-color: transparent !important;
  }
  .activity-list.list-surat-edaran .timeline-list-item h5,
  .activity-list.list-surat-edaran .timeline-list-item p {
    color: inherit;
  }
  .activity-list.list-surat-edaran .timeline-list-item .meta a:hover {
    text-decoration: none;
  }
  /* Remove transition on surat edaran items */
  .activity-list.list-surat-edaran {
    transition: none !important;
  }
  
</style>
<!-- <h2 class='mt-5'>Peta Lokasi Masalah</h2> -->
<div class="d-flex flex-column gap-3 mb-3">
  <?php if ($role_code == 'sad') { ?>

    <div class="row flex-fill">
      <!-- <div class="col-12 col-lg-6">
      <div class="card bg-success h-100 shadow" style="border-radius: 1rem;">
        <div class="card-header border-0 rounded-top-3 bg-success" style="border-radius: 1rem;">
          <h3 class="card-title d-flex flex-column">
            <span class="mt-3 fw-bolder fs-5 text-white"><i class="bx bxs-filter-alt me-3"></i>FILTER DATA</span>
          </h3>
        </div>
        <div class="card-body pt-3 text-white">
          <div class="row">
            <div class="col-12 col-lg-4">
              <label for="spda_date">Tanggal</label>
              <div class="input-group">
                <input type="text" class="form-control" name="periode" id="periode">
                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="mb-3">
                <label class="form-label" for="kota_id">Kota</label>
                <select name="kota_id" id="kota_id" class="form-select sel2"></select>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="mb-3">
                <label class="form-label" for="karyawan_id">Karyawan</label>
                <select name="karyawan_id" id="karyawan_id" class="form-select sel2"></select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> -->
      <div class="col-12">
        <div class="card h-100 bg-white shadow" style="border-radius: 1rem;">
          <div class="card-body d-flex p-0">
            <div class="flex-grow-1 p-4 d-flex flex-column justify-content-center align-items-start bg-header_carousel">
              <h4 class="text-danger fw-bolder m-0">Informasi</h4>
              <p class="text-dark my-4 fs-5 fw-bold">
                Selamat Datang Di Aplikasi Sobat NGI
                <br> Saat ini kami akan terus berusaha mengoptimalkan layanan Sobat NGI.
                <br><i><small>Informasi by Admin</small></i>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<!-- <div class="card border border-primary">
    <div class="card-header bg-transparent border-primary">
        <h5 class="my-0 text-primary"><i class="bx bxs-filter-alt me-3"></i>FILTER DATA</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-lg-4">
                <label for="spda_date">Tanggal</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="periode" id="periode">
                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="mb-3">
                    <label class="form-label" for="kota_id">Kota</label>
                    <select name="kota_id" id="kota_id" class="form-select sel2"></select>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="mb-3">
                    <label class="form-label" for="karyawan_id">Karyawan</label>
                    <select name="karyawan_id" id="karyawan_id" class="form-select sel2"></select>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="row flex-fill mb-3">
  <div class="col-md-12">
    <div class="accordion" id="accordionAnalitics1">
      <div class="accordion-item bg-white shadow" style="border-radius: 1rem;">
        <h2 class="accordion-header" id="analitics1-headingOne">
          <button class="accordion-button text-success fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#analitics1-collapseOne" aria-expanded="true" aria-controls="analitics1-collapseOne" style="border-radius: 1rem 1rem 0rem 0rem;">
            <span>MAP WILAYAH <br>
              <small class="text-muted">Klik Lokasi untuk menampilkan data</small>
            </span>
          </button>
        </h2>
        <div id="analitics1-collapseOne" class="accordion-collapse collapse show" aria-labelledby="analitics1-headingOne" data-bs-parent="#accordionAnalitics1">
          <div class="accordion-body">
            <div id="map" class="shadow rounded-3 mb-3"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mb-3">
  <!-- <div class="row flex-fill mb-3"> -->
  <div class="col-12 col-lg-6">
    <div class="accordion" id="accordionAnalitics2">
      <div class="accordion-item bg-white shadow" style="border-radius: 1rem;">
        <h2 class="accordion-header" id="analitics1-headingOne2">
          <button class="accordion-button text-success fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#analitics1-collapseOne2" aria-expanded="true" aria-controls="analitics1-collapseOne2" style="border-radius: 1rem 1rem 0rem 0rem;">
            <span>KETERSEDIAAN BARANG <br>
              <!-- <small class="text-muted">Klik Lokasi untuk menampilkan data</small> -->
            </span>
          </button>
        </h2>
        <div id="analitics1-collapseOne2" class="accordion-collapse collapse show" aria-labelledby="analitics1-headingOne2" data-bs-parent="#accordionAnalitics2">
          <div class="accordion-body">
            <div id="bar_chart" style="height: 400px;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- </div> -->

  <!-- <div class="row flex-fill mb-3"> -->
  <div class="col-12 col-lg-6">
    <div class="accordion" id="accordionAnalitics3">
      <div class="accordion-item bg-white shadow" style="border-radius: 1rem;">
        <h2 class="accordion-header" id="analitics1-headingOne3">
          <button class="accordion-button text-success fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#analitics1-collapseOne3" aria-expanded="true" aria-controls="analitics1-collapseOne3" style="border-radius: 1rem 1rem 0rem 0rem;">
            <span>CHART ALOKASI DEVICE <br>
              <!-- <small class="text-muted">Klik Lokasi untuk menampilkan data</small> -->
            </span>
          </button>
        </h2>
        <div id="analitics1-collapseOne3" class="accordion-collapse collapse show" aria-labelledby="analitics1-headingOne3" data-bs-parent="#accordionAnalitics3">
          <div class="accordion-body">
            <div id="chartdiv" style="width: 100%; height: 400px;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- </div> -->
</div>
<?php } else { ?>

  <!-- Filter Tanggal  -->
  <div class="row flex-fill">
    <div class="col-12 col-lg-6">
      <div class="card bg-success h-100 shadow" style="border-radius: 1rem;">
        <div class="card-header border-0 rounded-top-3 bg-success" style="border-radius: 1rem;">
          <h3 class="card-title d-flex flex-column">
            <span class="mt-3 fw-bolder fs-5 text-white"><i class="bx bxs-filter-alt me-3"></i>FILTER DATA</span>
          </h3>
        </div>
        <div class="card-body pt-3 text-white">
          <div class="row">
            <div class="col-12 col-lg-11">
              <label for="filter_tgl">Tanggal</label>
              <div class="input-group">
                <input type="text" class="form-control" id="filter_tgl" name="filter_tgl" placeholder="DD-MM-YYYY s/d DD-MM-YYYY" required autocomplete="off">
                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
              </div>
            </div>
            <div class="col-lg-1 col-md-12 text-center ">
              <label for="reset_filter_tgl"></label>
              <button class="btn" id="reset_filter_tgl"><i class="fa fa-sync text-light"></i><br> <span class="text-light">Reset</span></button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-6">
      <div class="card h-100 bg-white shadow" style="border-radius: 1rem;">
        <div class="card-body d-flex p-0">
          <div class="flex-grow-1 p-4 d-flex flex-column justify-content-center align-items-start bg-header_carousel">
            <h4 class="text-danger fw-bolder m-0">Informasi</h4>
            <p class="text-dark my-4 fs-5 fw-bold">
              Selamat Datang di Aplikasi Sistem Kepegawaian NGI
              <br> Saat ini kami akan terus berusaha mengoptimalkan layanan Sistem Kepegawaian NGI.
              <br><i><small>Informasi by Admin</small></i>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br>

  <?php if ($role_code == 'ceo' || $role_code == 'coo' || $role_code == 'admsp' || $role_code == 'hr' || $role_code == 'shr') { // BoD dan SuperAdmin
  ?>
    <div class="row" id="cardSummarySemuaDepartemen">

    </div>
    <div class="row">
      <!-- Total Karyawan per Divisi -->
      <div class="col-xl-6">
        <div class="card card-h-100">
          <div class="card-header">
            <h4 class="card-title mb-0">Total Karyawan per Divisi</h4>
          </div>
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-sm">
                <div id="pieChartTotalKaryawanPerDivisi_bod" data-colors='["#2ab57d", "#5156be", "#fd625e", "#4ba6ef", "#ffbf53"]' class="apex-charts"></div>
              </div>
              <div class="col-sm align-self-center">
                <div class="mt-4 mt-sm-0">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Detail Karyawan per Departemen  -->
      <div class="col-xl-6">
        <div class="card card-h-100">
          <div class="card-header">
            <h4 class="card-title mb-0">Data Karyawan per Departemen</h4>
          </div>
          <div class="card-body" id="bodyTotalKaryawanSemuaJabatan_bod">

          </div>
        </div>
      </div>
    </div>
    <br>
    <?php if ($role_code == 'ceo' || $role_code == 'coo' || $role_code == 'admsp') { // BoD dan SuperAdmin
    ?>
      <!-- DataTable Pengajuan Pinjaman yg Sedang Diproses  -->
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Daftar Pengajuan Pinjaman Terbaru</h4>
            </div>
            <div class="card-body pt-3">
              <div class="table-responsive">
                <table id="datatablePengajuanPinjamanTerbaru_bod" class="table table-theme table-row v-middle mb-0">
                  <thead>
                    <tr>
                      <th><span>#</span></th>
                      <th><span>Jabatan</span></th>
                      <th><span>Nama</span></th>
                      <th><span>Tanggal Pengajuan</span></th>
                      <th><span>Plafon</span></th>
                      <th><span>Jangka Waktu Pembayaran</span></th>
                      <th><span>Verifikasi</span></th>
                      <th><span>Status</span></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
    <?php } else { ?>
      <?php if ($role_code == 'hr' || $role_code == 'shr') { // HR and SHR 
      ?>
      <!-- HR Dashboard Cards -->
      <div class="row mb-4">

    <!-- Kotak Suara Card -->
        <div class="col-xl-8">
          <div class="card shadow-sm h-100">
            <div class="card-header d-flex align-items-center">
              <h4 class="card-title mb-0">
                <i class="fas fa-comment-alt text-primary me-2"></i>Kotak Suara
              </h4>
            </div>
            <div class="card-body">
              <div class="row">
                <!-- Section Keluhan -->
                <div class="col-md-6 border-end">
                  <h5 class="fw-bold mb-3"><a href="<?= base_url() ?>/simpeg/keluhan" class="text-decoration-none text-dark"><i class="fas fa-exclamation-circle text-warning me-2"></i>Keluhan</a></h5>
                  <div class="d-flex gap-3 mb-3 flex-wrap">
                    <div>
                      <span class="badge bg-warning" id="keluhan-menunggu">0</span>
                      <small class="text-muted ms-1">Menunggu Review</small>
                    </div>
                    <div>
                      <span class="badge bg-success" id="keluhan-ditanggapi">0</span>
                      <small class="text-muted ms-1">Ditanggapi</small>
                    </div>
                    <div>
                      <span class="badge bg-danger" id="keluhan-ditutup">0</span>
                      <small class="text-muted ms-1">Ditutup</small>
                    </div>
                    <!-- <div>
                      <span class="badge bg-secondary" id="keluhan-total">0</span>
                      <small class="text-muted ms-1">Total</small>
                    </div> -->
                  </div>
                  <div>
                    <h5 class="fs-14 mt-4">Keluhan per Divisi</h5>
                    <div id="keluhan-by-divisi-chart" data-colors='["#2ab57d", "#5156be", "#fd625e", "#ffbf53"]' class="apex-charts mt-3" style="height: 200px;"></div>
                  </div>
                </div>


                 <!-- Section Aduan -->
                <div class="col-md-6 border-end">
                  <h5 class="fw-bold mb-3"><a href="<?= base_url() ?>/simpeg/aduan" class="text-decoration-none text-dark"><i class="fas fa-exclamation-circle text-warning me-2"></i>Aduan</a></h5>
                  <div class="d-flex gap-3 mb-3 flex-wrap">
                    <div>
                      <span class="badge bg-warning" id="aduan-menunggu">0</span>
                      <small class="text-muted ms-1">Menunggu Review</small>
                    </div>
                    <div>
                      <span class="badge bg-success" id="aduan-ditanggapi">0</span>
                      <small class="text-muted ms-1">Ditanggapi</small>
                    </div>
                    <!-- <div>
                      <span class="badge bg-danger" id="keluhan-ditutup">0</span>
                      <small class="text-muted ms-1">Ditutup</small>
                    </div> -->
                    <!-- <div>
                      <span class="badge bg-secondary" id="keluhan-total">0</span>
                      <small class="text-muted ms-1">Total</small>
                    </div> -->
                  </div>
                  <div>
                    <h5 class="fs-14 mt-4">Aduan per Divisi</h5>
                    <div id="aduan-by-divisi-chart" data-colors='["#2ab57d", "#5156be", "#fd625e", "#ffbf53"]' class="apex-charts mt-3" style="height: 200px;"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                  <i class="fas fa-info-circle me-1"></i>Data Aduan dan Keluhan
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- HR Catatan Karyawan Summary Card -->
        <div class="col-xl-4">
          <div class="card shadow-sm h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="card-title mb-0">
            <a href="<?= base_url() ?>/simpeg/cakar" class="text-decoration-none text-dark">
              <i class="fas fa-sticky-note text-primary me-2"></i>Ringkasan Catatan
            </a>
          </h4>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-4">
          <div class="card border mb-0">
            <div class="card-body py-2">
              <div class="d-flex align-items-center">
            <div class="avatar-sm flex-shrink-0">
              <span class="avatar-title bg-soft-success text-success rounded fs-3">
                <i class="fas fa-thumbs-up"></i>
              </span>
            </div>
            <div class="flex-grow-1 ms-2">
              <p class="text-muted mb-1 small">Baik</p>
              <h5 class="mb-0" id="cakar-summary-baik">-</h5>
            </div>
              </div>
            </div>
          </div>
            </div>
            <div class="col-md-4">
          <div class="card border mb-0">
            <div class="card-body py-2">
              <div class="d-flex align-items-center">
            <div class="avatar-sm flex-shrink-0">
              <span class="avatar-title bg-soft-danger text-danger rounded fs-3">
                <i class="fas fa-thumbs-down"></i>
              </span>
            </div>
            <div class="flex-grow-1 ms-2">
              <p class="text-muted mb-1 small">Buruk</p>
              <h5 class="mb-0" id="cakar-summary-buruk">-</h5>
            </div>
              </div>
            </div>
          </div>
            </div>
            <div class="col-md-4">
          <div class="card border mb-0">
            <div class="card-body py-2">
              <div class="d-flex align-items-center">
            <div class="avatar-sm flex-shrink-0">
              <span class="avatar-title bg-soft-info text-info rounded fs-3">
                <i class="fas fa-info-circle"></i>
              </span>
            </div>
            <div class="flex-grow-1 ms-2">
              <p class="text-muted mb-1 small">Info</p>
              <h5 class="mb-0" id="cakar-summary-info">-</h5>
            </div>
              </div>
            </div>
          </div>
            </div>
          </div>
          
          <div>
            <h5 class="fs-14 mb-3">Catatan per Divisi</h5>
            <div id="cakar-by-divisi-chart" data-colors='["#2ab57d", "#5156be", "#fd625e", "#ffbf53"]' class="apex-charts" dir="ltr" style="height: 250px;"></div>
          </div>
        </div>
        <div class="card-footer">
          <div class="d-flex justify-content-between align-items-center">
            <div class="small text-muted">
          <i class="fas fa-info-circle me-1"></i>Data Catatan Karyawan 
            </div>
          </div>
        </div>
          </div>
        </div>
      </div>
      <?php } ?>
      <div class="row">
        <div class="col-xl-8">
          <div class="card">
            <div class="card-header align-items-center d-flex">
              <h4 class="card-title mb-0 flex-grow-1">Surat Edaran</h4>
            </div><!-- end card header -->
            <div class="card-body px-0">
              <div class="px-3" data-simplebar style="max-height: 352px; min-height: 352px;">
                <ul class="list-unstyled activity-wid mb-0" id="list-surat">
                  <!-- Data akan diisi lewat AJAX -->
                </ul>
              </div>
            </div><!-- end card body -->
            <div class="card-footer">
              <small class="text-muted">
                <i class="mdi mdi-information-outline"></i> Tembusan Surat:
                <span class="d-inline-block rounded-circle bg-primary" style="width: 8px; height: 8px;"></span> Semua karyawan,
                <span class="d-inline-block rounded-circle bg-success" style="width: 8px; height: 8px;"></span> Divisi tertentu,
                <span class="d-inline-block rounded-circle bg-warning" style="width: 8px; height: 8px;"></span> Karyawan tertentu.
              </small>
            </div>

          </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-4">
          <div class="card bg-primary text-white shadow-primary card-h-100">
            <div class="card-body p-0">
              <div id="carouselExampleCaptions" class="carousel slide text-center widget-carousel position-relative" data-bs-ride="carousel">
                <div class="carousel-indicators" id="panduan-indicators"></div> <!-- Titik indikator -->
                <div class="carousel-inner" id="panduan-container"></div> <!-- Isi dari JS -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
    <?php }  ?>
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title mb-0">Grafik Pengajuan Semua Divisi</h4>
          </div>
          <div class="card-body">
            <div id="columnChartPengajuanSemuaDivisi_bod" data-colors='["#2ab57d", "#5156be", "#fd625e", "#ffbf53"]' class="apex-charts" dir="ltr"></div>
          </div>
        </div>
        <!--end card-->
      </div>
    </div>
    <br>

  <?php } else { ?>

    <!-- Info Pribadi -->
    <div class="row">
      <div class="col-lg-12">
        <div class="alert alert-info alert-top-border alert-dismissible fade show mb-3" role="alert">
          <h5 class="text-center">INFORMASI PRIBADI</h5>
        </div>
      </div>
    </div>
    <!-- 4 Card  -->
    <div class="row g-3">
        <!-- Card 1: Total Lembur -->
        <div class="col-lg-3">
          <div class="accordion-item shadow-sm border rounded-3">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionLemburKaryawan" aria-expanded="false" aria-controls="accordionLemburKaryawan">
                <div class="d-flex justify-content-between align-items-center w-100">
                  <div>
                    <span class="text-muted lh-1 d-block mb-3">Total Lembur</span>
                    <h3 class="mb-1 d-flex flex-wrap align-items-end gap-1">
                      <span class="fw-bold text-dark fs-3" id="sum-lembur-jam"><i class="fa fa-spinner fa-pulse fa-fw"></i></span>
                      <span class="text-muted fs-5">jam</span>
                      <span class="separator"></span>
                      <span class="fw-semibold text-success fs-5">Rp</span>
                      <span class="fw-bold text-success fs-3" id="sum-lembur-pendapatan"><i class="fa fa-spinner fa-pulse fa-fw"></i></span>
                    </h3>
                  </div>
                  <div class="d-flex align-items-center gap-2 pe-2 fs-1 text-primary">
                    <i class="mdi mdi-clock-outline"></i>
                  </div>
                </div>
              </button>
            </h2>
            <div id="accordionLemburKaryawan" class="accordion-collapse collapse accordionKaryawan" data-jenis="totalLembur" aria-labelledby="headingOne">
              <div class="accordion-body">

              </div>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="col-lg-3">
          <div class="accordion-item shadow-sm border rounded-3">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionCutiKaryawan" aria-expanded="false" aria-controls="accordionCutiKaryawan">
                <div class="d-flex justify-content-between align-items-center w-100">
                  <div>
                    <span class="text-muted lh-1 d-block mb-3">Total Cuti Tahunan</span>
                    <h3 class="mb-1 d-flex align-items-baseline gap-2">
                      <span class="fw-bold text-primary fs-3" id="sum-cuti-tahunan-terpakai"><i class="fa fa-spinner fa-pulse fa-fw"></i></span>
                      <span class="text-muted fs-5">/ <span id="batas-cuti-tahunan">12</span> hari</span>
                    </h3>
                  </div>
                  <div class="d-flex align-items-center gap-2 pe-2 fs-1 text-primary">
                    <i class="mdi mdi-calendar-remove"></i>
                  </div>
                </div>
              </button>
            </h2>
            <div id="accordionCutiKaryawan" class="accordion-collapse collapse accordionKaryawan" data-jenis="totalCuti" aria-labelledby="headingTwo">
              <div class="accordion-body">

              </div>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="col-lg-3">
          <div class="accordion-item shadow-sm border rounded-3">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionDLKaryawan" aria-expanded="false" aria-controls="accordionDLKaryawan">
                <div class="d-flex justify-content-between align-items-center w-100">
                  <div>
                    <span class="text-muted lh-1 d-block mb-3">Total Dinas Luar</span>
                    <h3 class="mb-1 d-flex align-items-end gap-1">
                      <span class="fw-bold text-dark fs-3" id="sum-dinas-luar"><i class="fa fa-spinner fa-pulse fa-fw"></i></span>
                      <span class="text-muted fs-5">kali</span>
                    </h3>
                  </div>
                  <div class="d-flex align-items-center gap-2 pe-2 fs-1 text-primary">
                    <i class="fa fa-street-view"></i>
                  </div>
                </div>
              </button>
            </h2>
            <div id="accordionDLKaryawan" class="accordion-collapse collapse accordionKaryawan" data-jenis="totalDL" aria-labelledby="headingThree">
              <div class="accordion-body">

              </div>
            </div>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="col-lg-3">
          <div class="accordion-item shadow-sm border rounded-3">
            <h2 class="accordion-header" id="headingFour">
              <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionPinjamanKaryawan" aria-expanded="false" aria-controls="accordionPinjamanKaryawan">
                <div class="d-flex justify-content-between align-items-center w-100">
                  <div>
                    <span class="text-muted lh-1 d-block mb-3">Total Pinjaman</span>
                    <h3 class="mb-1 d-flex align-items-end gap-1">
                      <span class="fw-semibold text-success fs-5">Rp</span>
                      <span class="fw-bold text-success fs-3" id="sum-pinjaman"><i class="fa fa-spinner fa-pulse fa-fw"></i></span>
                    </h3>
                  </div>
                  <div class="d-flex align-items-center gap-2 pe-2 fs-1 text-primary">
                    <i class="mdi mdi-cash-multiple"></i>
                  </div>
                </div>
              </button>
            </h2>
            <div id="accordionPinjamanKaryawan" class="accordion-collapse collapse accordionKaryawan" data-jenis="totalPinjaman" aria-labelledby="headingFour">
              <div class="accordion-body">

              </div>
            </div>
          </div>
        </div>
      </div>

    <br>
    <!-- SE, Catatan Karyawan dan Dokumen  -->
    <div class="row">
      <div class="col-xl-3">
      <div class="card h-100 shadow-sm" id="catatan-karyawan-card">
        <div class="card-header d-flex align-items-center" id="catatan-karyawan-header" role="button" style="cursor:pointer;">
            <h5 class="mb-0 flex-grow-1">
              <i class="fas fa-sticky-note text-primary me-2"></i>Catatan Karyawan
            </h5>
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-primary" id="catatan-count">0</span>
            </div>
          </div>
        <div class="card-body px-0">
          <div class="px-3">
            <div id="catatan-karyawan-preview">
              <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 mb-0">Memuat catatan...</p>
              </div>
            </div>
            <!-- Template for note items -->
            <template id="note-item-template">
              <div class="note-item mb-3">
                <div class="card">
                  <div class="card-body p-3">
                    <h6 class="card-title mb-2">{{title}}</h6>
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="badge bg-soft-{{type_class}} text-{{type_class}} px-2">
                        <i class="fas fa-{{type_icon}} me-1"></i>{{type}}
                      </span>
                      <small class="text-muted">{{date}}</small>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>
        <div class="card-footer">
          <div class="d-flex align-items-center text-muted fs-13">
            <i class="fas fa-info-circle me-2"></i>
            <small>Ini merupakan 3 catatan terbaru Anda</small>
          </div>
        </div>
      </div>
      </div><!-- end col -->

      <div class="col-xl-6">
      <div class="card h-100">
        <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Surat Edaran</h4>
        </div><!-- end card header -->
        <div class="card-body px-0">
        <div class="px-3" data-simplebar style="max-height: 260px; min-height: 220px;">
          <ul class="list-unstyled activity-wid mb-0" id="list-surat">
          <!-- Data akan diisi lewat AJAX -->
          </ul>
        </div>
        </div><!-- end card body -->
        <div class="card-footer">
        <small class="text-muted">
          <i class="mdi mdi-information-outline"></i> Tembusan Surat:
          <span class="d-inline-block rounded-circle bg-primary" style="width: 8px; height: 8px;"></span> Semua karyawan,
          <span class="d-inline-block rounded-circle bg-success" style="width: 8px; height: 8px;"></span> Divisi tertentu,
          <span class="d-inline-block rounded-circle bg-warning" style="width: 8px; height: 8px;"></span> Karyawan tertentu.
        </small>
        </div>

      </div><!-- end card -->
      </div><!-- end col -->

      <div class="col-xl-3">
      <div class="card bg-primary text-white shadow-primary card-h-100">
        <div class="card-body p-0">
        <div id="carouselExampleCaptions" class="carousel slide text-center widget-carousel position-relative" data-bs-ride="carousel">
          <div class="carousel-indicators" id="panduan-indicators"></div> <!-- Titik indikator -->
          <div class="carousel-inner" id="panduan-container" style="min-height: 220px;"></div> <!-- Isi dari JS -->

        </div>
        </div>
        </div>
      </div>
      </div>
      </div>
    </div>
    <br>

    <!-- Grafik Lembur n Cuti  -->
    <div class="row">
      <div class="col-xl-8 d-flex">
        <div class="card h-100 w-100">
          <div class="card-header">
            <h4 class="card-title mb-0">Grafik Lembur Karyawan</h4>
          </div>
          <div class="card-body">
            <div id="chart_lembur_karyawan" data-colors='["#2ab57d", "#5156be", "#fd625e"]' class="apex-charts" dir="ltr"></div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 d-flex">
        <div class="card h-100 w-100">
          <div class="card-header">
            <h4 class="card-title mb-0">Cuti Tahunan Karyawan</h4>
          </div>
          <div class="card-body d-flex flex-column justify-content-center align-items-center">
            <div id="cuti_tahunan_wrapper" class="text-center w-100">
              <div id="cuti_tahunan" data-colors='["#fd625e", "#5156be"]' class="apex-charts mx-auto" style="max-width:220px;" dir="ltr"></div>
              <div id="cuti_tahunan_legend" class="mt-3 w-100 text-center" style="font-size:14px;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Kalender (kalo jadi) -->



    <br>
    <?php if ($role_code == 'adm' || $role_code == 'ftc') { // Admin 
    ?>
      <!-- Informasi terkait Admin -->
      <div class="row">
        <div class="col-lg-12">
          <div class="alert alert-info alert-top-border alert-dismissible fade show mb-3" role="alert">
            <h5 class="text-center">INFORMASI TERKAIT ADMIN</h5>
          </div>
        </div>
      </div>

      <!-- DataTable Pengajuan yg Sedang Diproses  -->
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Daftar Pengajuan Terbaru</h4>
            </div>
            <div class="card-body pt-3">
              <div class="table-responsive">
                <table id="datatablePengajuanTerbaru_adm" class="table table-theme table-row v-middle mb-0">
                  <thead>
                    <tr>
                      <th><span>#</span></th>
                      <th><span>Role</span></th>
                      <th><span>Nama</span></th>
                      <th><span>Jenis Pengajuan</span></th>
                      <th><span>Tanggal Pengajuan</span></th>
                      <th><span>Keperluan</span></th>
                      <th><span>Verifikasi</span></th>
                      <th><span>Status</span></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title mb-0">Grafik Pengajuan Lembur Semua Role</h4>
            </div>
            <div class="card-body">
              <div id="chartPengajuanLemburSemuaJabatan_adm"
                data-colors='["#fd625e", "#f5b849", "#4ba6ef", "#2ab57d"]'
                class="apex-charts" dir="ltr"></div>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title mb-0">Grafik Pengajuan Cuti Semua Role</h4>
            </div>
            <div class="card-body">
              <div id="chartPengajuanCutiSemuaJabatan_adm"
                data-colors='["#fd625e", "#f5b849", "#4ba6ef", "#2ab57d"]'
                class="apex-charts" dir="ltr"></div>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title mb-0">Grafik Pengajuan Dinas Luar Semua Role</h4>
            </div>
            <div class="card-body">
              <div id="chartPengajuanDLSemuaJabatan_adm"
                data-colors='["#fd625e", "#f5b849", "#4ba6ef", "#2ab57d"]'
                class="apex-charts" dir="ltr"></div>
            </div>
          </div>
        </div>
      </div>
      <br>

      <?php if ($role_code == 'ftc') { ?>
        <!-- Informasi terkait Finance -->
        <div class="row">
          <div class="col-lg-12">
            <div class="alert alert-info alert-top-border alert-dismissible fade show mb-3" role="alert">
              <h5 class="text-center">INFORMASI TERKAIT FINANCE</h5>
            </div>
          </div>
        </div>
        <!-- DataTable Pengajuan Pinjaman yg Sedang Diproses  -->
        <div class="row">
          <div class="col-xl-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Daftar Pengajuan Pinjaman Terbaru</h4>
              </div>
              <div class="card-body pt-3">
                <div class="table-responsive">
                  <table id="datatablePengajuanPinjamanTerbaru_ftc" class="table table-theme table-row v-middle mb-0">
                    <thead>
                      <tr>
                        <th><span>#</span></th>
                        <th><span>Role</span></th>
                        <th><span>Nama</span></th>
                        <th><span>Tanggal Pengajuan</span></th>
                        <th><span>Plafon</span></th>
                        <th><span>Jangka Waktu Pembayaran</span></th>
                        <th><span>Verifikasi</span></th>
                        <th><span>Status</span></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <br>
        <!-- Grafik Pengajuan Pinjaman Semua Jabatan -->
        <div class="row">
          <div class="col-xl-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title mb-0">Grafik Pengajuan Pinjaman Semua Jabatan</h4>
              </div>
              <div class="card-body">
                <div id="chartPengajuanPinjamanPerJabatan_ftc"
                  data-colors='["#fd625e", "#f5b849", "#4ba6ef", "#2ab57d"]'
                  class="apex-charts" dir="ltr"></div>
              </div>
            </div>
          </div>
        </div>
        <br>
      <?php } ?>
    <?php } else if ($spv_id == null) { // SPV tiap Divisi 
    ?>
      <!-- Informasi terkait SPV -->
      <div class="row">
        <div class="col-lg-12">
          <div class="alert alert-info alert-top-border alert-dismissible fade show mb-3" role="alert">
            <h5 class="text-center">INFORMASI TERKAIT SPV</h5>
          </div>
        </div>
      </div>
      <!-- Chart Total Karyawan dan Total Pengajuan per Jabatan  -->
      <div class="row">
        <div class="col-xl-6">
          <div class="card card-h-100">
            <div class="card-header">
              <h4 class="card-title mb-0">Total Karyawan per Jabatan</h4>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
              <div id="pieChartTotalKaryawanPerJabatan" data-colors='["#2ab57d", "#5156be", "#fd625e", "#4ba6ef", "#ffbf53"]' class="apex-charts"></div>
            </div>
          </div><!-- end card -->
        </div><!-- end col -->
        <div class="col-xl-6">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title mb-0">Grafik Pengajuan per Jabatan</h4>
            </div>
            <div class="card-body">
              <div id="columnChartPengajuanJabatan" data-colors='["#2ab57d", "#fd625e"]' class="apex-charts" dir="ltr"></div>
            </div>
          </div>
          <!--end card-->
        </div>
      </div>
      <br>
      <!-- DataTable Pengajuan yg Belum Diproses -->
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Daftar Pengajuan Terbaru</h4>
            </div>
            <div class="card-body pt-3">
              <div class="table-responsive">
                <table id="datatablePengajuanTerbaru_spv" class="table table-theme table-row v-middle mb-0">
                  <thead>
                    <tr>
                      <th><span>#</span></th>
                      <th><span>Role</span></th>
                      <th><span>Nama</span></th>
                      <th><span>Jenis Pengajuan</span></th>
                      <th><span>Tanggal Pengajuan</span></th>
                      <th><span>Keperluan</span></th>
                      <th><span>Verifikasi</span></th>
                      <th><span>Status</span></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-xl-12">
          <div class="card h-100 w-100">
            <div class="card-header">
              <h4 class="card-title mb-0">Grafik Pengajuan Lembur per Hari (by Role)</h4>
            </div>
            <div class="card-body">
              <div id="columnChartPengajuanLemburPerHari"
                data-colors='["#2ab57d", "#5156be", "#fd625e", "#4ba6ef", "#ffbf53"]'
                class="apex-charts" dir="ltr"></div>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-xl-12">
          <div class="card h-100 w-100">
            <div class="card-header">
              <h4 class="card-title mb-0">Grafik Pengajuan Cuti per Hari (by Role)</h4>
            </div>
            <div class="card-body">
              <div id="columnChartPengajuanCutiPerHari"
                data-colors='["#2ab57d", "#5156be", "#fd625e", "#4ba6ef", "#ffbf53"]'
                class="apex-charts" dir="ltr"></div>
            </div>
          </div>
        </div>
      </div>
      <br>
    <?php } else { // karyawan 
    ?>
      <!-- Employee Notes Widget moved to top row with Surat Edaran -->
    <?php } ?>
  <?php } ?>
<?php } ?>

<!-- Additional content -->
<!-- modal xl  -->
<div id="modalDetail" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
    <div class="modal-content" style="border-radius: 8px;">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalDetailBody">
      </div>
      <div class="modal-footer d-flex justify-content-center" id="modalFooter">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
      </div>
    </div>
  </div>
</div>

<div id="modalDetail2" class="modal fade" tabindex="-1" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
    <div class="modal-content" style="border-radius: 8px;">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalDetail2Body">
      </div>
      <div class="modal-footer d-flex justify-content-center" id="modalFooter">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- modal -->
<div class="modal fade" id="modal-log" tabindex="-1" aria-labelledby="modal-form" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title w-100 text-center" id="modal-title">Detail data complain</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body overflow-auto" style="height: 50vh;">
        <ul class="list-group list-group-flush p-3" id="modal-body"></ul>
      </div>
    </div>
  </div>
</div>
<!-- end modal -->

<script src="assets/js/app.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- MetisMenu -->
<script src="https://cdn.jsdelivr.net/npm/metismenu"></script>
<!-- mep -->
<script src="https://cdn.maptiler.com/maptiler-sdk-js/v2.0.3/maptiler-sdk.umd.min.js"></script>
<link href="https://cdn.maptiler.com/maptiler-sdk-js/v2.0.3/maptiler-sdk.css" rel="stylesheet" />

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>


<!-- amchart -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/locales/de_DE.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/germanyLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/fonts/notosans-sc.js"></script>

<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>


<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
  const url = '<?= base_url() . "/main/action/" . uri_segment(1) ?>';
  const url_ajax = '<?= base_url() . "/main/ajax" ?>';
  const baseUrl = '<?= base_url() ?>';
  const role_code = <?= json_encode($role_code) ?>;
  const spv_id = <?= json_encode($spv_id) ?>;

  // console.log('Role Code:', role_code);
  // console.log('SPV ID:', spv_id);

  var tgl_mulai = null;
  var tgl_selesai = null;

  var start = moment().subtract(1, 'month');
  var end = moment();


  var chartLemburPerBulan_karyawan = null;
  var chartCutiTahunan_karyawan = null;
  
  function loadChartCutiTahunan(data) {
    // Dispose existing chart if it exists
    if (chartCutiTahunan_karyawan) {
      chartCutiTahunan_karyawan.dispose();
    }

    // Create root element
    var root = am5.Root.new("chartCutiTahunanKaryawan");

    // Set themes
    root.setThemes([am5themes_Animated.new(root)]);

    // Create chart
    chartCutiTahunan_karyawan = root.container.children.push(
      am5xy.XYChart.new(root, {
        panX: false,
        panY: false,
        wheelX: "none",
        wheelY: "none",
        layout: root.verticalLayout
      })
    );

    // Add legend
    var legend = chartCutiTahunan_karyawan.children.push(
      am5.Legend.new(root, {
        centerX: am5.p50,
        x: am5.p50
      })
    );

    // Create axes
    var xAxis = chartCutiTahunan_karyawan.xAxes.push(
      am5xy.CategoryAxis.new(root, {
        categoryField: "bulan",
        renderer: am5xy.AxisRendererX.new(root, {
          cellStartLocation: 0.1,
          cellEndLocation: 0.9
        }),
        tooltip: am5.Tooltip.new(root, {})
      })
    );

    xAxis.data.setAll(data);

    var yAxis = chartCutiTahunan_karyawan.yAxes.push(
      am5xy.ValueAxis.new(root, {
        renderer: am5xy.AxisRendererY.new(root, {}),
        min: 0
      })
    );

    // Add series
    function createSeries(name, fieldName, color) {
      var series = chartCutiTahunan_karyawan.series.push(
        am5xy.ColumnSeries.new(root, {
          name: name,
          xAxis: xAxis,
          yAxis: yAxis,
          valueYField: fieldName,
          categoryXField: "bulan",
          tooltip: am5.Tooltip.new(root, {
            labelText: "[bold]{name}[/]: {valueY}"
          })
        })
      );

      series.columns.template.setAll({
        cornerRadiusTL: 5,
        cornerRadiusTR: 5,
        fillOpacity: 0.8
      });

      series.data.setAll(data);
      series.appear(1000);
    }

    // Create series for different types of cuti
    createSeries("Cuti Terpakai", "terpakai", am5.color("#ff6b6b"));
    createSeries("Sisa Cuti", "sisa", am5.color("#51cf66"));

    // Make stuff animate on load
    chartCutiTahunan_karyawan.appear(1000, 100);
  }

  var chartPengajuanPinjamanPerJabatan_ftc = null;
  var chartPengajuanLemburSemuaJabatan_adm = null;
  var chartPengajuanCutiSemuaJabatan_adm = null;
  var chartPengajuanDLSemuaJabatan_adm = null;
  var chartTotalKaryawanPerJabatan_spv = null;
  var chartPengajuanPerJabatan_spv = null;
  var chartPengajuanLemburPerHari_spv = null;
  var chartPengajuanDinasLuarPerHari_spv = null;
  var chartPengajuanCutiPerHari_spv = null;
  var chartTotalKaryawanPerDivisi_bod = null;
  var chartPengajuanSemuaDivisi_bod = null;

  var dataStart = 0;
  var coreEvents;

  const select2Array = [];


  $(document).ready(function() {
    coreEvents = new CoreEvents();
    coreEvents.url = url;
    coreEvents.ajax = url_ajax;
    coreEvents.csrf = {
      "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
    };

    var password = '<?= $defaultPassword ?>';
    var defaultPassword = '6c7b5db4a59292148e91a25165d55534f4aa3c65139e5556a1459c8fb991dabb';
    if (password === defaultPassword) {
      Swal.fire({
        title: 'Informasi Penting',
        text: 'Anda menggunakan password default. Silakan ubah password Anda di halaman profil.',
        icon: 'info',
        confirmButtonText: 'OK',
        customClass: {
          confirmButton: 'btn btn-primary'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          // Redirect ke halaman profil
          window.location.href = 'https://devel74.nginovasi.id/sobat_hss/main/userprofile';
        }
      });
    }


    $('#filter_tgl').daterangepicker({
      startDate: start,
      endDate: end,
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      showDropdowns: true,
      alwaysShowCalendars: true,
      autoUpdateInput: false,
      maxDate: moment(),
      locale: {
        format: 'DD-MM-YYYY',
        separator: ' s/d ',
        applyLabel: 'Pilih',
        cancelLabel: 'Batal',
        fromLabel: 'Dari',
        toLabel: 'Ke',
        weekLabel: 'W',
        firstDay: 0,
        daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
        monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
      }
    }).on('apply.daterangepicker', function(ev, picker) {
      let startDate = picker.startDate.format('DD-MM-YYYY');
      let endDate = picker.endDate.format('DD-MM-YYYY');
      let formattedDate = startDate + ' s/d ' + endDate;
      $('#filter_tgl').val(formattedDate);
      tgl_mulai = picker.startDate.format('YYYY-MM-DD');
      tgl_selesai = picker.endDate.format('YYYY-MM-DD');

      $('#accordionLemburKaryawan, #accordionCutiKaryawan, #accordionDLKaryawan, #accordionPinjamanKaryawan').collapse('hide');

      loadAjax();
    });

    // Set nilai awal saat halaman dimuat
    $('#filter_tgl').val(start.format('DD-MM-YYYY') + ' s/d ' + end.format('DD-MM-YYYY'));
    tgl_mulai = start.format('YYYY-MM-DD');
    tgl_selesai = end.format('YYYY-MM-DD');


    $(document)
    .on('click', '#reset_filter_tgl', function() {
      $('#filter_tgl').val(null);
      tgl_mulai = null;
      tgl_selesai = null;
      $('#accordionLemburKaryawan, #accordionCutiKaryawan, #accordionDLKaryawan, #accordionPinjamanKaryawan').collapse('hide');
      loadAjax();
    }).on('hidden.bs.collapse', '.accordionKaryawan', function() {
      var jenisAccordion = $(this).attr('id');
      $('#' + jenisAccordion + ' .accordion-body').html(`
            <div class="d-flex justify-content-center align-items-center">
              <i class="fa fa-spinner fa-pulse "></i>
            </div>
          `);
    }).on('shown.bs.collapse', '.accordionKaryawan', function() {
      // var jenisAccordion = $(this).data('jenis');
      var jenisAccordion = $(this).attr('id');
      var endpoint = '';
      if (jenisAccordion == 'accordionLemburKaryawan') {
        endpoint = url + 'getTotalStatusLembur_karyawan';
      } else if (jenisAccordion == 'accordionCutiKaryawan') {
        endpoint = url + 'getTotalStatusCuti_karyawan';
      } else if (jenisAccordion == 'accordionDLKaryawan') {
        endpoint = url + 'getTotalStatusDL_karyawan';
      } else if (jenisAccordion == 'accordionPinjamanKaryawan') {
        endpoint = url + 'getTotalStatusPinjaman_karyawan';
      }
      $.ajax({
        url: endpoint,
        type: 'POST',
        dataType: 'json',
        data: {
          tgl_mulai: tgl_mulai,
          tgl_selesai: tgl_selesai,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          $('#' + jenisAccordion + ' .accordion-body').html(`
            <div class="d-flex justify-content-center align-items-center">
              <i class="fa fa-spinner fa-pulse "></i>
            </div>
          `);
        },
        success: function(res) {
          if (res.success) {
            var data = res.data;
            var content = ``;
            var statusInfo = {
              belum_diproses: {
                title: 'Belum Diproses',
                color: 'info'
              },
              sedang_diproses: {
                title: 'Proses Verifikasi',
                color: 'warning'
              },
              disetujui: {
                title: 'Disetujui',
                color: 'success'
              },
              ditolak: {
                title: 'Ditolak',
                color: 'danger'
              }
            };
            for (var key in statusInfo) {
              var total = data[key] || 0;
              content += `<tr class="align-middle">
                    <td>${statusInfo[key].title}</td>
                    <td class="text-end">
                      <button type="button" class="btn btn-sm btn-soft-${statusInfo[key].color} rounded-pill px-3 daftarPengajuan" data-status="${key}" data-jenis="${jenisAccordion}" style="min-width: 60px;"><strong>${parseInt(total)}</strong></button>
                    </td>
                  </tr>`;
            }
            let tableContent = `
                <div class="table-responsive overflow-visible">
                  <table class="table table-hover table-sm m-0">
                    <tbody>
                      ${content}
                    </tbody>
                  </table>
                </div>
              `;
            let body = $('#' + jenisAccordion + ' .accordion-body');
            body.fadeOut(100, function() {
              body.html(tableContent).fadeIn(300);
            });
          } else {
            $('#' + jenisAccordion + ' .accordion-body').html(`<div class="d-flex justify-content-center align-items-center">?</div>`);
          }
        },
        error: function(xhr, status, error) {
          console.log(xhr.responseText);
        }
      });
    }).on('click', '.daftarPengajuan', function() {
      var jenis = $(this).data('jenis');
      var status = $(this).data('status');
      var endpoint = '';
      var pengajuan = '';
      if (jenis == 'accordionLemburKaryawan') {
        endpoint = url + 'getDaftarLemburPerStatus_karyawan';
        pengajuan = 'Lembur';
      } else if (jenis == 'accordionCutiKaryawan') {
        endpoint = url + 'getDaftarCutiPerStatus_karyawan';
        pengajuan = 'Cuti';
      } else if (jenis == 'accordionDLKaryawan') {
        endpoint = url + 'getDaftarDLPerStatus_karyawan';
        pengajuan = 'Dinas Luar';
      } else if (jenis == 'accordionPinjamanKaryawan') {
        endpoint = url + 'getDaftarPinjamanPerStatus_karyawan';
        pengajuan = 'Pinjaman';
      }
      $.ajax({
        url: endpoint,
        type: 'POST',
        dataType: 'json',
        data: {
          status: status,
          tgl_mulai: tgl_mulai,
          tgl_selesai: tgl_selesai,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          Swal.fire({
            title: "",
            icon: "info",
            text: "Proses mengambil data, mohon ditunggu...",
            didOpen: function() {
              Swal.showLoading()
            }
          });
        },
        success: function(res) {
          Swal.close();
          if (res.success) {
            if (tgl_mulai != null && tgl_selesai != null && pengajuan != 'Cuti') {
              $('#' + res.atr.modal + ' .modal-title').text('Daftar ' + pengajuan + ' Karyawan pada ' + tgl_mulai + ' s/d ' + tgl_selesai);
            } else {
              $('#' + res.atr.modal + ' .modal-title').text('Daftar ' + pengajuan + ' Karyawan');
            }
            $('#' + res.atr.modal_body).html(res.html);
            $('#' + res.atr.modal).modal('toggle');
          } else {
            Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
          }
        },
        error: function(xhr, status, error) {
          console.log(xhr.responseText);
          Swal.close();
          Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
        }
      });
    }).on('click', '.list-surat-edaran', function() {
      var suratId = $(this).data('id');

      $.ajax({
        url: url + 'getSuratEdaranDetail_karyawan',
        type: 'POST',
        dataType: 'json',
        data: {
          id: suratId,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          Swal.fire({
            title: "",
            icon: "info",
            text: "Proses mengambil data, mohon ditunggu...",
            didOpen: function() {
              Swal.showLoading()
            }
          });
        },
        success: function(res) {
          Swal.close();
          if (res.success) {
            let badge = ``;
            if (res.data.is_deleted == 0) {
              badge = `<span class="badge bg-success text-center">Berlaku</span>`;
            } else if (res.data.is_deleted == 1) {
              badge = `<span class="badge bg-danger text-center">Tidak Berlaku</span>`;
            }
            $('#' + res.atr.modal + ' .modal-title').html(`Detail Surat Edaran ` + badge);
            $('#' + res.atr.modal_body).html(res.html);
            $('#' + res.atr.modal).modal('toggle');
          } else {
            Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
          }
        },
        error: function(xhr, status, error) {
          console.log(xhr.responseText);
          Swal.close();
          Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
        }
      });
    }).on('click', '.detailPengajuan', function() {
      var jenis = $(this).data('jenis');
      var dataId = $(this).data('id');
      var endpoint = '';
      var pengajuan = '';
      if (jenis == 'dinas_luar') {
        endpoint = url + 'getDetailDL_karyawan';
        pengajuan = 'Dinas Luar';
      } else if (jenis == 'pinjaman') {
        endpoint = url + 'getDetailPinjaman_karyawan';
        pengajuan = 'Pinjaman';
      }
      $.ajax({
        url: endpoint,
        type: 'POST',
        dataType: 'json',
        data: {
          id: dataId,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          Swal.fire({
            title: "",
            icon: "info",
            text: "Proses mengambil data, mohon ditunggu...",
            didOpen: function() {
              Swal.showLoading()
            }
          });
        },
        success: function(res) {
          Swal.close();
          if (res.success) {
            $('#modalDetail').modal('hide');
            $('#' + res.atr.modal + ' .modal-title').text('Detail ' + pengajuan + ' Karyawan');
            $('#' + res.atr.modal_body).html(res.html);
            $('#' + res.atr.modal).modal('toggle');
          } else {
            Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
          }
        },
        error: function(xhr, status, error) {
          console.log(xhr.responseText);
          Swal.close();
          Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
        }
      });
    }).on('click', '.allPengajuanPerDepartemen', function() {
      var idDepartemen = $(this).data('iddepartemen');
      $.ajax({
        url: url + 'getAllPengajuanPerDepartemen_bod',
        type: 'POST',
        dataType: 'json',
        data: {
          idDepartemen: idDepartemen,
          tgl_mulai: tgl_mulai,
          tgl_selesai: tgl_selesai,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          Swal.fire({
            title: "",
            icon: "info",
            text: "Proses mengambil data, mohon ditunggu...",
            didOpen: function() {
              Swal.showLoading()
            }
          });
        },
        success: function(res) {
          Swal.close();
          if (res.success) {
            if (tgl_mulai != null && tgl_selesai != null) {
              $('#' + res.atr.modal + ' .modal-title').text('Daftar Pengajuan Karyawan pada ' + tgl_mulai + ' s/d ' + tgl_selesai);
            } else {
              $('#' + res.atr.modal + ' .modal-title').text('Daftar Pengajuan Karyawan');
            }
            $('#' + res.atr.modal_body).html(res.html);
            $('#' + res.atr.modal).modal('toggle');
          } else {
            Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
          }
        },
        error: function(xhr, status, error) {
          console.log(xhr.responseText);
          Swal.close();
          Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
        }
      });
    }).on('click', '.satuJenisPengajuanPerDepartemen', function() {
      var jenis = $(this).data('jenis');
      var status = $(this).data('status');
      var idDepartemen = $(this).data('iddepartemen');

      var endpoint = '';
      var pengajuan = '';
      if (jenis == 'Lembur') {
        endpoint = url + 'getDaftarLemburPerDepartemen_bod';
        pengajuan = 'Lembur';
      } else if (jenis == 'Cuti') {
        endpoint = url + 'getDaftarCutiPerDepartemen_bod';
        pengajuan = 'Cuti';
      } else if (jenis == 'Dinas Luar') {
        endpoint = url + 'getDaftarDLPerDepartemen_bod';
        pengajuan = 'Dinas Luar';
      } else if (jenis == 'Pinjaman Karyawan') {
        endpoint = url + 'getDaftarPinjamanPerDepartemen_bod';
        pengajuan = 'Pinjaman';
      }
      $.ajax({
        url: endpoint,
        type: 'POST',
        dataType: 'json',
        data: {
          status: status,
          idDepartemen: idDepartemen,
          tgl_mulai: tgl_mulai,
          tgl_selesai: tgl_selesai,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          Swal.fire({
            title: "",
            icon: "info",
            text: "Proses mengambil data, mohon ditunggu...",
            didOpen: function() {
              Swal.showLoading()
            }
          });
        },
        success: function(res) {
          Swal.close();
          if (res.success) {
            if (tgl_mulai != null && tgl_selesai != null) {
              $('#' + res.atr.modal + ' .modal-title').text('Daftar ' + pengajuan + ' Karyawan pada ' + tgl_mulai + ' s/d ' + tgl_selesai);
            } else {
              $('#' + res.atr.modal + ' .modal-title').text('Daftar ' + pengajuan + ' Karyawan');
            }
            $('#' + res.atr.modal_body).html(res.html);
            $('#' + res.atr.modal).modal('toggle');
          } else {
            Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
          }
        },
        error: function(xhr, status, error) {
          console.log(xhr.responseText);
          Swal.close();
          Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
        }
      });
    }).on('click', '.dataKaryawan', function() {
      var idDepartemen = $(this).data('iddepartemen');
      var idJabatan = $(this).data('idjabatan');
      $.ajax({
        url: url + 'getDataKaryawan_bod',
        type: 'POST',
        dataType: 'json',
        data: {
          idDepartemen: idDepartemen,
          idJabatan: idJabatan,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          Swal.fire({
            title: "",
            icon: "info",
            text: "Proses mengambil data, mohon ditunggu...",
            didOpen: function() {
              Swal.showLoading()
            }
          });
        },
        success: function(res) {
          Swal.close();
          if (res.success) {
            $('#' + res.atr.modal + ' .modal-title').text('Daftar Karyawan');
            $('#' + res.atr.modal_body).html(res.html);
            $('#' + res.atr.modal).modal('toggle');
          } else {
            Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
          }
        },
        error: function(xhr, status, error) {
          console.log(xhr.responseText);
          Swal.close();
          Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
        }
      });
    }).on('click', '.detailKaryawan', function() {
      var dataId = $(this).data('id');

      $.ajax({
        url: url + 'getDetailKaryawan_bod',
        type: 'POST',
        dataType: 'json',
        data: {
          id: dataId,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          Swal.fire({
            title: "",
            icon: "info",
            text: "Proses mengambil data, mohon ditunggu...",
            didOpen: function() {
              Swal.showLoading()
            }
          });
        },
        success: function(res) {
          Swal.close();
          if (res.success) {
            $('#modalDetail').modal('hide');
            $('#' + res.atr.modal + ' .modal-title').text('Detail Karyawan');
            $('#' + res.atr.modal_body).html(res.html);
            $('#' + res.atr.modal).modal('toggle');
          } else {
            Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
          }
        },
        error: function(xhr, status, error) {
          console.log(xhr.responseText);
          Swal.close();
          Swal.fire('Perhatian!', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'warning');
        }
      });
    }).on('click', '#accordionExample .accordion-button', function(e) {
      // prevent the default bootstrap toggle so we can control all panels together
      e.preventDefault();
      var panels = $('#accordionLemburKaryawan, #accordionCutiKaryawan, #accordionDLKaryawan, #accordionPinjamanKaryawan');
      // if any panel is open, close all; otherwise open all
      var anyOpen = panels.filter('.show').length > 0;
      var action = anyOpen ? 'hide' : 'show';
      panels.each(function() {
        $(this).collapse(action);
      });
    }).on('click', '.note-item', function() {
      const noteId = $(this).data('id');
      showCatatanKaryawanDetail(noteId);
    }).on('click', '.note-item-all', function() {
      const noteId = $(this).data('id');
      $('#modalDetail').modal('hide');
      setTimeout(() => {
        showCatatanKaryawanDetail(noteId);
      }, 500);
    }).on('click', '#catatan-karyawan-card', function(e) {
      if (!$(e.target).closest('.note-item').length) {
        showAllCatatanKaryawan();
      }
    }).on('click', '#lihat-semua-catatan', function(e) {
      e.preventDefault();
      showAllCatatanKaryawan();
    });

    coreEvents.load(null, null, null, null, null);
    if (role_code === 'hr' || role_code === 'shr') { // Karyawan
      loadCakarSummary();
      loadRekapKeluhanDashboard();    // <-- pindah ke sini
      loadRekapAduanDashboard(); 
    };
    if (role_code !== 'sad' && role_code !== 'admin' && role_code !== 'hr' && role_code !== 'shr') {
      loadEmployeeNotes();
    }
    loadAjax();
  });
  // Allow multiple panels to be open by removing the accordion parent attribute
  $('#accordionLemburKaryawan, #accordionCutiKaryawan, #accordionDLKaryawan, #accordionPinjamanKaryawan').removeAttr('data-bs-parent');

  function loadAjax() {
    var ajaxReq = [];
    if (role_code == 'ceo' || role_code == 'coo' || role_code == 'admsp' || role_code == 'hr' || role_code == 'shr') { // BoD atau SuperAdmin
      var getPengajuanSemuaDepartemen_bod = $.ajax({
        url: url + 'getPengajuanSemuaDepartemen_bod',
        method: 'POST',
        dataType: 'json',
        data: {
          tgl_mulai: tgl_mulai,
          tgl_selesai: tgl_selesai,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          // Menampilkan animasi loading untuk seluruh konten card
          $('#cardSummarySemuaDepartemen').html(`
          <div class="col-12">
            <div class="card">
              <div class="card-body text-center">
                <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                <p>Memuat data...</p>
              </div>
            </div>
          </div>
        `);
        },
        success: function(res) {
          if (res.success) {
            let html = '';

            res.data.forEach(dept => {
              let namaDept = dept.Nama_Departemen || 'Tidak Diketahui';
              let idDept = dept.ID_Departemen || null;
              let jenisPengajuan = JSON.parse(dept.Jenis_Pengajuan);
              let totalAll = dept.Total_Pengajuan || 0;

              html += `
          <div class="col-xl-3 fade-in mb-3">
            <div class="card position-relative">
              <div class="position-relative">
                <img class="card-img-top img-fluid" src="assets/images/small/img-2.jpg"
                  alt="Card image cap"
                  style="max-height: 175px; object-fit: cover; filter: brightness(0.5);">
                <div class="position-absolute top-50 start-50 translate-middle text-white text-center" style="line-height: 1.2;">
                  <div class="fw-semibold" style="font-size: 0.9rem; margin-bottom: 2px;">DEPARTEMEN</div>
                  <div class="fw-bold" style="font-size: 1.4rem; letter-spacing: 0.5px;">${namaDept}</div>
                </div>
              </div>

              <ul class="list-group list-group-flush">
                <li class="list-group-item text-center text-muted">
                  ${(tgl_mulai && tgl_selesai) ? `Periode ${moment(tgl_mulai).format('DD-MM-YYYY')} s/d ${moment(tgl_selesai).format('DD-MM-YYYY')}` : 'Semua Periode'}
                </li>
                <li class="list-group-item">
                  <h3 class="mb-1 d-flex justify-content-center flex-wrap align-items-end gap-1 allPengajuanPerDepartemen" data-iddepartemen="${idDept}">
                    <span class="fw-bold text-dark fs-3">${totalAll}</span>
                    <span class="text-muted fs-5">pengajuan</span>
                  </h3>
                </li>
        `;

              jenisPengajuan.forEach(jenis => {
                let nama = jenis.jenis_pengajuan;
                let total = jenis['Belum Diproses'] + jenis['Sedang Diproses'] + jenis['Disetujui'] + jenis['Ditolak'];

                html += `
            <li class="list-group-item px-3 py-2">
              <div class="row">
                <div class="col-6 border-end d-flex align-items-center justify-content-center">
                  <div class="fw-medium text-muted">${nama}</div>
                </div>
                <div class="col-6">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center text-muted fs-5 satuJenisPengajuanPerDepartemen" data-jenis="${nama}" data-iddepartemen="${idDept}" data-status="">${total} pengajuan</li>
                    <li class="list-group-item px-0 py-1" style="font-size: 0.8rem;">
                      <div class="d-flex justify-content-between text-info satuJenisPengajuanPerDepartemen" data-jenis="${nama}" data-iddepartemen="${idDept}" data-status="belum_diproses">
                        <div><i class="fas fa-circle text-info me-1" style="font-size: 0.5rem;"></i>Belum Diproses</div>
                        <div>(${jenis['Belum Diproses']})</div>
                      </div>
                      <div class="d-flex justify-content-between text-warning satuJenisPengajuanPerDepartemen" data-jenis="${nama}" data-iddepartemen="${idDept}" data-status="sedang_diproses">
                        <div><i class="fas fa-circle text-warning me-1" style="font-size: 0.5rem;"></i>Sedang Diproses</div>
                        <div>(${jenis['Sedang Diproses']})</div>
                      </div>
                      <div class="d-flex justify-content-between text-success satuJenisPengajuanPerDepartemen" data-jenis="${nama}" data-iddepartemen="${idDept}" data-status="disetujui">
                        <div><i class="fas fa-circle text-success me-1" style="font-size: 0.5rem;"></i>Disetujui</div>
                        <div>(${jenis['Disetujui']})</div>
                      </div>
                      <div class="d-flex justify-content-between text-danger satuJenisPengajuanPerDepartemen" data-jenis="${nama}" data-iddepartemen="${idDept}" data-status="ditolak">
                        <div><i class="fas fa-circle text-danger me-1" style="font-size: 0.5rem;"></i>Ditolak</div>
                        <div>(${jenis['Ditolak']})</div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </li>
          `;
              });

              html += `</ul></div></div>`;
            });

            // Mengubah konten card dengan animasi
            $('#cardSummarySemuaDepartemen').html(html);
          } else {
            $('#cardSummarySemuaDepartemen').html('<p class="text-danger">Gagal mengambil data pengajuan.</p>');
            console.error(res.message);
          }
        },
        error: function(xhr, status, error) {
          $('#cardSummarySemuaDepartemen').html('<p class="text-danger">Terjadi kesalahan saat mengambil data.</p>');
          console.error(error);
        }
      });

      ajaxReq.push(getPengajuanSemuaDepartemen_bod);

      var getTotalKaryawanSemuaJabatan_bod = $.ajax({
        url: url + 'getTotalKaryawanSemuaJabatan_bod',
        type: 'POST',
        dataType: 'json',
        data: {
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          $('#bodyTotalKaryawanSemuaJabatan_bod').html(`
            <div class="p-4 d-flex justify-content-center align-items-center text-center">
              <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>  
            </div>
          `);
        },
        success: function(res) {
          if (res.success) {
            let content = '';
            const colors = ['primary', 'danger', 'warning', 'success', 'info'];

            for (let divisi in res.data) {
              const list = res.data[divisi];
              const totalDivisi = list.reduce((sum, item) => sum + parseInt(item.total_karyawan), 0);

              // Baris judul divisi
              content += `
                <tr>
                  <td style="width: 70%;"><strong>${divisi}</strong></td>
                  <td class="text-center dataKaryawan" data-iddepartemen="${list[0].departemen_id}" data-idjabatan="" style="width: 15%;">${totalDivisi}</td>
                  <td class="text-center" style="width: 15%;">-</td>
                </tr>
              `;

              // Daftar jabatan (flat)
              list.forEach((item, i) => {
                const color = colors[(i + 1) % colors.length];
                const percentage = totalDivisi > 0 ? ((item.total_karyawan / totalDivisi) * 100).toFixed(1) : '0.0';

                content += `
                  <tr>
                    <td style="width: 70%; text-indent: 20px;">
                      <span class="d-inline-block rounded-circle bg-${color} me-2" style="width: 8px; height: 8px;"></span>
                      ${item.user_web_role_name}
                    </td>
                    <td class="text-center text-${color} dataKaryawan" data-iddepartemen="${item.departemen_id}" data-idjabatan="${item.role_id}" style="width: 15%;">${item.total_karyawan}</td>
                    <td class="text-center text-${color}" style="width: 15%;">${percentage}%</td>
                  </tr>
                `;
              });
            }

            $('#bodyTotalKaryawanSemuaJabatan_bod').html(`
              <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-striped table-bordered mb-0">
                  <thead>
                    <tr>
                      <th>Jabatan</th>
                      <th class="text-center">Total</th>
                      <th class="text-center">%</th>
                    </tr>
                  </thead>
                  <tbody>
                    ${content}
                  </tbody>
                </table>
              </div>
            `);
          } else {
            $('#bodyTotalKaryawanSemuaJabatan_bod').html(`<p class="text-center text-danger">Gagal menampilkan data</p>`);
          }
        },
        error: function(xhr) {
          console.error(xhr.responseText);
          $('#bodyTotalKaryawanSemuaJabatan_bod').html(`<p class="text-center text-danger">Gagal memuat data</p>`);
        }
      });

      ajaxReq.push(getTotalKaryawanSemuaJabatan_bod);


      var getChartTotalKaryawanPerDivisi_bod = $.ajax({
        url: url + 'getChartTotalKaryawanPerDivisi_bod',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          var data = response.data;

          var labels = data.map(item => item.divisi_nama);
          var series = data.map(item => parseInt(item.jumlah_karyawan));
          const colors = getRandomColors('#pieChartTotalKaryawanPerJabatan', series.length);

          var options = {
            series: series,
            chart: {
              width: 227,
              height: 227,
              type: 'pie',
            },
            labels: labels,
            colors: colors,
            stroke: {
              width: 0,
            },
            legend: {
              show: false
            },
            responsive: [{
              breakpoint: 480,
              options: {
                chart: {
                  width: 200
                },
              }
            }]
          };
          if (chartTotalKaryawanPerDivisi_bod !== null) {
            chartTotalKaryawanPerDivisi_bod.destroy();
          }

          chartTotalKaryawanPerDivisi_bod = new ApexCharts(document.querySelector("#pieChartTotalKaryawanPerDivisi_bod"), options);
          chartTotalKaryawanPerDivisi_bod.render();

          var detailHTML = "";
          for (var i = 0; i < data.length; i++) {
            var colorClass = colors[i];

            detailHTML +=
              `<div class="mt-4 pt-2">
                <p class="mb-2"><i class="mdi mdi-circle align-middle font-size-10 me-2" style="color:${colorClass};"></i> 
                  ${data[i].divisi_nama}: <span class="ms-2"><strong>${data[i].jumlah_karyawan} Karyawan</strong></span>
                </p>
              </div>`;
          }
          $("#pieChartTotalKaryawanPerDivisi_bod").parent().next().find(".mt-4.mt-sm-0").html(detailHTML);
        }
      });
      ajaxReq.push(getChartTotalKaryawanPerDivisi_bod);


      var getChartPengajuanSemuaDivisi_bod = $.ajax({
        url: url + 'getChartPengajuanSemuaDivisi_bod',
        type: 'POST',
        dataType: 'json',
        data: {
          tgl_mulai: tgl_mulai,
          tgl_selesai: tgl_selesai,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        success: function(response) {
          var data = response.data;

          var categories = data.map(item => item.divisi_nama);
          var cuti = data.map(item => parseInt(item.jml_cuti));
          var dinas = data.map(item => parseInt(item.jml_dinas));
          var lembur = data.map(item => parseInt(item.jml_lembur));
          var pinjaman = data.map(item => parseInt(item.jml_pinjaman));
          var fullDivisi = data.map(item => item.divisi_nama);

          var columnColors = ['#fd625e', '#5156be', '#2ab57d', '#ffbf53']; // sesuai urutan cuti, dinas, lembur, pinjaman

          var options = {
            chart: {
              height: 350,
              type: 'bar',
              stacked: false,
              toolbar: {
                show: false
              }
            },
            plotOptions: {
              bar: {
                horizontal: false,
                columnWidth: '25%',
              }
            },
            dataLabels: {
              enabled: false
            },
            stroke: {
              show: true,
              width: 2,
              colors: ['transparent']
            },
            series: [{
                name: 'Cuti',
                data: cuti
              },
              {
                name: 'Dinas Luar',
                data: dinas
              },
              {
                name: 'Lembur',
                data: lembur
              },
              {
                name: 'Pinjaman Karyawan',
                data: pinjaman
              }
            ],
            colors: columnColors,
            xaxis: {
              categories: categories,
              title: {
                text: 'Divisi',
                style: {
                  fontWeight: '500'
                }
              },
              labels: {
                rotate: 0,
                style: {
                  fontSize: '12px',
                  fontWeight: 400
                },
                formatter: function(val) {
                  return val.length > 15 ? val.substring(0, 15) + '...' : val;
                }
              }
            },
            yaxis: {
              title: {
                text: 'Jumlah Pengajuan',
                style: {
                  fontWeight: '500'
                }
              }
            },
            tooltip: {
              enabled: true,
              shared: false,
              intersect: true,
              custom: function({
                series,
                seriesIndex,
                dataPointIndex,
                w
              }) {
                const divisi = fullDivisi[dataPointIndex];
                const color = w.config.colors[seriesIndex];
                const value = series[seriesIndex][dataPointIndex];
                const name = w.config.series[seriesIndex].name;

                return `
            <div class="apexcharts-tooltip-title" style="text-align:center; font-size:12px; font-weight:500;">
              ${divisi}
            </div>
            <div style="padding: 6px 10px;">
              <span style="display:inline-flex; align-items:center;">
                <span style="background-color: ${color}; width:10px; height:10px; border-radius:50%; margin-right:6px;"></span>
                ${name}:&nbsp;<strong>${value} pengajuan</strong>
              </span>
            </div>
          `;
              }
            },
            grid: {
              borderColor: '#f1f1f1',
            },
            fill: {
              opacity: 1
            }
          };

          if (chartPengajuanSemuaDivisi_bod !== null) {
            chartPengajuanSemuaDivisi_bod.destroy();
          }

          chartPengajuanSemuaDivisi_bod = new ApexCharts(
            document.querySelector("#columnChartPengajuanSemuaDivisi_bod"),
            options
          );

          chartPengajuanSemuaDivisi_bod.render();
        }
      });
      ajaxReq.push(getChartPengajuanSemuaDivisi_bod);
      if (role_code == 'ceo' || role_code == 'coo' || role_code == 'admsp') { // BoD atau SuperAdmin

        var datatablePengajuanPinjamanTerbaru_bod = initDatatable("#datatablePengajuanPinjamanTerbaru_bod", {
          url: url + "datatablePengajuanPinjamanTerbaru_bod",
          csrf: coreEvents.csrf,
          // dom: 'lfrtip' // buat nampilin show x entries
          filter: {
            tgl_mulai: tgl_mulai,
            tgl_selesai: tgl_selesai,
          },
          columns: [{
              data: null,
              className: "text-center align-middle",
              orderable: false,
              width: 1,
              render: function(a, type, data, index) {
                return dataStart + index.row + 1
              }
            },
            {
              data: "user_web_role_name",
              className: "text-start align-middle",
              orderable: true,
              width: 100
            },
            {
              data: "karyawan_nama",
              className: "text-start align-middle",
              orderable: true,
              width: 100
            },
            {
              data: "tgl_pengajuan",
              className: "text-start align-middle",
              orderable: true,
              width: 100
            },
            {
              data: "pinjaman_plafon",
              className: "text-start align-middle",
              orderable: true,
              width: 100
            },
            {
              data: "pinjaman_jangka_waktu",
              className: "text-start align-middle",
              orderable: true,
              width: 100,
            },
            {
              data: "verifikasi",
              className: "text-start align-middle",
              orderable: true,
              width: 100,
              render: function(data, type, row) {
                return `<span class="badge bg-secondary w-100 text-center">${data}</span>`;
              }
            },
            {
              data: "status",
              className: "text-start align-middle",
              orderable: true,
              width: 100,
              render: function(data, type, row) {
                return `<span class="badge bg-warning p-1 w-100 text-center"><i class='fas fa-spinner'></i> <span class="ms-2">${data}</span></span>`;
              }
            },
          ],
        });
        ajaxReq.push(datatablePengajuanPinjamanTerbaru_bod);
      } else {
        var getSuratEdaran_karyawan = $.ajax({
          url: url + 'getSuratEdaran_karyawan',
          type: 'POST',
          dataType: 'json',
          data: {
            // tgl_mulai: tgl_mulai,
            // tgl_selesai: tgl_selesai,
            "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
          },
          beforeSend: function() {
            $('#list-surat').html(`<div class="p-4 d-flex justify-content-center align-items-center text-center" style="height: 300px;">
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>  
              </div>`);

          },
          success: function(response) {
            $('#list-surat').empty();

            const data = response.data;
            if (!data || data.length === 0) {
              $('#list-surat').append(`<li style="height:300px; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-muted">Tidak ada surat edaran</span>
                                  </li>
                            `).hide().fadeIn(500);
            } else {
              data.forEach(function(item, index) {

                let iconClass, bgColor;
                let badgeClass, status;
                switch (item.surat_edaran_tembusan) {
                  case '1':
                    iconClass = 'bx bx-briefcase';
                    bgColor = 'bg-primary';
                    break;
                  case '2':
                    iconClass = 'bx bx-buildings';
                    bgColor = 'bg-success';
                    break;
                  case '3':
                    iconClass = 'bx bx-folder';
                    bgColor = 'bg-warning';
                    break;
                  default:
                    iconClass = 'bx bx-cog';
                    bgColor = 'bg-info';
                    break;
                }

                if (item.is_deleted == 0) {
                  badgeClass = "bg-success";
                  status = "Berlaku";
                } else if (item.is_deleted == 1) {
                  badgeClass = "bg-danger";
                  status = "Tidak Berlaku";
                }

                const isLastItem = index === data.length - 1;

                let listItem = `
                  <li class="activity-list list-surat-edaran hover-shadow ${isLastItem ? '' : 'activity-border'}" data-id="${item.id}" style="cursor: pointer; display:none;">
                    <div class="activity-icon avatar-md">
                      <span class="avatar-title ${bgColor} text-warning rounded-circle text-white">
                        <i class="${iconClass} font-size-18"></i>
                      </span>
                    </div>
                    <div class="timeline-list-item">
                      <div class="d-flex">
                        <div class="flex-grow-1 overflow-hidden me-4">
                          <h5 class="font-size-14 mb-1">${item.surat_edaran_judul}</h5>
                          <p class="text-truncate text-muted font-size-13">${item.surat_edaran_nomor}</p>
                        </div>
                        <div class="flex-shrink-0 text-end me-3">
                          <h6 class="mb-1">${item.karyawan_nama}</h6>
                          <div class="font-size-13"><span class="badge ${badgeClass}"><span>${status}</span></span> ${item.created_at}</div>
                        </div>
                      </div>
                    </div>
                  </li>
                `;

                // Tambahkan ke list lalu animasikan muncul
                const $item = $(listItem).appendTo('#list-surat');
                setTimeout(() => {
                  $item.fadeIn(400);
                }, index * 100); // Delay dikit biar satu-satu muncul
              });
            }
          },

          error: function(xhr, status, error) {
            console.error("Error:", error);
            $('#list-surat').html('<li>Error loading data</li>');
          }
        });
        ajaxReq.push(getSuratEdaran_karyawan);

        var getDokumenPanduan_karyawan = $.ajax({
          url: url + 'getDokumenPanduan_karyawan',
          type: 'POST',
          dataType: 'json',
          data: {
            // tgl_mulai: tgl_mulai,
            // tgl_selesai: tgl_selesai,
            "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
          },
          beforeSend: function() {
            $('#panduan-container').html(`
              <div class="p-4 d-flex justify-content-center align-items-center text-center" style="height: 450px;">
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>  
              </div>
            `);
          },
          success: function(response) {
            const data = response.data;

            // Biar smooth: sembunyikan dulu kontainer sebelum diisi
            $('#panduan-container').fadeOut(200, function() {
              if (!data || data.length === 0) {
                $('#panduan-container').html(`
                <div class="d-flex justify-content-center align-items-center flex-column text-center" style="height: 450px;">
                  <p class="mb-0">Tidak ada dokumen panduan</p>
                </div>
              `).fadeIn(400);

                // Tetap tambahkan tombol panah agar tampil, tapi tidak bisa diklik
                $('#carouselExampleCaptions').append(`
                <button class="carousel-control-prev disabled" type="button" style="opacity: 0.3; pointer-events: none;">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next disabled" type="button" style="opacity: 0.3; pointer-events: none;">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
              `);
                return;
              }
              let html = '';
              let indicators = '';

              data.forEach(function(item, index) {
                html += `
                <div class="carousel-item ${index === 0 ? 'active' : ''}">
                  <div class="p-4 d-flex flex-column justify-content-center align-items-center text-center" style="min-height: 352px;">
                    <h4 class="lh-base fw-normal text-white mb-1">
                      <b>${item.dokumen_panduan_nama}</b>
                    </h4>
                    <p class="text-white-50 font-size-13 mb-3">Dibuat Oleh: ${item.karyawan_nama}</p>
                    
                    <div class="my-3 text-center">
                      <iframe 
                        src="${baseUrl + item.dokumen_panduan_lampiran}"
                        width="80%" 
                        height="250px" 
                        style="border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);"
                        scrolling="no">
                      </iframe>
                    </div>
                    
                    <div>
                      <a href="${baseUrl + item.dokumen_panduan_lampiran}" target="_blank" class="btn btn-light btn-sm shadow-sm">
                        Lihat dokumen
                      </a>
                    </div>
                  </div>
                </div>
              `;
                indicators += `
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="${index}"
                  ${index === 0 ? 'class="active" aria-current="true"' : ''} aria-label="Slide ${index + 1}">
                </button>
              `;
              });

              $('#panduan-container').html(html).fadeIn(400); // Munculkan setelah konten diisi
              $('#panduan-indicators').html(indicators);

              if (data.length > 1) {
                $('#carouselExampleCaptions').append(`
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              `);
              }
            });
          },

          error: function(xhr, status, error) {
            console.error("Error loading dokumen panduan:", error);
            $('#panduan-container').html('<div class="p-4">Gagal memuat dokumen panduan.</div>');
          }
        });
        ajaxReq.push(getDokumenPanduan_karyawan);
      }
    } else {
      var getTotalLembur = $.ajax({
        url: url + 'getTotalLembur_karyawan',
        type: 'POST',
        dataType: 'json',
        data: {
          tgl_mulai: tgl_mulai,
          tgl_selesai: tgl_selesai,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          $('#sum-lembur-jam').html(`<i class="fa fa-spinner fa-pulse fa-fw"></i>`);
          $('#sum-lembur-pendapatan').html(`<i class="fa fa-spinner fa-pulse fa-fw"></i>`);
        },
        success: function(res) {
          if (res.success) {
            var data = res.data;
            loadNumberIterate(data.total_akumulasi_lembur, '#sum-lembur-jam');
            loadNumberIterate(data.total_pendapatan_lembur, '#sum-lembur-pendapatan', true);
          } else {
            $('#sum-lembur-jam').text('?');
            $('#sum-lembur-pendapatan').text('?');
          }
        },

        error: function(xhr, status, error) {
          $('#sum-lembur-jam').text('?');
          $('#sum-lembur-pendapatan').text('?');
          console.log(xhr.responseText);
        }
      });
      ajaxReq.push(getTotalLembur);

      var getTotalDinasLuar = $.ajax({
        url: url + 'getTotalDinasLuar_karyawan',
        type: 'POST',
        dataType: 'json',
        data: {
          tgl_mulai: tgl_mulai,
          tgl_selesai: tgl_selesai,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          $('#sum-dinas-luar').html(`<i class="fa fa-spinner fa-pulse fa-fw"></i>`);
        },
        success: function(res) {
          if (res.success) {
            var data = res.data;
            loadNumberIterate(data.total, '#sum-dinas-luar');
          } else {
            $('#sum-dinas-luar').text('?');
          }
        },

        error: function(xhr, status, error) {
          $('#sum-dinas-luar').text('?');
          console.log(xhr.responseText);
        }
      });
      ajaxReq.push(getTotalDinasLuar);

      var getTotalPinjaman = $.ajax({
        url: url + 'getTotalPinjaman_karyawan',
        type: 'POST',
        dataType: 'json',
        data: {
          tgl_mulai: tgl_mulai,
          tgl_selesai: tgl_selesai,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          $('#sum-pinjaman').html(`<i class="fa fa-spinner fa-pulse fa-fw"></i>`);
        },
        success: function(res) {
          if (res.success) {
            var data = res.data;
            loadNumberIterate(data.total, '#sum-pinjaman', true);
          } else {
            $('#sum-pinjaman').text('?');
          }
        },

        error: function(xhr, status, error) {
          $('#sum-pinjaman').text('?');
          console.log(xhr.responseText);
        }
      });
      ajaxReq.push(getTotalPinjaman);

      var getTotalCutiTahunan = $.ajax({
        url: url + 'getTotalCutiTahunan_karyawan',
        type: 'POST',
        dataType: 'json',
        data: {
          tgl_mulai: tgl_mulai,
          tgl_selesai: tgl_selesai,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          $('#sum-cuti-tahunan-terpakai').html(`<i class="fa fa-spinner fa-pulse fa-fw"></i>`);
          $('#batas-cuti-tahunan').html(`<i class="fa fa-spinner fa-pulse fa-fw"></i>`);
        },
        success: function(res) {
          if (res.success) {
            var data = res.data;
            var sisaCuti = data.jenis_cuti_batas_pertahun - data.total;
            loadNumberIterate(data.total, '#sum-cuti-tahunan-terpakai');
            $('#batas-cuti-tahunan').html(data.jenis_cuti_batas_pertahun);
          } else {
            $('#sum-cuti-tahunan-terpakai').text('?');
          }
        },

        error: function(xhr, status, error) {
          $('#sum-cuti-tahunan-terpakai').text('?');
          console.log(xhr.responseText);
        }
      });
      ajaxReq.push(getTotalCutiTahunan);

      var getSuratEdaran_karyawan = $.ajax({
        url: url + 'getSuratEdaran_karyawan',
        type: 'POST',
        dataType: 'json',
        data: {
          // tgl_mulai: tgl_mulai,
          // tgl_selesai: tgl_selesai,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          $('#list-surat').html(`<div class="p-4 d-flex justify-content-center align-items-center text-center" style="height: 300px;">
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>  
              </div>`);

        },
        success: function(response) {
          $('#list-surat').empty();

          const data = response.data;
          if (!data || data.length === 0) {
            $('#list-surat').append(`<li style="height:300px; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-muted">Tidak ada surat edaran</span>
                                  </li>
                            `).hide().fadeIn(500);
          } else {
            data.forEach(function(item, index) {

              let iconClass, bgColor;
              let badgeClass, status;
              switch (item.surat_edaran_tembusan) {
                case '1':
                  iconClass = 'bx bx-briefcase';
                  bgColor = 'bg-primary';
                  break;
                case '2':
                  iconClass = 'bx bx-buildings';
                  bgColor = 'bg-success';
                  break;
                case '3':
                  iconClass = 'bx bx-folder';
                  bgColor = 'bg-warning';
                  break;
                default:
                  iconClass = 'bx bx-cog';
                  bgColor = 'bg-info';
                  break;
              }

              if (item.is_deleted == 0) {
                badgeClass = "bg-success";
                status = "Berlaku";
              } else if (item.is_deleted == 1) {
                badgeClass = "bg-danger";
                status = "Tidak Berlaku";
              }

              const isLastItem = index === data.length - 1;

              let listItem = `
                  <li class="activity-list list-surat-edaran hover-shadow ${isLastItem ? '' : 'activity-border'}" data-id="${item.id}" style="cursor: pointer; display:none;">
                    <div class="activity-icon avatar-md">
                      <span class="avatar-title ${bgColor} text-warning rounded-circle text-white">
                        <i class="${iconClass} font-size-18"></i>
                      </span>
                    </div>
                    <div class="timeline-list-item">
                      <div class="d-flex">
                        <div class="flex-grow-1 overflow-hidden me-4">
                          <h5 class="font-size-14 mb-1">${item.surat_edaran_judul}</h5>
                          <p class="text-truncate text-muted font-size-13">${item.surat_edaran_nomor}</p>
                        </div>
                        <div class="flex-shrink-0 text-end me-3">
                          <h6 class="mb-1">${item.karyawan_nama}</h6>
                          <div class="font-size-13"><span class="badge ${badgeClass}"><span>${status}</span></span> ${item.created_at}</div>
                        </div>
                      </div>
                    </div>
                  </li>
                `;

              // Tambahkan ke list lalu animasikan muncul
              const $item = $(listItem).appendTo('#list-surat');
              setTimeout(() => {
                $item.fadeIn(400);
              }, index * 100); // Delay dikit biar satu-satu muncul
            });
          }
        },

        error: function(xhr, status, error) {
          console.error("Error:", error);
          $('#list-surat').html('<li>Error loading data</li>');
        }
      });
      ajaxReq.push(getSuratEdaran_karyawan);

      var getDokumenPanduan_karyawan = $.ajax({
        url: url + 'getDokumenPanduan_karyawan',
        type: 'POST',
        dataType: 'json',
        data: {
          // tgl_mulai: tgl_mulai,
          // tgl_selesai: tgl_selesai,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        beforeSend: function() {
          $('#panduan-container').html(`
              <div class="p-4 d-flex justify-content-center align-items-center text-center" style="height: 450px;">
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>  
              </div>
            `);
        },
        success: function(response) {
          const data = response.data;

          // Biar smooth: sembunyikan dulu kontainer sebelum diisi
          $('#panduan-container').fadeOut(200, function() {
            if (!data || data.length === 0) {
              $('#panduan-container').html(`
                <div class="d-flex justify-content-center align-items-center flex-column text-center" style="height: 450px;">
                  <p class="mb-0">Tidak ada dokumen panduan</p>
                </div>
              `).fadeIn(400);

              // Tetap tambahkan tombol panah agar tampil, tapi tidak bisa diklik
              $('#carouselExampleCaptions').append(`
                <button class="carousel-control-prev disabled" type="button" style="opacity: 0.3; pointer-events: none;">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next disabled" type="button" style="opacity: 0.3; pointer-events: none;">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
              `);
              return;
            }
            let html = '';
            let indicators = '';

            data.forEach(function(item, index) {
              html += `
                <div class="carousel-item ${index === 0 ? 'active' : ''}">
                  <div class="p-4 d-flex flex-column justify-content-center align-items-center text-center" style="min-height: 352px;">
                    <h4 class="lh-base fw-normal text-white mb-1">
                      <b>${item.dokumen_panduan_nama}</b>
                    </h4>
                    <p class="text-white-50 font-size-13 mb-3">Dibuat Oleh: ${item.karyawan_nama}</p>
                    
                    <div class="my-3 text-center">
                      <iframe 
                        src="${baseUrl + item.dokumen_panduan_lampiran}"
                        width="80%" 
                        height="250px" 
                        style="border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);"
                        scrolling="no">
                      </iframe>
                    </div>
                    
                    <div>
                      <a href="${baseUrl + item.dokumen_panduan_lampiran}" target="_blank" class="btn btn-light btn-sm shadow-sm">
                        Lihat dokumen
                      </a>
                    </div>
                  </div>
                </div>
              `;
              indicators += `
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="${index}"
                  ${index === 0 ? 'class="active" aria-current="true"' : ''} aria-label="Slide ${index + 1}">
                </button>
              `;
            });

            $('#panduan-container').html(html).fadeIn(400); // Munculkan setelah konten diisi
            $('#panduan-indicators').html(indicators);

            if (data.length > 1) {
              $('#carouselExampleCaptions').append(`
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              `);
            }
          });
        },

        error: function(xhr, status, error) {
          console.error("Error loading dokumen panduan:", error);
          $('#panduan-container').html('<div class="p-4">Gagal memuat dokumen panduan.</div>');
        }
      });
      ajaxReq.push(getDokumenPanduan_karyawan);

      var getChartLemburPerBulan_karyawan = $.ajax({
        url: url + 'getChartLemburPerBulan_karyawan',
        type: 'POST',
        cache: false,
        dataType: 'json',
        data: {
          tgl_mulai: tgl_mulai,
          tgl_selesai: tgl_selesai,
          "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
        },
        success: function(response) {
          var columnColors = getChartColorsArray("#chart_lembur_karyawan");

          const data = response.data;

          const categories = data.map(item => {
            const d = new Date(item.tanggal);
            return d.toLocaleDateString('id-ID', {
              day: '2-digit',
              month: 'short'
            });
          });

          const seriesData = data.map(item => parseFloat(item.total_pendapatan));
          const jamData = data.map(item => parseFloat(item.total_jam));

          // Destroy chart sebelumnya
          if (chartLemburPerBulan_karyawan !== null) {
            chartLemburPerBulan_karyawan.destroy();
          }

          var options = {
            chart: {
              height: 350,
              type: 'bar',
              toolbar: {
                show: false
              }
            },
            series: [{
              name: 'Total Jam',
              data: jamData
            }],
            colors: columnColors,
            xaxis: {
              categories: categories,
              title: {
                text: 'Tanggal',
                style: {
                  fontWeight: '500'
                }
              }
            },
            yaxis: {
              title: {
                text: 'Jam Lembur (Jam)',
                style: {
                  fontWeight: '500'
                }
              }
            },
            tooltip: {
              custom: function({
                series,
                seriesIndex,
                dataPointIndex
              }) {
                const jam = series[seriesIndex][dataPointIndex];
                const pendapatan = seriesData[dataPointIndex];
                const tanggal = categories[dataPointIndex];
                return `
          <div class="apexcharts-tooltip-title text-center">${tanggal}</div>
          <div style="padding: 5px 10px;">
            <strong>Total Jam:</strong> ${jam.toLocaleString()}<br/>
            <strong>Total Pendapatan:</strong> Rp ${pendapatan.toLocaleString()}
          </div>
        `;
              }
            }
          };

          // Render chart baru
          chartLemburPerBulan_karyawan = new ApexCharts(
            document.querySelector("#chart_lembur_karyawan"),
            options
          );
          chartLemburPerBulan_karyawan.render();
        },
        error: function(xhr, status, error) {
          console.error("Error loading lembur chart:", error);
        }
      });
      ajaxReq.push(getChartLemburPerBulan_karyawan);

      var getChartCutiTahunan_karyawan = $.ajax({
        url: url + 'getChartCutiTahunan_karyawan', 
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          if (response.status) {
            var pieColors = ["#fd625e", "#5156be"]; // used, remaining
            
            var totalDipakai = response.data.terpakai ?? 0;
            var sisaCuti = response.data.sisa ?? 12;
            var batas = Math.max(1, (totalDipakai + sisaCuti));
            var totalSegments = 12;
            var segmentsFilled = Math.round((totalDipakai / batas) * totalSegments);
            if (segmentsFilled < 0) segmentsFilled = 0;
            if (segmentsFilled > totalSegments) segmentsFilled = totalSegments;

            var container = document.querySelector('#cuti_tahunan');
            container.innerHTML = '';
            var canvasSize = 220;
            var canvas = document.createElement('canvas');
            canvas.width = canvasSize; 
            canvas.height = canvasSize;
            canvas.style.maxWidth = '100%';
            canvas.style.height = 'auto';
            container.appendChild(canvas);

            var ctx = canvas.getContext('2d');
            var cx = canvas.width / 2;
            var cy = canvas.height / 2;
            var radius = Math.min(cx, cy) - 20;
            var strokeWidth = Math.floor(radius * 0.28);
            var gap = 0.12;
            var segAngle = (Math.PI * 2) / totalSegments;

            // Track current hovered segment
            var hoveredSegment = -1;

            function drawSegment(i, color, isHovered) {
              var start = -Math.PI / 2 + i * segAngle + gap / 2;
              var end = start + segAngle - gap;
              
              ctx.beginPath();
              ctx.lineWidth = strokeWidth;
              ctx.lineCap = 'butt';
              
              // Add glow effect on hover
              if (isHovered) {
                ctx.shadowColor = color;
                ctx.shadowBlur = 15;
                ctx.strokeStyle = lightenColor(color, 20);
              } else {
                ctx.shadowBlur = 0;
                ctx.strokeStyle = color;
              }
              
              ctx.arc(cx, cy, radius - strokeWidth / 2, start, end, false);
              ctx.stroke();
              ctx.shadowBlur = 0;
            }

            function lightenColor(color, percent) {
              var num = parseInt(color.replace("#",""), 16),
              amt = Math.round(2.55 * percent),
              R = (num >> 16) + amt,
              G = (num >> 8 & 0x00FF) + amt,
              B = (num & 0x0000FF) + amt;
              return "#" + (0x1000000 + (R<255?R<1?0:R:255)*0x10000 + (G<255?G<1?0:G:255)*0x100 + (B<255?B<1?0:B:255)).toString(16).slice(1);
            }
            function redraw() {
              ctx.clearRect(0, 0, canvas.width, canvas.height);
              
              // Draw segments
              for (var i = 0; i < totalSegments; i++) {
                var color = i < segmentsFilled ? pieColors[0] : pieColors[1];
                drawSegment(i, color, i === hoveredSegment);
              }
              // Draw center text
              var usedText = totalDipakai.toString();
              var quotaText = batas.toString();
              ctx.font = 'bold 18px Arial';
              ctx.fillStyle = '#212529';
              ctx.textAlign = 'center';
              ctx.textBaseline = 'middle';
              ctx.fillText(usedText, cx, cy - 6);
              ctx.font = '12px Arial';
              ctx.fillStyle = '#6c757d';
              ctx.fillText('dari ' + quotaText + ' hari', cx, cy + 14);
            }

            // Handle mouse movement
            canvas.addEventListener('mousemove', function(event) {
              var rect = canvas.getBoundingClientRect();
              var x = event.clientX - rect.left;
              var y = event.clientY - rect.top;
              
              // Convert to canvas coordinates
              x = x * (canvas.width / rect.width);
              y = y * (canvas.height / rect.height);
              
              // Calculate angle from center
              var angle = Math.atan2(y - cy, x - cx);
              if (angle < 0) angle += Math.PI * 2;
              angle = (angle + Math.PI/2) % (Math.PI * 2);
              
              // Calculate distance from center
              var dist = Math.sqrt(Math.pow(x - cx, 2) + Math.pow(y - cy, 2));
              
              // Check if mouse is near the ring
              var innerRadius = radius - strokeWidth;
              var outerRadius = radius;
              
              if (dist >= innerRadius && dist <= outerRadius) {
                // Calculate segment
                var segment = Math.floor(angle / segAngle);
                if (segment !== hoveredSegment) {
                  hoveredSegment = segment;
                  redraw();
                  canvas.style.cursor = 'pointer';
                }
              } else if (hoveredSegment !== -1) {
                hoveredSegment = -1;
                redraw();
                canvas.style.cursor = 'default';
              }
            });
            canvas.addEventListener('mouseleave', function() {
              hoveredSegment = -1;
              redraw();
              canvas.style.cursor = 'default';
            });

            // Initial draw
            redraw();
            // Add legend
            var legendHtml = `
              <div style="display:flex; justify-content:center; gap:20px; align-items:center;">
                <div style="display:flex; align-items:center; gap:8px;">
                  <span style="width:12px; height:12px; background:${pieColors[0]}; display:inline-block; border-radius:3px;"></span>
                  <div style="text-align:left;"><div style="font-weight:600;">Cuti Terpakai</div><div style="color:#6c757d;">${totalDipakai} Hari</div></div>
                </div>
                <div style="display:flex; align-items:center; gap:8px;">
                  <span style="width:12px; height:12px; background:${pieColors[1]}; display:inline-block; border-radius:3px;"></span>
                  <div style="text-align:left;"><div style="font-weight:600;">Sisa Cuti</div><div style="color:#6c757d;">${sisaCuti} Hari</div></div>
                </div>
              </div>
            `;
            document.getElementById('cuti_tahunan_legend').innerHTML = legendHtml;
          } else {
            console.warn("Gagal memuat data cuti:", response.message);
          }
        },
        error: function(err) {
          console.error("AJAX Error:", err);
        }
      });
      ajaxReq.push(getChartCutiTahunan_karyawan);

      if (role_code == 'adm' || role_code == 'ftc') { // Admin
        var datatablePengajuanTerbaru_adm = initDatatable("#datatablePengajuanTerbaru_adm", {
          url: url + "datatablePengajuanTerbaru_adm",
          csrf: coreEvents.csrf,
          // dom: 'lfrtip' // buat nampilin show x entries
          filter: {
            tgl_mulai: tgl_mulai,
            tgl_selesai: tgl_selesai,
          },
          columns: [{
              data: null,
              className: "text-center align-middle",
              orderable: false,
              width: 1,
              render: function(a, type, data, index) {
                return dataStart + index.row + 1
              }
            },
            {
              data: "user_web_role_name",
              className: "text-start align-middle",
              orderable: true,
              width: 100
            },
            {
              data: "karyawan_nama",
              className: "text-start align-middle",
              orderable: true,
              width: 100
            },
            {
              data: "jenis_pengajuan",
              className: "text-start align-middle",
              orderable: true,
              width: 100
            },
            {
              data: "tgl_pengajuan",
              className: "text-start align-middle",
              orderable: true,
              width: 100
            },
            {
              data: "keperluan",
              className: "text-start align-middle",
              orderable: true,
              width: 100,
            },
            {
              data: "verifikasi",
              className: "text-start align-middle",
              orderable: true,
              width: 100,
              render: function(data, type, row) {
                return `<span class="badge bg-secondary w-100 text-center">${data}</span>`;
              }
            },
            {
              data: "status",
              className: "text-start align-middle",
              orderable: true,
              width: 100,
              render: function(data, type, row) {
                return `<span class="badge bg-warning p-1 w-100 text-center"><i class='fas fa-spinner'></i> <span class="ms-2">${data}</span></span>`;
              }
            },
          ],
        });
        ajaxReq.push(datatablePengajuanTerbaru_adm);
        var getChartPengajuanLemburSemuaJabatan_adm = $.ajax({
          url: url + 'getChartPengajuanLemburSemuaJabatan_adm',
          type: 'POST',
          data: {
            start_date: tgl_mulai,
            end_date: tgl_selesai,
            "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
          },
          dataType: 'json',
          success: function(response) {
            const data = response.data || [];

            const categories = data.map(item => item.jabatan_anak);
            const ditolak = data.map(item => parseInt(item.jml_ditolak));
            const belumDiproses = data.map(item => parseInt(item.jml_belum_diproses));
            const sedangProses = data.map(item => parseInt(item.jml_sedang_proses));
            const selesai = data.map(item => parseInt(item.jml_selesai));

            const options = {
              chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: {
                  show: false
                }
              },
              plotOptions: {
                bar: {
                  horizontal: false,
                  columnWidth: '40%'
                }
              },
              dataLabels: {
                enabled: false
              },
              stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
              },
              series: [{
                  name: 'Ditolak',
                  data: ditolak
                },
                {
                  name: 'Belum Diproses',
                  data: belumDiproses
                },
                {
                  name: 'Sedang Proses',
                  data: sedangProses
                },
                {
                  name: 'Selesai',
                  data: selesai
                }
              ],
              colors: ['#fd625e', '#4ba6ef', '#f5b849', '#2ab57d'],
              xaxis: {
                categories: categories,
                title: {
                  text: 'Jabatan',
                  style: {
                    fontWeight: '500'
                  }
                },
                labels: {
                  rotate: -45,
                  style: {
                    fontSize: '12px',
                    fontWeight: 400
                  },
                  formatter: function(val) {
                    return val.length > 15 ? val.substring(0, 15) + '...' : val;
                  }
                }
              },
              yaxis: {
                title: {
                  text: 'Jumlah Pengajuan',
                  style: {
                    fontWeight: '500'
                  }
                }
              },
              tooltip: {
                enabled: true,
                shared: false,
                intersect: true,
                custom: function({
                  series,
                  seriesIndex,
                  dataPointIndex,
                  w
                }) {
                  const jabatan = w.globals.labels[dataPointIndex];
                  const color = w.config.colors[seriesIndex];
                  const value = series[seriesIndex][dataPointIndex];
                  const name = w.config.series[seriesIndex].name;

                  return `
                    <div class="apexcharts-tooltip-title" style="text-align:center; font-size:12px; font-weight:500;">
                        ${jabatan}
                    </div>
                    <div style="padding: 6px 10px;">
                        <span style="display:inline-flex; align-items:center;">
                            <span style="background-color: ${color}; width:10px; height:10px; border-radius:50%; margin-right:6px;"></span>
                            ${name}:&nbsp;<strong>${value} pengajuan</strong>
                        </span>
                    </div>
                `;
                }
              },
              grid: {
                borderColor: '#f1f1f1'
              },
              fill: {
                opacity: 1
              }
            };

            if (chartPengajuanLemburSemuaJabatan_adm !== null) {
              chartPengajuanLemburSemuaJabatan_adm.destroy();
            }

            chartPengajuanLemburSemuaJabatan_adm = new ApexCharts(
              document.querySelector("#chartPengajuanLemburSemuaJabatan_adm"),
              options
            );

            chartPengajuanLemburSemuaJabatan_adm.render();
          },
          error: function(xhr, status, error) {
            console.error('Gagal memuat data:', error);
            document.getElementById('chartPengajuanLemburSemuaJabatan_adm').innerHTML = `
                <div class="text-center py-5">
                    <h5>Gagal memuat data. Silakan coba lagi.</h5>
                </div>
            `;
          }
        });
        ajaxReq.push(getChartPengajuanLemburSemuaJabatan_adm);

        var getChartPengajuanCutiSemuaJabatan_adm = $.ajax({
          url: url + 'getChartPengajuanCutiSemuaJabatan_adm',
          type: 'POST',
          data: {
            start_date: tgl_mulai,
            end_date: tgl_selesai,
            "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
          },
          dataType: 'json',
          success: function(response) {
            const data = response.data || [];

            const categories = data.map(item => item.jabatan_anak);
            const ditolak = data.map(item => parseInt(item.jml_ditolak));
            const belumDiproses = data.map(item => parseInt(item.jml_belum_diproses));
            const sedangProses = data.map(item => parseInt(item.jml_sedang_proses));
            const selesai = data.map(item => parseInt(item.jml_selesai));

            const options = {
              chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: {
                  show: false
                }
              },
              plotOptions: {
                bar: {
                  horizontal: false,
                  columnWidth: '40%'
                }
              },
              dataLabels: {
                enabled: false
              },
              stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
              },
              series: [{
                  name: 'Ditolak',
                  data: ditolak
                },
                {
                  name: 'Belum Diproses',
                  data: belumDiproses
                },
                {
                  name: 'Sedang Proses',
                  data: sedangProses
                },
                {
                  name: 'Selesai',
                  data: selesai
                }
              ],
              colors: ['#fd625e', '#4ba6ef', '#f5b849', '#2ab57d'],
              xaxis: {
                categories: categories,
                title: {
                  text: 'Jabatan',
                  style: {
                    fontWeight: '500'
                  }
                },
                labels: {
                  rotate: -45,
                  style: {
                    fontSize: '12px',
                    fontWeight: 400
                  },
                  formatter: function(val) {
                    return val.length > 15 ? val.substring(0, 15) + '...' : val;
                  }
                }
              },
              yaxis: {
                title: {
                  text: 'Jumlah Pengajuan',
                  style: {
                    fontWeight: '500'
                  }
                }
              },
              tooltip: {
                enabled: true,
                shared: false,
                intersect: true,
                custom: function({
                  series,
                  seriesIndex,
                  dataPointIndex,
                  w
                }) {
                  const jabatan = w.globals.labels[dataPointIndex];
                  const color = w.config.colors[seriesIndex];
                  const value = series[seriesIndex][dataPointIndex];
                  const name = w.config.series[seriesIndex].name;

                  return `
                    <div class="apexcharts-tooltip-title" style="text-align:center; font-size:12px; font-weight:500;">
                        ${jabatan}
                    </div>
                    <div style="padding: 6px 10px;">
                        <span style="display:inline-flex; align-items:center;">
                            <span style="background-color: ${color}; width:10px; height:10px; border-radius:50%; margin-right:6px;"></span>
                            ${name}:&nbsp;<strong>${value} pengajuan</strong>
                        </span>
                    </div>
                `;
                }
              },
              grid: {
                borderColor: '#f1f1f1'
              },
              fill: {
                opacity: 1
              }
            };

            if (chartPengajuanCutiSemuaJabatan_adm !== null) {
              chartPengajuanCutiSemuaJabatan_adm.destroy();
            }

            chartPengajuanCutiSemuaJabatan_adm = new ApexCharts(
              document.querySelector("#chartPengajuanCutiSemuaJabatan_adm"),
              options
            );

            chartPengajuanCutiSemuaJabatan_adm.render();
          },
          error: function(xhr, status, error) {
            console.error('Gagal memuat data:', error);
            document.getElementById('chartPengajuanCutiSemuaJabatan_adm').innerHTML = `
                <div class="text-center py-5">
                    <h5>Gagal memuat data. Silakan coba lagi.</h5>
                </div>
            `;
          }
        });
        ajaxReq.push(getChartPengajuanCutiSemuaJabatan_adm);

        var getChartPengajuanDLSemuaJabatan_adm = $.ajax({
          url: url + 'getChartPengajuanDLSemuaJabatan_adm',
          type: 'POST',
          data: {
            start_date: tgl_mulai,
            end_date: tgl_selesai,
            "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
          },
          dataType: 'json',
          success: function(response) {
            const data = response.data || [];

            const categories = data.map(item => item.jabatan_anak);
            const ditolak = data.map(item => parseInt(item.jml_ditolak));
            const belumDiproses = data.map(item => parseInt(item.jml_belum_diproses));
            const sedangProses = data.map(item => parseInt(item.jml_sedang_proses));
            const selesai = data.map(item => parseInt(item.jml_selesai));

            const options = {
              chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: {
                  show: false
                }
              },
              plotOptions: {
                bar: {
                  horizontal: false,
                  columnWidth: '40%'
                }
              },
              dataLabels: {
                enabled: false
              },
              stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
              },
              series: [{
                  name: 'Ditolak',
                  data: ditolak
                },
                {
                  name: 'Belum Diproses',
                  data: belumDiproses
                },
                {
                  name: 'Sedang Proses',
                  data: sedangProses
                },
                {
                  name: 'Selesai',
                  data: selesai
                }
              ],
              colors: ['#fd625e', '#4ba6ef', '#f5b849', '#2ab57d'],
              xaxis: {
                categories: categories,
                title: {
                  text: 'Jabatan',
                  style: {
                    fontWeight: '500'
                  }
                },
                labels: {
                  rotate: -45,
                  style: {
                    fontSize: '12px',
                    fontWeight: 400
                  },
                  formatter: function(val) {
                    return val.length > 15 ? val.substring(0, 15) + '...' : val;
                  }
                }
              },
              yaxis: {
                title: {
                  text: 'Jumlah Pengajuan',
                  style: {
                    fontWeight: '500'
                  }
                }
              },
              tooltip: {
                enabled: true,
                shared: false,
                intersect: true,
                custom: function({
                  series,
                  seriesIndex,
                  dataPointIndex,
                  w
                }) {
                  const jabatan = w.globals.labels[dataPointIndex];
                  const color = w.config.colors[seriesIndex];
                  const value = series[seriesIndex][dataPointIndex];
                  const name = w.config.series[seriesIndex].name;

                  return `
                    <div class="apexcharts-tooltip-title" style="text-align:center; font-size:12px; font-weight:500;">
                        ${jabatan}
                    </div>
                    <div style="padding: 6px 10px;">
                        <span style="display:inline-flex; align-items:center;">
                            <span style="background-color: ${color}; width:10px; height:10px; border-radius:50%; margin-right:6px;"></span>
                            ${name}:&nbsp;<strong>${value} pengajuan</strong>
                        </span>
                    </div>
                `;
                }
              },
              grid: {
                borderColor: '#f1f1f1'
              },
              fill: {
                opacity: 1
              }
            };

            if (chartPengajuanDLSemuaJabatan_adm !== null) {
              chartPengajuanDLSemuaJabatan_adm.destroy();
            }

            chartPengajuanDLSemuaJabatan_adm = new ApexCharts(
              document.querySelector("#chartPengajuanDLSemuaJabatan_adm"),
              options
            );

            chartPengajuanDLSemuaJabatan_adm.render();
          },
          error: function(xhr, status, error) {
            console.error('Gagal memuat data:', error);
            document.getElementById('chartPengajuanDLSemuaJabatan_adm').innerHTML = `
                <div class="text-center py-5">
                    <h5>Gagal memuat data. Silakan coba lagi.</h5>
                </div>
            `;
          }
        });
        ajaxReq.push(getChartPengajuanDLSemuaJabatan_adm);

        if (role_code == 'ftc') {
          var datatablePengajuanPinjamanTerbaru_ftc = initDatatable("#datatablePengajuanPinjamanTerbaru_ftc", {
            url: url + "datatablePengajuanPinjamanTerbaru_ftc",
            csrf: coreEvents.csrf,
            // dom: 'lfrtip' // buat nampilin show x entries
            filter: {
              tgl_mulai: tgl_mulai,
              tgl_selesai: tgl_selesai,
            },
            columns: [{
                data: null,
                className: "text-center align-middle",
                orderable: false,
                width: 1,
                render: function(a, type, data, index) {
                  return dataStart + index.row + 1
                }
              },
              {
                data: "user_web_role_name",
                className: "text-start align-middle",
                orderable: true,
                width: 100
              },
              {
                data: "karyawan_nama",
                className: "text-start align-middle",
                orderable: true,
                width: 100
              },
              {
                data: "tgl_pengajuan",
                className: "text-start align-middle",
                orderable: true,
                width: 100
              },
              {
                data: "pinjaman_plafon",
                className: "text-start align-middle",
                orderable: true,
                width: 100
              },
              {
                data: "pinjaman_jangka_waktu",
                className: "text-start align-middle",
                orderable: true,
                width: 100,
              },
              {
                data: "verifikasi",
                className: "text-start align-middle",
                orderable: true,
                width: 100,
                render: function(data, type, row) {
                  return `<span class="badge bg-secondary w-100 text-center">${data}</span>`;
                }
              },
              {
                data: "status",
                className: "text-start align-middle",
                orderable: true,
                width: 100,
                render: function(data, type, row) {
                  return `<span class="badge bg-info p-1 w-100 text-center"><i class='fas fa-hourglass'></i> <span class="ms-2">${data}</span></span>`;
                }
              },
            ],
          });
          ajaxReq.push(datatablePengajuanPinjamanTerbaru_ftc);

          var getChartPengajuanPinjamanPerJabatan_ftc = $.ajax({
            url: url + 'getChartPengajuanPinjamanPerJabatan_ftc',
            type: 'POST',
            data: {
              start_date: tgl_mulai,
              end_date: tgl_selesai,
              "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
            },
            dataType: 'json',
            success: function(response) {
              const data = response.data || [];

              const categories = data.map(item => item.jabatan_anak);
              const ditolak = data.map(item => parseInt(item.jml_ditolak));
              const belumDiproses = data.map(item => parseInt(item.jml_belum_diproses));
              const sedangProses = data.map(item => parseInt(item.jml_sedang_proses));
              const selesai = data.map(item => parseInt(item.jml_selesai));

              const options = {
                chart: {
                  height: 350,
                  type: 'bar',
                  stacked: false,
                  toolbar: {
                    show: false
                  }
                },
                plotOptions: {
                  bar: {
                    horizontal: false,
                    columnWidth: '40%'
                  }
                },
                dataLabels: {
                  enabled: false
                },
                stroke: {
                  show: true,
                  width: 2,
                  colors: ['transparent']
                },
                series: [{
                    name: 'Ditolak',
                    data: ditolak
                  },
                  {
                    name: 'Belum Diproses',
                    data: belumDiproses
                  },
                  {
                    name: 'Sedang Proses',
                    data: sedangProses
                  },
                  {
                    name: 'Selesai',
                    data: selesai
                  }
                ],
                colors: ['#fd625e', '#f5b849', '#4ba6ef', '#2ab57d'],
                xaxis: {
                  categories: categories,
                  title: {
                    text: 'Jabatan',
                    style: {
                      fontWeight: '500'
                    }
                  },
                  labels: {
                    rotate: -45,
                    style: {
                      fontSize: '12px',
                      fontWeight: 400
                    },
                    formatter: function(val) {
                      return val.length > 15 ? val.substring(0, 15) + '...' : val;
                    }
                  }
                },
                yaxis: {
                  title: {
                    text: 'Jumlah Pengajuan',
                    style: {
                      fontWeight: '500'
                    }
                  }
                },
                tooltip: {
                  enabled: true,
                  shared: false,
                  intersect: true,
                  custom: function({
                    series,
                    seriesIndex,
                    dataPointIndex,
                    w
                  }) {
                    const jabatan = w.globals.labels[dataPointIndex];
                    const color = w.config.colors[seriesIndex];
                    const value = series[seriesIndex][dataPointIndex];
                    const name = w.config.series[seriesIndex].name;

                    return `
                    <div class="apexcharts-tooltip-title" style="text-align:center; font-size:12px; font-weight:500;">
                        ${jabatan}
                    </div>
                    <div style="padding: 6px 10px;">
                        <span style="display:inline-flex; align-items:center;">
                            <span style="background-color: ${color}; width:10px; height:10px; border-radius:50%; margin-right:6px;"></span>
                            ${name}:&nbsp;<strong>${value} pengajuan</strong>
                        </span>
                    </div>
                `;
                  }
                },
                grid: {
                  borderColor: '#f1f1f1'
                },
                fill: {
                  opacity: 1
                }
              };

              if (chartPengajuanPinjamanPerJabatan_ftc !== null) {
                chartPengajuanPinjamanPerJabatan_ftc.destroy();
              }

              chartPengajuanPinjamanPerJabatan_ftc = new ApexCharts(
                document.querySelector("#chartPengajuanPinjamanPerJabatan_ftc"),
                options
              );

              chartPengajuanPinjamanPerJabatan_ftc.render();
            },
            error: function(xhr, status, error) {
              console.error('Gagal memuat data:', error);
              document.getElementById('chartPengajuanPinjamanPerJabatan_ftc').innerHTML = `
                <div class="text-center py-5">
                    <h5>Gagal memuat data. Silakan coba lagi.</h5>
                </div>
            `;
            }
          });

          ajaxReq.push(getChartPengajuanPinjamanPerJabatan_ftc);

        }

      } else if (spv_id == null) { // SPV tiap Divisi
        var getChartTotalKaryawanPerJabatan_spv = $.ajax({
          url: url + 'getChartTotalKaryawanPerJabatan_spv',
          method: 'POST',
          data: {
            role_code: role_code,
            "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
          },
          success: function(response) {
            let data = response.data;

            let series = [];
            let labels = [];

            data.forEach(function(item) {
              labels.push(item.jabatan ?? 'Tidak diketahui');
              series.push(parseInt(item.jumlah_karyawan));
            });

            const colors = getRandomColors('#pieChartTotalKaryawanPerJabatan', series.length);

            var options = {
              chart: {
                type: 'pie',
                height: 350
              },
              series: series,
              labels: labels,
              colors: colors,
              legend: {
                position: 'bottom'
              },
              tooltip: {
                y: {
                  formatter: function(val) {
                    return val + " karyawan";
                  }
                }
              }
            };

            if (chartTotalKaryawanPerJabatan_spv !== null) {
              chartTotalKaryawanPerJabatan_spv.destroy();
            }

            chartTotalKaryawanPerJabatan_spv = new ApexCharts($("#pieChartTotalKaryawanPerJabatan")[0], options);
            chartTotalKaryawanPerJabatan_spv.render();
          }
        });

        ajaxReq.push(getChartTotalKaryawanPerJabatan_spv);

        var getChartPengajuanPerJabatan_spv = $.ajax({
          url: url + 'getChartPengajuanPerJabatan_spv',
          type: 'POST',
          data: {
            start_date: tgl_mulai,
            end_date: tgl_selesai,
            "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
          },
          dataType: 'json',
          success: function(response) {
            var data = response.data;

            var categories = data.map(item => item.jabatan);
            var cuti = data.map(item => parseInt(item.jml_cuti));
            var lembur = data.map(item => parseInt(item.jml_lembur));
            var fullJabatan = data.map(item => item.jabatan);

            var columnColors = ['#fd625e', '#2ab57d'];
            var options = {
              chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: {
                  show: false
                }
              },
              plotOptions: {
                bar: {
                  horizontal: false,
                  columnWidth: '25%',
                }
              },
              dataLabels: {
                enabled: false
              },
              stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
              },
              series: [{
                  name: 'Cuti',
                  data: cuti
                },
                {
                  name: 'Lembur',
                  data: lembur
                }
              ],
              colors: columnColors,
              xaxis: {
                categories: categories,
                title: {
                  text: 'Jabatan',
                  style: {
                    fontWeight: '500'
                  }
                },
                labels: {
                  rotate: 0,
                  style: {
                    fontSize: '12px',
                    fontWeight: 400
                  },
                  formatter: function(val) {
                    return val.length > 15 ? val.substring(0, 15) + '...' : val;
                  }
                }
              },
              yaxis: {
                title: {
                  text: 'Jumlah Pengajuan',
                  style: {
                    fontWeight: '500'
                  }
                }
              },
              tooltip: {
                enabled: true,
                shared: false,
                intersect: true,
                custom: function({
                  series,
                  seriesIndex,
                  dataPointIndex,
                  w
                }) {
                  const jabatan = fullJabatan[dataPointIndex];
                  const color = w.config.colors[seriesIndex];
                  const value = series[seriesIndex][dataPointIndex];
                  const name = w.config.series[seriesIndex].name;

                  return `
          <div class="apexcharts-tooltip-title" style="text-align:center; font-size:12px; font-weight:500;">
            ${jabatan}
          </div>
          <div style="padding: 6px 10px;">
            <span style="display:inline-flex; align-items:center;">
              <span style="background-color: ${color}; width:10px; height:10px; border-radius:50%; margin-right:6px;"></span>
              ${name}:&nbsp;<strong>${value} pengajuan</strong>
            </span>
          </div>
        `;
                }
              },
              grid: {
                borderColor: '#f1f1f1'
              },
              fill: {
                opacity: 1
              }
            };

            if (chartPengajuanPerJabatan_spv !== null) {
              chartPengajuanPerJabatan_spv.destroy();
            }

            chartPengajuanPerJabatan_spv = new ApexCharts(
              document.querySelector("#columnChartPengajuanJabatan"),
              options
            );

            chartPengajuanPerJabatan_spv.render();
          }
        });
        ajaxReq.push(getChartPengajuanPerJabatan_spv);

        var getChartPengajuanLemburPerHari_spv = $.ajax({
          url: url + 'getChartPengajuanLemburPerHari_spv',
          type: 'POST',
          data: {
            start_date: tgl_mulai,
            end_date: tgl_selesai,
            "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
          },
          dataType: 'json',
          success: function(response) {
            const rawData = response.data;

            // Ekstrak semua jabatan dan tanggal
            const allJabatans = new Set();
            const allDates = [];

            rawData.forEach(row => {
              const tanggal = row.tanggal;
              const lemburData = row.lembur_per_jabatan ? JSON.parse(row.lembur_per_jabatan) : {};

              allDates.push(tanggal);
              Object.keys(lemburData).forEach(jabatan => {
                allJabatans.add(jabatan);
              });
            });

            const jabatanList = Array.from(allJabatans);

            // Urutkan tanggal dari terlama ke terbaru
            const dateLabels = [...new Set(allDates)]
              .map(date => new Date(date)) // Konversi ke objek Date
              .sort((a, b) => a - b) // Urutkan dari terlama ke terbaru
              .map(date => date.toISOString().split('T')[0]); // Format YYYY-MM-DD

            // Mapping data ke format ApexCharts
            const series = jabatanList.map(jabatan => ({
              name: jabatan,
              data: dateLabels.map(date => {
                const dataRow = rawData.find(row => row.tanggal === date);
                const lemburData = dataRow?.lembur_per_jabatan ? JSON.parse(dataRow.lembur_per_jabatan) : {};
                return parseInt(lemburData[jabatan] || 0);
              })
            }));

            // Konfigurasi chart
            const colors = getRandomColors('#columnChartPengajuanLemburPerHari', series.length);

            const options = {
              chart: {
                type: 'bar',
                height: 350,
                stacked: false,
                toolbar: {
                  show: false
                }
              },
              plotOptions: {
                bar: {
                  horizontal: false,
                  columnWidth: '40%'
                }
              },
              dataLabels: {
                enabled: false
              },
              stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
              },
              xaxis: {
                categories: dateLabels,
                title: {
                  text: 'Tanggal',
                  style: {
                    fontWeight: '500'
                  }
                }
              },
              yaxis: {
                title: {
                  text: 'Jumlah Pengajuan Lembur',
                  style: {
                    fontWeight: '500'
                  }
                }
              },
              colors: colors,
              series: series,
              tooltip: {
                enabled: true,
                shared: false,
                intersect: true,
                custom: function({
                  series,
                  seriesIndex,
                  dataPointIndex,
                  w
                }) {
                  const tanggal = w.config.xaxis.categories[dataPointIndex];
                  const color = w.config.colors[seriesIndex];
                  const value = series[seriesIndex][dataPointIndex];
                  const name = w.config.series[seriesIndex].name;

                  return `
                    <div class="apexcharts-tooltip-title" style="text-align:center; font-size:12px; font-weight:500;">
                        ${tanggal}
                    </div>
                    <div style="padding: 6px 10px;">
                        <span style="display:inline-flex; align-items:center;">
                            <span style="background-color: ${color}; width:10px; height:10px; border-radius:50%; margin-right:6px;"></span>
                            ${name}:&nbsp;<strong>${value} pengajuan</strong>
                        </span>
                    </div>
                `;
                }
              },
              grid: {
                borderColor: '#f1f1f1'
              },
              fill: {
                opacity: 1
              }
            };

            if (chartPengajuanLemburPerHari_spv !== null) {
              chartPengajuanLemburPerHari_spv.destroy();
            }

            chartPengajuanLemburPerHari_spv = new ApexCharts(
              document.querySelector("#columnChartPengajuanLemburPerHari"),
              options
            );

            chartPengajuanLemburPerHari_spv.render();
          }
        });
        ajaxReq.push(getChartPengajuanLemburPerHari_spv);

        var getChartPengajuanCutiPerHari_spv = $.ajax({
          url: url + 'getChartPengajuanCutiPerHari_spv',
          type: 'POST',
          data: {
            start_date: tgl_mulai,
            end_date: tgl_selesai,
            "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
          },
          dataType: 'json',
          success: function(response) {
            const rawData = response.data;

            // Ekstrak semua jabatan dan tanggal
            const allJabatans = new Set();
            const allDates = [];

            rawData.forEach(row => {
              const tanggal = row.tanggal;
              const cutiData = row.cuti_per_jabatan ? JSON.parse(row.cuti_per_jabatan) : {};

              allDates.push(tanggal);
              Object.keys(cutiData).forEach(jabatan => {
                allJabatans.add(jabatan);
              });
            });

            const jabatanList = Array.from(allJabatans);

            const dateLabels = [...new Set(allDates)]
              .map(date => new Date(date))
              .sort((a, b) => a - b)
              .map(date => date.toISOString().split('T')[0]);

            const series = jabatanList.map(jabatan => ({
              name: jabatan,
              data: dateLabels.map(date => {
                const dataRow = rawData.find(row => row.tanggal === date);
                const cutiData = dataRow?.cuti_per_jabatan ? JSON.parse(dataRow.cuti_per_jabatan) : {};
                return parseInt(cutiData[jabatan] || 0);
              })
            }));

            const colors = getRandomColors('#columnChartPengajuanCutiPerHari', series.length);

            const options = {
              chart: {
                type: 'bar',
                height: 350,
                stacked: false,
                toolbar: {
                  show: false
                }
              },
              plotOptions: {
                bar: {
                  horizontal: false,
                  columnWidth: '40%'
                }
              },
              dataLabels: {
                enabled: false
              },
              stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
              },
              xaxis: {
                categories: dateLabels,
                title: {
                  text: 'Tanggal',
                  style: {
                    fontWeight: '500'
                  }
                }
              },
              yaxis: {
                title: {
                  text: 'Jumlah Pengajuan Cuti',
                  style: {
                    fontWeight: '500'
                  }
                }
              },
              colors: colors,
              series: series,
              tooltip: {
                enabled: true,
                shared: false,
                intersect: true,
                custom: function({
                  series,
                  seriesIndex,
                  dataPointIndex,
                  w
                }) {
                  const tanggal = w.config.xaxis.categories[dataPointIndex];
                  const color = w.config.colors[seriesIndex];
                  const value = series[seriesIndex][dataPointIndex];
                  const name = w.config.series[seriesIndex].name;

                  return `
                    <div class="apexcharts-tooltip-title" style="text-align:center; font-size:12px; font-weight:500;">
                        ${tanggal}
                    </div>
                    <div style="padding: 6px 10px;">
                        <span style="display:inline-flex; align-items:center;">
                            <span style="background-color: ${color}; width:10px; height:10px; border-radius:50%; margin-right:6px;"></span>
                            ${name}:&nbsp;<strong>${value} pengajuan</strong>
                        </span>
                    </div>
                `;
                }
              },
              grid: {
                borderColor: '#f1f1f1'
              },
              fill: {
                opacity: 1
              }
            };

            if (chartPengajuanCutiPerHari_spv !== null) {
              chartPengajuanCutiPerHari_spv.destroy();
            }

            chartPengajuanCutiPerHari_spv = new ApexCharts(
              document.querySelector("#columnChartPengajuanCutiPerHari"),
              options
            );
            chartPengajuanCutiPerHari_spv.render();
          }
        });
        ajaxReq.push(getChartPengajuanCutiPerHari_spv);

        var datatablePengajuanTerbaru_spv = initDatatable("#datatablePengajuanTerbaru_spv", {
          url: url + "datatablePengajuanTerbaru_spv",
          csrf: coreEvents.csrf,
          // dom: 'lfrtip' // buat nampilin show x entries
          filter: {
            tgl_mulai: tgl_mulai,
            tgl_selesai: tgl_selesai,
          },
          columns: [{
              data: null,
              className: "text-center align-middle",
              orderable: false,
              width: 1,
              render: function(a, type, data, index) {
                return dataStart + index.row + 1
              }
            },
            {
              data: "user_web_role_name",
              className: "text-start align-middle",
              orderable: true,
              width: 100
            },
            {
              data: "karyawan_nama",
              className: "text-start align-middle",
              orderable: true,
              width: 100
            },
            {
              data: "jenis_pengajuan",
              className: "text-start align-middle",
              orderable: true,
              width: 100
            },
            {
              data: "tgl_pengajuan",
              className: "text-start align-middle",
              orderable: true,
              width: 100
            },
            {
              data: "keperluan",
              className: "text-start align-middle",
              orderable: true,
              width: 100,
            },
            {
              data: "verifikasi",
              className: "text-start align-middle",
              orderable: true,
              width: 100,
              render: function(data, type, row) {
                return `<span class="badge bg-secondary w-100 text-center">${data}</span>`;
              }
            },
            {
              data: "status",
              className: "text-start align-middle",
              orderable: true,
              width: 100,
              render: function(data, type, row) {
                return `<span class="badge bg-info p-1 w-100 text-center"><i class='fas fa-hourglass'></i> <span class="ms-2">${data}</span></span>`;
              }
            },
          ],
        });
        ajaxReq.push(datatablePengajuanTerbaru_spv);


      } else { // karyawan

      }
    }


    $.when.apply($, ajaxReq).done(function() {
      console.log(ajaxReq.length + ' request done');
    });
  }

  function loadCakarSummary() {
    $.ajax({
      url: '<?= base_url() ?>/simpeg/ajax/getCakarSummaryForHR',
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          const data = response.data;
          
          // Update counts
          $('#cakar-summary-baik').text(data.countByType.baik || 0);
          $('#cakar-summary-buruk').text(data.countByType.buruk || 0);
          $('#cakar-summary-info').text(data.countByType.info || 0);
          
          // Update latest notes table
          let latestHTML = '';
          if (data.latest.length === 0) {
            latestHTML = '<tr><td colspan="4" class="text-center">Tidak ada data</td></tr>';
          } else {
            data.latest.forEach(item => {
              let badgeClass = 'bg-info';
              if (item.catatan_jenis === 'baik') badgeClass = 'bg-success';
              if (item.catatan_jenis === 'buruk') badgeClass = 'bg-danger';
              
              latestHTML += `
                <tr>
                  <td>${item.karyawan_nama}</td>
                  <td><span class="badge ${badgeClass}">${item.jenis_display}</span></td>
                  <td>${item.catatan_judul}</td>
                  <td>${item.created_at_formatted}</td>
                </tr>
              `;
            });
          }
          $('#cakar-latest-tbody').html(latestHTML);
          
          // Update chart
          if (data.byDivisi.length > 0) {
            renderCakarByDivisiChart(data.byDivisi);
          }
        }
      },
      error: function() {
        console.error('Failed to load catatan karyawan summary');
      }
    });
  }
  
  function renderCakarByDivisiChart(data) {
    var colors = ["#2ab57d", "#fd625e", "#28518fff", "#ffbf53"];
    var options = {
      series: [{
        name: 'Catatan Baik',
        data: data.map(item => item.baik || 0)
      }, {
        name: 'Catatan Buruk',
        data: data.map(item => item.buruk || 0)
      }, {
        name: 'Catatan Info',
        data: data.map(item => item.info || 0)
      }],
      chart: {
        type: 'bar',
        height: 250,
        toolbar: {
          show: false
        },
        background: '#ffffff'
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '45%',
          endingShape: 'rounded'
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      xaxis: {
        categories: data.map(item => item.divisi_nama),
        labels: {
          style: { fontSize: '12px', fontWeight: 500, fontStyle: 'normal' },
          rotate: 0, // <-- pastikan 0 agar horizontal
          formatter: function(val) {
            return val.length > 12 ? val.substring(0, 12) + '…' : val;
          }
        }
      },
      yaxis: {
        title: {
          text: 'Jumlah Catatan'
        },
        labels: {
          style: {
            fontSize: '12px'
          }
        }
      },
      grid: {
        borderColor: '#f1f1f1',
        backgroundColor: '#ffffff'
      },
      fill: {
        opacity: 1
      },
      tooltip: {
        y: {
          formatter: function(value) {
            return value + " catatan";
          }
        },
        custom: function({ series, seriesIndex, dataPointIndex, w }) {
          var name = w.globals.seriesNames[seriesIndex];
          var value = series[seriesIndex][dataPointIndex];
          var divisi = w.globals.labels[dataPointIndex];
          var color = w.globals.colors[seriesIndex];
          return `
          <div style="padding: 6px 10px; font-weight:500; background-color: #ffffff;">
            ${divisi}
          </div>
          <div style="padding: 6px 10px; background-color: #ffffff;">
            <span style="display:inline-flex; align-items:center;">
              <span style="background-color: ${color}; width:10px; height:10px; border-radius:50%; margin-right:6px;"></span>
              ${name}:&nbsp;<strong>${value} catatan</strong>
            </span>
          </div>
        `;
        }
      },
      legend: {
        position: 'bottom',
        fontSize: '12px'
      },
      colors: colors
    };

    if (window.cakarByDivisiChart) {
      window.cakarByDivisiChart.destroy();
    }
    
    window.cakarByDivisiChart = new ApexCharts(document.querySelector("#cakar-by-divisi-chart"), options);
    window.cakarByDivisiChart.render();
  }


  function loadNumberIterate(number, idHtml, isRupiah = false, duration = 1000) {
    const start = performance.now();
    const targetValue = number;

    function animate(currentTime) {
      const elapsed = currentTime - start;
      const progress = Math.min(elapsed / duration, 1);
      const currentValue = Math.floor(progress * targetValue);
      if (isRupiah) {
        $(idHtml).html(formatRupiah(currentValue));
      } else {
        $(idHtml).html(currentValue);
      }
      if (progress < 1) {
        requestAnimationFrame(animate);
      }
    }
    requestAnimationFrame(animate);
  }


  function formatRupiah(angka) {
    return angka.toLocaleString('id-ID');
  }


  function getChartColorsArray(chartId) {
    var colors = $(chartId).attr('data-colors');
    var colors = JSON.parse(colors);
    return colors.map(function(value) {
      var newValue = value.replace(' ', '');
      if (newValue.indexOf('--') != -1) {
        var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
        if (color) return color;
      } else {
        return newValue;
      }
    })
  }

  function initDatatable(selector, options = {}) {
    const defaultOptions = {
      serverSide: true,
      cache: false,
      async: true,
      processing: true,
      ordering: true,
      paging: true,
      searchDelay: 2000,
      searching: {
        regex: true
      },
      lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]
      ],
      pageLength: 5,
      language: {
        searchPlaceholder: options.placeholder || "Cari...",
        processing: "<div class='spinner-border spinner-border-sm' role='status'></div>",
        sProcessing: "<img src='/assets/gif/spinner.gif' width='100px' height='100px' />",
      },
      ajax: {
        type: "POST",
        url: options.url, // wajib isi
        dataType: "json",
        data: function(data) {
          if (options.filter) {
            data.filter = options.filter;
          }

          dataStart = data.start;
          let form = {};
          Object.keys(data).forEach(function(key) {
            form[key] = data[key] || "";
          });

          let info = {
            start: data.start || 0,
            length: data.length,
            draw: 1
          };

          $.extend(form, info);
          if (options.csrf) {
            $.extend(form, options.csrf);
          }

          return form;
        },
        complete: function() {
          $('.otorisasi, .tooltipp').tooltip({
            html: true,
            delay: {
              show: 100,
              hide: 0
            }
          });
        }
      },
      dom: options.dom || 'Bfrtip',
      buttons: options.buttons || [],
      columns: options.columns,
      columnDefs: options.columnDefs || [],
      fixedColumns: options.fixedColumns || false,
      initComplete: options.initComplete || null
    };

    // Reset dulu
    $(selector).DataTable().clear().destroy();

    // Inisialisasi
    return $(selector).DataTable(defaultOptions).on('init.dt', function() {
      $(this).css('width', '100%');
    });
  }

  function getChartColorsArray(chartId) {
    let colors = $(chartId).attr('data-colors');
    colors = JSON.parse(colors);
    return colors.map(function(value) {
      const newValue = value.replace(' ', '');
      if (newValue.indexOf('--') !== -1) {
        const color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
        return color ? color.trim() : newValue;
      } else {
        return newValue;
      }
    });
  }

  function generateRandomColors(count) {
    const colors = [];
    for (let i = 0; i < count; i++) {
      const r = Math.floor(Math.random() * 200 + 30);
      const g = Math.floor(Math.random() * 200 + 30);
      const b = Math.floor(Math.random() * 200 + 30);
      colors.push(`rgb(${r}, ${g}, ${b})`);
    }
    return colors;
  }

  function getRandomColors(chartId, totalNeeded) {
    let finalColors = [];

    let dataColors = $(chartId).attr('data-colors');
    try {
      dataColors = JSON.parse(dataColors).map(function(value) {
        const cleaned = value.replace(' ', '');
        if (cleaned.indexOf('--') !== -1) {
          const cssColor = getComputedStyle(document.documentElement).getPropertyValue(cleaned);
          return cssColor ? cssColor.trim() : cleaned;
        } else {
          return cleaned;
        }
      });
    } catch (e) {
      console.warn("data-colors tidak valid, akan lanjut ke warna acak.");
      dataColors = ["#2ab57d", "#5156be", "#fd625e", "#4ba6ef", "#ffbf53"];
    }

    finalColors = [...dataColors];

    const sisa = totalNeeded - finalColors.length;
    if (sisa > 0) {
      finalColors = finalColors.concat(generateRandomColors(sisa));
    }

    return finalColors;
  }

  function LoadChartLokasi() {
    fetch('<?= base_url() ?>/main/ajax/getLokasiBarang')
      .then(response => response.json())
      .then(data => {
        if (!data.data || data.data.length === 0) {
          throw new Error("Data lokasi tidak ditemukan");
        }

        // Siapkan data chart dengan warna berdasarkan status_pinjam
        const chartData = data.data.map(loc => ({
          country: loc.nama_lokasi,
          value: parseInt(loc.jumlah_barang),
          color: loc.status_pinjam === "Tersedia" ? "#5156be" : "#a0a0a0"
        }));

        am5.ready(function() {
          // Dispose root sebelumnya agar tidak crash saat rerender
          am5.registry.rootElements.forEach(root => {
            if (root.dom.id === "chartdiv") root.dispose();
          });

          var root = am5.Root.new("chartdiv");
          root.setThemes([am5themes_Animated.new(root)]);

          var chart = root.container.children.push(
            am5xy.XYChart.new(root, {
              layout: root.verticalLayout,
              panX: false,
              panY: false,
              wheelX: "none",
              wheelY: "none",
              paddingLeft: 0,
              paddingRight: 0
            })
          );

          // Tambahkan cursor untuk interaksi
          var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
          cursor.lineY.set("visible", false);

          var xRenderer = am5xy.AxisRendererX.new(root, {
            minGridDistance: 30
          });

          xRenderer.labels.template.setAll({
            rotation: -45,
            centerY: am5.p50,
            centerX: am5.p100,
            paddingRight: 15
          });

          var xAxis = chart.xAxes.push(
            am5xy.CategoryAxis.new(root, {
              categoryField: "country",
              renderer: xRenderer,
              tooltip: am5.Tooltip.new(root, {})
            })
          );

          var yAxis = chart.yAxes.push(
            am5xy.ValueAxis.new(root, {
              renderer: am5xy.AxisRendererY.new(root, {})
            })
          );

          var series = chart.series.push(
            am5xy.ColumnSeries.new(root, {
              name: "Jumlah Barang",
              xAxis: xAxis,
              yAxis: yAxis,
              valueYField: "value",
              categoryXField: "country",
              tooltip: am5.Tooltip.new(root, {
                labelText: "{categoryX}: {valueY} Barang"
              })
            })
          );

          series.columns.template.setAll({
            cornerRadiusTL: 6,
            cornerRadiusTR: 6,
            width: am5.percent(70),
            strokeOpacity: 0,
            interactive: true
          });

          // Warna kolom berdasarkan status_pinjam
          series.columns.template.adapters.add("fill", function(fill, target) {
            const color = target.dataItem?.dataContext?.color;
            return color ? color : chart.get("colors").getIndex(series.columns.indexOf(target));
          });

          series.columns.template.adapters.add("stroke", function(stroke, target) {
            const color = target.dataItem?.dataContext?.color;
            return color ? color : chart.get("colors").getIndex(series.columns.indexOf(target));
          });

          // Set data chart
          xAxis.data.setAll(chartData);
          series.data.setAll(chartData);

          series.appear(1000);
          chart.appear(1000, 100);
        });

        // Tambahkan legend jika belum ada
        let legendDiv = document.getElementById("chart_legend_lokasi");
        if (!legendDiv) {
          legendDiv = document.createElement("div");
          legendDiv.id = "chart_legend_lokasi";
          legendDiv.style.marginTop = "20px";
          legendDiv.style.textAlign = "center";
          document.getElementById("chartdiv").after(legendDiv);
        }
        legendDiv.innerHTML = `
          <div style="display: flex; justify-content: center; gap: 20px; margin-top: 10px; font-size: 14px;">
            <div style="display: flex; align-items: center;">
              <span style="width: 15px; height: 15px; background-color: #5156be; display: inline-block; margin-right: 5px; border-radius: 3px;"></span>
              Tersedia
            </div>
            <div style="display: flex; align-items: center;">
              <span style="width: 15px; height: 15px; background-color: #a0a0a0; display: inline-block; margin-right: 5px; border-radius: 3px;"></span>
              Tidak Tersedia
            </div>
          </div>
        `;
      })
      .catch(error => console.error("Error fetching data:", error));
  }

  function LoadJumlahBarang() {
    let root, chart, series, yAxis;

    function renderAm5Chart(dataArray) {
      if (root) root.dispose();
      root = am5.Root.new("bar_chart");

      root.numberFormatter.setAll({
        numberFormat: "#a",
        bigNumberPrefixes: [{
            number: 1e6,
            suffix: "M"
          },
          {
            number: 1e9,
            suffix: "B"
          }
        ],
        smallNumberPrefixes: []
      });

      const stepDuration = 2000;

      root.setThemes([am5themes_Animated.new(root)]);

      chart = root.container.children.push(am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "none",
        wheelY: "none",
        paddingLeft: 0
      }));

      chart.zoomOutButton.set("forceHidden", true);

      const yRenderer = am5xy.AxisRendererY.new(root, {
        minGridDistance: 20,
        inversed: true,
        minorGridEnabled: true
      });
      yRenderer.grid.template.set("visible", false);

      yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
        maxDeviation: 0,
        categoryField: "network",
        renderer: yRenderer
      }));

      const xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
        maxDeviation: 0,
        min: 0,
        strictMinMax: true,
        extraMax: 0.1,
        renderer: am5xy.AxisRendererX.new(root, {})
      }));

      xAxis.set("interpolationDuration", stepDuration / 10);
      xAxis.set("interpolationEasing", am5.ease.linear);

      series = chart.series.push(am5xy.ColumnSeries.new(root, {
        xAxis: xAxis,
        yAxis: yAxis,
        valueXField: "value",
        categoryYField: "network"
      }));

      series.columns.template.setAll({
        cornerRadiusBR: 5,
        cornerRadiusTR: 5
      });

      series.columns.template.adapters.add("fill", (fill, target) => {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
      });
      series.columns.template.adapters.add("stroke", (stroke, target) => {
        return chart.get("colors").getIndex(series.columns.indexOf(target));
      });

      series.bullets.push(() => {
        return am5.Bullet.new(root, {
          locationX: 1,
          sprite: am5.Label.new(root, {
            text: "{valueXWorking.formatNumber('#.# a')}",
            fill: root.interfaceColors.get("alternativeText"),
            centerX: am5.p100,
            centerY: am5.p50,
            populateText: true
          })
        });
      });

      yAxis.data.setAll(dataArray);
      series.data.setAll(dataArray);

      function getSeriesItem(category) {
        return series.dataItems.find(item => item.get("categoryY") === category);
      }

      function sortCategoryAxis() {
        series.dataItems.sort((x, y) => y.get("valueX") - x.get("valueX"));
        am5.array.each(yAxis.dataItems, function(dataItem) {
          let seriesItem = getSeriesItem(dataItem.get("category"));
          if (seriesItem) {
            let index = series.dataItems.indexOf(seriesItem);
            let delta = (index - dataItem.get("index", 0)) / series.dataItems.length;
            if (dataItem.get("index") !== index) {
              dataItem.set("index", index);
              dataItem.set("deltaPosition", -delta);
              dataItem.animate({
                key: "deltaPosition",
                to: 0,
                duration: stepDuration / 2,
                easing: am5.ease.out(am5.ease.cubic)
              });
            }
          }
        });
        yAxis.dataItems.sort((x, y) => x.get("index") - y.get("index"));
      }

      setTimeout(() => {
        sortCategoryAxis();
      }, 100);

      series.appear(1000);
      chart.appear(1000, 100);
    }

    function fetchDataAndRenderChart() {
      fetch("<?= base_url() ?>/main/ajax/getBarang")
        .then(res => res.json())
        .then(data => {
          if (!data.data) throw new Error("Data barang tidak ditemukan");
          const chartData = data.data.map(item => ({
            network: item.nama_aset,
            value: parseInt(item.jumlah)
          }));
          renderAm5Chart(chartData);
        })
        .catch(err => {
          console.error("Error data barang:", err);
          // Optional: tampilkan pesan error di chart
          if (root) root.dispose();
          document.getElementById("bar_chart").innerHTML = "<div class='text-danger text-center p-4'>Gagal memuat data barang</div>";
        });
    }

    fetchDataAndRenderChart();
  }

  $(document).ready(function() {
    LoadChartLokasi();
    LoadJumlahBarang();
  });

  $(document).on("click", ".lengkap", function() {
    const lokasi = $(this).data("lokasi");
    $("#modal-body").html("<li class='text-center'>Loading...</li>");
    $("#modal-title").text(lokasi);
    $.ajax({
      url: "<?= base_url('complain/ajax/getComplainDetail') ?>",
      type: "GET",
      data: {
        lokasi: lokasi
      },
      dataType: "json",
      success: function(response) {
        if (response.success && response.data.length > 0) {
          let html = `<table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Foto Complain</th>
                                            <th>Email</th>
                                            <th>Lokasi Masalah</th>
                                            <th>Periode</th>
                                            <th>Jenis Bus</th>
                                            <th>Koridor</th>
                                            <th>Nomor Lambung</th>
                                            <th>Nama Layanan</th>
                                            <th>IMEI</th>
                                            <th>Device ID</th>
                                            <th>Perangkat</th>
                                            <th>Kategori Permasalahan</th>
                                            <th>Sub Kategori Permasalahan</th>
                                            <th>Tanggal Kejadian</th>
                                            <th>Waktu Kejadian</th>
                                            <th>Timestamp</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

          response.data.forEach((item, index) => {
            html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.foto ? `<img src="<?= base_url() ?>/${item.foto}" width="100" />` : '-'}</td>
                                <td>${item.email}</td>
                                <td>${item.lokasi_masalah}</td>
                                <td>${item.periode}</td>
                                <td>${item.jenis_bus}</td>
                                <td>${item.koridor}</td>
                                <td>${item.nomor_lambung}</td>
                                <td>${item.nama_layanan}</td>
                                <td>${item.imei}</td>
                                <td>${item.device_id}</td>
                                <td>${item.perangkat}</td>
                                <td>${item.kategori_permasalahan}</td>
                                <td>${item.sub_kategori_permasalahan}</td>
                                <td>${item.tanggal_kejadian}</td>
                                <td>${item.waktu_kejadian}</td>
                                <td>${item.timestamp}</td>
                            </tr>`;
          });

          html += `
                        </tbody>
                    </table>`;
          $("#modal-body").html(html);
          $("#modal-log").modal("show");
        } else {
          $("#modal-body").html("<p class='text-danger text-center'>Data tidak ditemukan</p>");
        }
      },
      error: function() {
        $("#modal-body").html("<p class='text-danger text-center'>Gagal mengambil data</p>");
      },
    });
  });


  maptilersdk.config.apiKey = 'sE1SyP9yF4U9lB4KDB3Q';
  let map;

  window.onload = function() {
    map = new maptilersdk.Map({
      container: 'map',
      GamepadButton: false,
      style: maptilersdk.MapStyle.STREETS.BASIC,
      center: [110.410300, -6.984929], // Adjusted center coordinates
      zoom: 12, // Adjusted zoom level for better overview
      pitch: 50,
      minZoom: 8, // Adjusted minimum zoom level
      bearing: -17.6,
    });

    fetch('<?= base_url() ?>/complain/ajax/getLokasiMasalahDashboard')
      .then(response => response.json())
      .then(data => {
        data.forEach(loc => {
          const lokasiName = loc.text;
          const query = encodeURIComponent(lokasiName);

          fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`, {
              headers: {
                'User-Agent': 'CI4MapApp/1.0'
              }
            })
            .then(res => res.json())
            .then(geo => {
              if (geo.length > 0) {
                const lat = parseFloat(geo[0].lat);
                const lon = parseFloat(geo[0].lon);

                const customMarkerElement = createCustomMarker();

                const marker = new maptilersdk.Marker({
                  element: customMarkerElement
                }).setLngLat([lon, lat]).addTo(map);

                fetch(`<?= base_url() ?>/complain/ajax/getRingkasanComplain/${query}`)
                  .then(res => res.json())
                  .then(ringkasan => {
                    let jenisText = '';
                    if (ringkasan.jenis_complain && ringkasan.jenis_complain.length > 0) {
                      ringkasan.jenis_complain.forEach(j => {
                        jenisText += `${j.kategori_permasalahan}: ${j.jumlah}<br>`;
                      });
                    } else {
                      jenisText = 'Tidak ada data<br>';
                    }

                    let popupContent = `
                                            <b>${lokasiName}</b><br>
                                            Total Complain: ${ringkasan.total_complain}<br>
                                            Jenis Complain:<br>${jenisText}
                                            Terakhir: ${ringkasan.terakhir}
                                            <br>`;
                    if (ringkasan.total_complain > 0) {
                      popupContent += `
                                                <button class="btn lengkap btn-sm btn-outline-success mt-2"
                                                    data-lokasi="${lokasiName}">
                                                    <i class="fa fa-eye"></i> Lihat Detail
                                                </button>`;
                    }

                    const popup = new maptilersdk.Popup({
                      closeButton: false,
                      closeOnClick: false,
                      offset: [0, -25]
                    }).setHTML(popupContent);

                    marker.getElement().addEventListener('click', () => {
                      popup.setLngLat([lon, lat]).addTo(map);
                    });
                  });
              }
            });
        });
      });
  };

  function createCustomMarker() {
    const markerEl = document.createElement('div');
    markerEl.style.backgroundImage = 'url(https://devel74.nginovasi.id/sobat_hss/assets/images/ngi_pin.png)';
    markerEl.style.backgroundSize = 'contain';
    markerEl.style.width = '50px';
    markerEl.style.height = '50px';
    markerEl.style.backgroundRepeat = 'no-repeat';
    return markerEl;
  }

  function loadEmployeeNotes() {
    $.ajax({
      url: '<?= base_url() ?>/simpeg/ajax/getCatatanKaryawan',
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        let html = '';
        const $countEl = $('#catatan-count');
        const $previewEl = $('#catatan-karyawan-preview');

        // Safety guard: kalau container-nya nggak ada, stop
        if ($countEl.length === 0 || $previewEl.length === 0) return;

        const list = Array.isArray(response?.data) ? response.data : [];

        if (response?.success && list.length > 0) {
          // --- (2) sort by created_at desc jika API nggak jamin urutan
          const sorted = list.slice().sort((a, b) => {
            const da = new Date(a.created_at ?? a.created_at_formatted ?? 0);
            const db = new Date(b.created_at ?? b.created_at_formatted ?? 0);
            return db - da;
          });

          // Update badge count
          $countEl.text(sorted.length);

          // Tampilkan 3 terbaru
          sorted.slice(0, 3).forEach(function(note) {
            let badgeClass = 'bg-secondary';
            let icon = 'fas fa-question-circle';

            if (note.catatan_jenis === 'baik') {
              badgeClass = 'bg-soft-success text-success';
              icon = 'fas fa-thumbs-up';
            } else if (note.catatan_jenis === 'buruk') {
              badgeClass = 'bg-soft-danger text-danger';
              icon = 'fas fa-thumbs-down';
            } else if (note.catatan_jenis === 'info') {
              badgeClass = 'bg-soft-info text-info';
              icon = 'fas fa-info-circle';
            }

            html += `
              <div class="note-item" data-id="${note.id}">
                <div class="card">
                  <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h6 class="card-title mb-1">${note.catatan_judul ?? '-'}</h6>
                        <small class="text-muted">
                          <i class="fas fa-calendar-alt me-1"></i>${note.created_at_formatted ?? '-'}
                        </small>
                      </div>
                      <span class="badge ${badgeClass} px-2 py-1" aria-label="${note.catatan_jenis ?? 'lainnya'}">
                        <i class="${icon}"></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            `;
          });

          // --- (1) HILANGKAN inline onclick — cukup id saja
          // moved "Lihat Semua Catatan" link to the card header to improve accessibility/usability

          // simpan untuk modal
          window.allCatatanKaryawanData = sorted;
        } else {
          $countEl.text('0');
          html = `
            <div class="text-center text-muted py-4">
              <i class="fas fa-sticky-note fa-3x mb-3 text-light"></i>
              <p>Belum ada catatan untuk Anda.</p>
            </div>
          `;
          window.allCatatanKaryawanData = [];
        }

        $previewEl.html(html);
      },
      error: function() {
        $('#catatan-count').text('!');
        $('#catatan-karyawan-preview').html(`
          <div class="text-center text-danger py-4">
            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
            <p>Gagal memuat catatan. Silakan coba lagi nanti.</p>
          </div>
        `);
        window.allCatatanKaryawanData = [];
      }
    });
  }
  
  function showCatatanKaryawanDetail(noteId) {
    // Find the selected note from stored data
    const selectedNote = window.allCatatanKaryawanData.find(note => note.id == noteId);
    
    if (!selectedNote) {
      Swal.fire('Error', 'Catatan tidak ditemukan', 'error');
      return;
    }
    
    let badgeClass = 'bg-secondary';
    let jenisTeks = 'Umum';
    let icon = 'fas fa-question-circle';
    
    if (selectedNote.catatan_jenis === 'baik') {
      badgeClass = 'bg-success';
      jenisTeks = 'Baik';
      icon = 'fas fa-thumbs-up';
    } else if (selectedNote.catatan_jenis === 'buruk') {
      badgeClass = 'bg-danger';
      jenisTeks = 'Buruk';
      icon = 'fas fa-thumbs-down';
    } else if (selectedNote.catatan_jenis === 'info') {
      badgeClass = 'bg-info';
      jenisTeks = 'Info';
      icon = 'fas fa-info-circle';
    }
    
    const modalContent = `
      <div class="modal-body">
        <div class="mb-4">
          <span class="badge ${badgeClass} mb-2">
            <i class="${icon} me-1"></i> ${jenisTeks}
          </span>
          <h4>${selectedNote.catatan_judul}</h4>
          <small class="text-muted">
            <i class="fas fa-calendar me-1"></i>${selectedNote.created_at_formatted}
            <span class="ms-3"><i class="fas fa-user me-1"></i>Oleh: ${selectedNote.created_by_name || 'Admin'}</span>
          </small>
        </div>
        
        <div class="card">
          <div class="card-body">
            ${selectedNote.catatan_deskripsi ? selectedNote.catatan_deskripsi : '<p class="text-muted">Tidak ada deskripsi</p>'}
          </div>
        </div>
      </div>
    `;
    
    $('#modalDetail .modal-title').text('Detail Catatan Karyawan');
    $('#modalDetailBody').html(modalContent);
    $('#modalDetail').modal('show');
  }
  
  function showAllCatatanKaryawan() {
    if (!window.allCatatanKaryawanData || window.allCatatanKaryawanData.length === 0) {
      Swal.fire('Info', 'Tidak ada catatan karyawan yang tersedia', 'info');
      return;
    }
    
    let html = '<div class="list-group">';
    
    window.allCatatanKaryawanData.forEach(function(note) {
      let badgeClass = 'bg-secondary';
      let icon = 'fas fa-question-circle';
      
      if (note.catatan_jenis === 'baik') {
        badgeClass = 'bg-success';
        icon = 'fas fa-thumbs-up';
      } else if (note.catatan_jenis === 'buruk') {
        badgeClass = 'bg-danger';
        icon = 'fas fa-thumbs-down';
      } else if (note.catatan_jenis === 'info') {
        badgeClass = 'bg-info';
        icon = 'fas fa-info-circle';
      }
      
      html += `
        <div class="list-group-item list-group-item-action note-item-all" data-id="${note.id}">
          <div class="d-flex w-100 justify-content-between align-items-center">
            <h6 class="mb-1">${note.catatan_judul}</h6>
            <span class="badge ${badgeClass}"><i class="${icon}"></i></span>
          </div>
          <small class="text-muted">
            <i class="fas fa-calendar-alt me-1"></i>${note.created_at_formatted}
          </small>
          ${note.catatan_deskripsi ? 
            `<p class="mb-1 text-truncate mt-2" style="max-width: 100%;">${note.catatan_deskripsi}</p>` : ''}
        </div>
      `;
    });
    
    html += '</div>';
    
    $('#modalDetail .modal-title').text('Semua Catatan Karyawan');
    $('#modalDetailBody').html(html);
    $('#modalDetail').modal('show');
  }

  function loadRekapKeluhanDashboard() {
    $.ajax({
      url: baseUrl + '/simpeg/action/getRekapKeluhanHR',
      type: 'GET',
      dataType: 'json',
      success: function(res) {
        if (!(res && res.success && res.data)) return;

        $('#keluhan-menunggu').text(res.data.menunggu ?? 0);
        $('#keluhan-ditanggapi').text(res.data.ditanggapi ?? 0);
        $('#keluhan-ditutup').text(res.data.ditutup ?? 0);
        $('#keluhan-total').text(res.data.total ?? 0);

        const $wrap = $('#keluhan-by-divisi-chart');
        if (!($wrap.length)) return;

        const byDivisi = Array.isArray(res.data.byDivisi) ? res.data.byDivisi : [];
        if (byDivisi.length === 0) {
          $wrap.html('<div class="text-muted text-center">Tidak ada data</div>');
          if (window.keluhanByDivisiChart) { window.keluhanByDivisiChart.destroy(); window.keluhanByDivisiChart = null; }
          return;
        }

        const labels = byDivisi.map(d => d.divisi_nama);
        const menunggu  = byDivisi.map(d => parseInt(d.menunggu, 10)  || 0);
        const ditanggapi= byDivisi.map(d => parseInt(d.ditanggapi,10) || 0);
        const ditutup   = byDivisi.map(d => parseInt(d.ditutup, 10)   || 0);

        if (window.keluhanByDivisiChart) window.keluhanByDivisiChart.destroy();
        window.keluhanByDivisiChart = new ApexCharts($wrap[0], {
          chart: { type: 'bar', height: 180, stacked: true, toolbar: { show: false } },
          series: [
            { name: 'Menunggu',   data: menunggu },
            { name: 'Ditanggapi', data: ditanggapi },
            { name: 'Ditutup',    data: ditutup }
          ],
          xaxis: {
            categories: labels,
            labels: {
              style: { fontSize: '12px', fontWeight: 500 },
              rotate: 0,
              formatter: (val) => (val && val.length > 12 ? val.substring(0,12) + '…' : val)
            }
          },
          colors: ['#ffc107', '#28a745', '#dc3545'],
          legend: { position: 'bottom', fontSize: '12px' },
          plotOptions: { bar: { horizontal: false, borderRadius: 4, columnWidth: '50%' } },
          dataLabels: { enabled: false }
        });
        window.keluhanByDivisiChart.render();
      }
    });
  }

  function loadRekapAduanDashboard() {
    $.ajax({
      url: baseUrl + '/simpeg/action/getRekapAduanHR',
      type: 'GET',
      dataType: 'json',
      success: function(res) {
        if (!(res && res.success && res.data)) return;

        $('#aduan-menunggu').text(res.data.menunggu ?? 0);
        $('#aduan-ditanggapi').text(res.data.ditanggapi ?? 0);

        const $wrap = $('#aduan-by-divisi-chart');
        if (!($wrap.length)) return;

        const byDivisi = Array.isArray(res.data.byDivisi) ? res.data.byDivisi : [];
        if (byDivisi.length === 0) {
          $wrap.html('<div class="text-muted text-center">Tidak ada data</div>');
          if (window.aduanByDivisiChart) { window.aduanByDivisiChart.destroy(); window.aduanByDivisiChart = null; }
          return;
        }

        const labels = byDivisi.map(d => d.divisi_nama);
        const menunggu   = byDivisi.map(d => parseInt(d.menunggu, 10)  || 0);
        const ditanggapi = byDivisi.map(d => parseInt(d.ditanggapi,10) || 0);

        if (window.aduanByDivisiChart) window.aduanByDivisiChart.destroy();
        window.aduanByDivisiChart = new ApexCharts($wrap[0], {
          chart: { type: 'bar', height: 180, stacked: true, toolbar: { show: false } },
          series: [
            { name: 'Menunggu',   data: menunggu },
            { name: 'Ditanggapi', data: ditanggapi }
          ],
          xaxis: {
            categories: labels,
            labels: {
              style: { fontSize: '12px', fontWeight: 500 },
              rotate: 0,
              formatter: (val) => (val && val.length > 12 ? val.substring(0,12) + '…' : val)
            }
          },
          colors: ['#ffc107', '#28a745'], // kuning & hijau
          legend: { position: 'bottom', fontSize: '12px' },
          plotOptions: { bar: { horizontal: false, borderRadius: 4, columnWidth: '50%' } },
          dataLabels: { enabled: false }
        });
        window.aduanByDivisiChart.render();
      }
    });
  }


</script>
