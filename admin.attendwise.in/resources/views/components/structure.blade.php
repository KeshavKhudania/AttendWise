
<!DOCTYPE html>
<html lang="en">

<head>
  <base href="{{env("APP_URL")}}">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="auth_token" content="{{csrf_token()}}">
  <meta name="csrf" content="{{csrf_token()}}">
  <title>{{$hospital['name']}}</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="assets/css/animate.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="assets/js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="assets/css/msc-text-editor.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="assets/images/favicon.png" />
  <script src="assets/js/jquery.3.6.0.min.js"></script>
  <script src="assets/js/chart.js"></script>
  <script src="assets/js/main.js"></script>
  <script src="assets/js/msc-select-search.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body class="">
<!-- GlobalDialog modal (drop into layout once) -->
<div id="globalDialogContainer">
  <!-- Confirm / Alert modal -->
  <div class="modal fade" id="globalDialogModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-sm">
        <div class="modal-header">
          <h5 class="modal-title" id="globalDialogTitle">Confirm</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="globalDialogBody">
          <!-- message goes here -->
        </div>
        <div class="modal-footer" id="globalDialogFooter">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="globalDialogCancel">Cancel</button>
          <button type="button" class="btn btn-primary" id="globalDialogOk">OK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Prompt: shows input if options.prompt = true -->
  <!-- Toast container -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 12000;">
    <div id="globalDialogToast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true" style="min-width:220px; display:none;">
      <div class="d-flex">
        <div class="toast-body" id="globalDialogToastBody">Saved</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
</div>
