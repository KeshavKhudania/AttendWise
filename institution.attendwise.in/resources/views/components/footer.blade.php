  </div>
  <footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
      <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Made with <i class="fas fa-heart text-danger"></i> by <b>Team  <a href="https://attendwise.in" class="text-decoration-none">AttendWise</a></b></span>
      <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © <?=date("Y");?>. All rights reserved.</span>
    </div>
  </footer>

  </div>
  </div>
  </div>
  <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div
      class="modal-dialog modal-dialog-scrollable modal-md"
      role="document"
    >
      <div class="modal-content">
        <div class="modal-body pb-3">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-2 text-center">
                <span class="text-danger rounded-circle">
                  <i class="fas fa-exclamation-triangle fa-2x"></i>
                </span>
              </div>
              <div class="col-md-10">
                <h4 class="fw-bold text-start">Are you sure?</h4>
                <p>Are you sure you want to delete this? This action can't be undo.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-secondary">
          <div class="text-end">
            <button
            type="button"
            class="btn btn-secondary me-2"
            data-bs-dismiss="modal"
          >
            Cancel
          </button>
          <button type="button" id="confirmDelete" class="btn btn-primary" >Confirm</button>
        </div>
        </div>
      </div>
    </div>
  </div>

  <div class="msc-response-box"></div>
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/dashboard.js"></script>
  <script src="assets/js/msc-text-editor.js"></script>
{{-- <script src="assets/js/chart.js"></script> --}}
<script src="assets/js/script.js"></script>

</body>

</html>

