<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= @CompanyName ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/dist/css/adminlte.min.css">
  <style>
    .error {
      color: red !important;
    }

    .invalid_user {
      color: red !important;
    }
  </style>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>Buyer</b> Login</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <span id="invalid_user" class="invalid_error" style="display: none;">Invalid Username/ Password</span>
        <form action="<?= @basePath ?>BUYER/Login" id="loginForm" method="post">
          <div class="input-group">
            <input type="email" class="form-control" name="email" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mt-3">
            <input type="password" class="form-control" name="password" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-8">
              <!--<div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>-->
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="button" id="login-submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <!--<div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div>-->
        <!-- /.social-auth-links -->

        <!--<p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p>-->
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?= @basePath ?>admin_assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= @basePath ?>admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= @basePath ?>admin_assets/dist/js/adminlte.min.js"></script>

  <script>
    $(document).on("click", "#login-submit", function(event) {
      event.preventDefault();
      $("#login-submit").attr("disabled", true);

      var url = '<?= @basePath ?>BUYER/Login';
      var formmethod = 'post';
      var formdata = $('form#loginForm').serialize();
      console.log(formdata);
      $.ajax({
        url: url,
        type: formmethod,
        data: formdata,
        success: function(_response) {

          window.location.href = '<?= @basePath ?>BUYER/Dashboard';

        },
        error: function(_response) {

          var data = $.parseJSON(_response.responseText);

          $('.error').remove();
          if (_response.status === 500) {
            // alert("hoooo");
            $("#invalid_user").show();
            $("#invalid_user").css('color', 'red');
          }
          if (_response.status === 422) {
            var errors = $.parseJSON(_response.responseText);
            error = errors.errors;
            $.each(data.errors, function(key, value) {

              if ($('input[name=' + key + ']').length != 0)
                $('input[name=' + key + ']').parent().after('<span class="error ">' + value + '</span>');
              else if ($('select[name=' + key + ']').length != 0)
                $('select[name=' + key + ']').after('<span class="error">' + value + '</span>');
              else
                $('#' + key).after('<span class="error">' + value + '</span>');
            });
          }
        },
        complete: function() {
          // Re-enable the submit button after the request is complete
          $("#login-submit").attr("disabled", false);
        }
      });
    });
  </script>

</body>

</html>