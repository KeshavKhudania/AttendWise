<x-structure />
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0" id="login-page-container">
        <div class="row w-100 mx-0">
          <div class="col-lg-5 mx-auto">
            <div class="auth-form-light login-form-container text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="assets/images/logo.png" alt="logo">
              </div>
              <h2 class="fw-bolder">Welcome to AttendWise</h2>
              <h6 class="fw-normal pt-2">Please enter your credentials to sign in.</h6>
              <form class="pt-4" action="{{route("login_check")}}" method="POST" id="login-form">
                @csrf
                <div class="form-group">
                  <label for="emailAddress" class="form-label">Email Address</label>
                  <input type="email" class="form-control form-control-lg" name="email" id="emailAddress" placeholder="Email Address">
                </div>
                <div class="form-group">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control form-control-lg" name="password" id="password" placeholder="Password">
                </div>
                <div class="mt-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit">SIGN IN</button>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input" name="keep_signed_in">
                      Keep me signed in
                    </label>
                  </div>
                  <a href="{{route("forgotPasswordView")}}" class="auth-link text-black">Forgot password?</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <script>
    $(document).ready(function () {
        $("#login-form").submit(function(e){
          e.preventDefault()
          $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data: $(this).serializeArray(),
            success: function (response) {
              response = JSON.parse(response);
              mscToast({
                "msg":response.msg,
                "color":response.color,
                "icon":response.icon,
              });
              setTimeout(() => {
                if (response.redirect) {
                  window.location.href = response.redirect;
                }
              }, 1500);
            },
            error: function(err){
              const res = JSON.parse(err.responseJSON.message);
              mscToast({
                msg:res.msg,
                color:res.color,
                icon:res.icon,
              });
            }
          });
        })
    });
  </script>
<x-footer />