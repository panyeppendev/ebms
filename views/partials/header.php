<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="shortcut icon" href="<?= base_url() ?>/assets/images/favicon.png">
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/daterangepicker/daterangepicker.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>template/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>template/plugins/croppie/croppie.css">
    <style>
        select[readonly] {
            background: #e9ecef;
            pointer-events: none;
            touch-action: none;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed layout-navbar-fixed text-sm">
    <!-- Site wrapper -->
    <div class="wrapper">
        <?php $this->load->view('partials/navbar'); ?>
        <?php $this->load->view('partials/sidebar'); ?>