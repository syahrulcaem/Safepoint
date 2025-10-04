<!-- preloader css -->
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/preloader.min.css" type="text/css" />

<!-- Bootstrap Css -->
<link href="<?= base_url() ?>/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- <link href="<?= base_url() ?>/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" /> -->
<!-- <link href="<?= base_url() ?>/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" /> -->
<!-- Icons Css -->
<link href="<?= base_url() ?>/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="<?= base_url() ?>/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<!-- <script src="<?= base_url() ?>/assets/libs/jquery/jquery.min.js" defer="defer"></script> -->
<script src="<?= base_url() ?>/assets/libs/jquery/jquery.min.js"></script>

<!-- Datepicker jquery-ui -->
<script src="<?= base_url() ?>/assets/libs/jquery-ui/jquery-ui.min.js"></script>
<link href="<?= base_url() ?>/assets/libs/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />

<!-- datepicker css -->
<link rel="stylesheet" href="<?= base_url() ?>/assets/libs/flatpickr/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/libs/leaflet/leaflet.css">
<script src="<?= base_url() ?>/assets/libs/flatpickr/flatpickr.min.js"></script>

<!-- Date Range Picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<!-- Select2 -->
<link href="<?= base_url() ?>/assets/libs/select2/select2-min.css" rel="stylesheet" />
<script src="<?= base_url() ?>/assets/libs/select2/select2-min.js"></script>
<script src="<?= base_url() ?>/assets/js/html2canvas.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- alertifyjs Css -->
<link href="<?= base_url() ?>/assets/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet" type="text/css" />
<!-- alertifyjs default themes  Css -->
<link href="<?= base_url() ?>/assets/libs/alertifyjs/build/css/themes/default.min.css" rel="stylesheet" type="text/css" />
<style>
    .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 36px;
        user-select: none;
        -webkit-user-select: none;
    }

    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 34px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
        position: absolute;
        top: 1px;
        right: 5px;
        width: 20px;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        display: block;
        padding-left: 15px;
        padding-right: 20px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: darkgray;
        opacity: 0.9;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        cursor: pointer;
        float: right;
        font-weight: bold;
        height: 34px;
        margin-right: 26px;
        padding-right: 0px;
    }

    .action-button {
        display: flex;
    }

    .action-button>button {
        margin: 0 2px;
    }

    .text-muted a,
    .text-success a {
        cursor: pointer;
        position: relative;
        display: inline-block;
    }

    .text-muted a:hover,
    text-success a:hover {
        filter: brightness(75%);
    }

    img.img-kota {
        filter: brightness(0.5);
        height: 150px;
        opacity: 0.9;

    }

    div.accordion-item.grand-tot {
        height: 156px;
        position: relative;
    }

    div.accordion-item:first-child.grand-tot:first-child i {
        position: absolute;
        top: 0;
        right: 0;
    }

    #modalDetailPendQristot table tr td button,
    #button.btn-right,
    #modalDetailPendTot table tr td:nth-child(6) button,
    #modalDetailPendTot table tr td:nth-child(7) button,
    #modalDetailPendTot table tr td:nth-child(8) button {
        text-align: right;
        width: 100%;

    }

    #modalDetailPendTot table tfoot td {
        text-align: right;
        font-weight: bold;
    }

    #detailPendalltransTotQris table tr td:nth-child(5),
    #modalDetailPendQristot tfoot td,
    #datatable tfoot td,
    .td-right {
        text-align: right;
    }
</style>
<script>
    $(document).ready(function() {
        // console.log = function() {};
        // $("body").click(function() {
        //     var totalkota = 0;
        //     var totalinv = 0;
        //     $("div.kota").find(".card-body").each(function(index, item) {
        //         console.log(parseFloat($(item).find("a").eq(1).html().replace(/\./g, "").replaceAll("Rp ", "")) + " " + parseFloat($(item).find("a").eq(4).html().replace(/\./g, "").replaceAll("Rp ", "")));
        //         totalinv = totalinv + parseFloat($(item).find("a").eq(3).html().replace(/\./g, "").replaceAll("Rp ", ""));
        //         totalkota = totalkota + parseFloat($(item).find("a").eq(1).html().replace(/\./g, "").replaceAll("Rp ", "")) + parseFloat($(item).find("a").eq(4).html().replace(/\./g, "").replaceAll("Rp ", ""));
        //     });
        //     console.log($('div.kota').find(".card-body").length);
        //     console.log('TOTAL 10 KOTA VALID+INVALID : ' + totalkota);
        //     console.log('TOTAL 10 KOTA VALID : ' + (parseFloat(totalkota) - parseFloat(totalinv)));
        //     console.log('TOTAL INVALID : ' + totalinv);
        // });
    });
</script>