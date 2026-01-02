<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= (!empty($meta_title)) ? $meta_title : PROJECT_NAME; ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <link rel="icon" type="image/svg+xml" href="<?= base_url('assets/img/logo.png') ?>" sizes="any">
  <link href="<?= base_url('assets/img/logo.png') ?>" rel="apple-touch-icon">

  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i<?= '?v=' . v; ?>" rel="stylesheet">

  <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?><?= '?v=' . v; ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.css') ?><?= '?v=' . v; ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/boxicons/css/boxicons.min.css') ?><?= '?v=' . v; ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/quill/quill.snow.css') ?><?= '?v=' . v; ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/quill/quill.bubble.css') ?><?= '?v=' . v; ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/remixicon/remixicon.css') ?><?= '?v=' . v; ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/simple-datatables/style.css') ?><?= '?v=' . v; ?>" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js<?= '?v=' . v; ?>"></script>
  <link href="<?= base_url('assets/css/style.css') ?><?= '?v=' . v; ?>" rel="stylesheet">
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body data-logged="<?= session()->has('admin_logged_true') ? '1' : '0' ?>">

  <div id="offlineBanner">
    ⚠ You're offline. Check your connection .<span id="dots"></span>
  </div>