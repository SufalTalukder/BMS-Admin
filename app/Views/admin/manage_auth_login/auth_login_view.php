<main class="main">
  <div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
            <div class="d-flex justify-content-center py-4">
              <a href="index.html" class="logo d-flex align-items-center w-auto">
                <img src="<?php echo base_url('assets/img/e-com.jfif'); ?>" alt="e-com.jfif">
                <span class="d-none d-lg-block">Admin Login</span>
              </a>
            </div>
            <div class="card mb-3">
              <div class="card-body">
                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                  <p class="text-center small">Enter your username & password to login</p>
                </div>
                <div class="col-12">
                  <label for="yourUsername" class="form-label">Email Address</label>
                  <div class="input-group has-validation">
                    <input type="email" name="email" class="form-control" id="authEmailAddress" maxlength="50" required>
                  </div>
                </div>
                <div class="col-12" style="padding-top: 10px;">
                  <label for="yourPassword" class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" id="authPassword" maxlength="20" required>
                </div>
                <div class="col-12" style="padding-top: 15px;">
                  <button class="btn btn-primary w-100" type="button">
                    <span id="loginSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                    <span id="loginText" class="submitLoginBtn">Login</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</main>

<script type="text/javascript">
  // Submit
  $(document).on('click', '.submitLoginBtn', function(e) {
    e.preventDefault();

    const email = $('#authEmailAddress').val().trim();
    const password = $('#authPassword').val().trim();

    if (!email || !password) {
      showToast("Email & password required", "error");
      return;
    }

    $('#loginText').text('Logging in...');
    $('#loginSpinner').show();

    $.ajax({
      url: "<?= base_url('submit-login') ?>",
      type: "POST",
      dataType: "json",
      data: {
        email: email,
        password: password
      },
      success: function(response) {
        if (response.status) {
          showToast("Login successful!", "success");
          setTimeout(() => {
            window.location.href = "<?= base_url('admin/activity-service') ?>";
          }, 1000);
        } else {
          showToast(response.message, "error");
        }
      },
      error: function(xhr, status, error) {
        showToast("Server error!", "error");
      },
      complete: function() {
        $('#loginSpinner').hide();
        $('#loginText').text('Login');
      }
    });
  });

  // Toast Notification
  function showToast(message, type = 'info', duration = 2000) {
    let backgroundColor;
    switch (type) {
      case 'success':
        backgroundColor = "linear-gradient(to right, #00b09b, #96c93d)";
        break;
      case 'error':
        backgroundColor = "linear-gradient(to right, #ff0000, #ff6666)";
        break;
      case 'warning':
        backgroundColor = "linear-gradient(to right, #f0ad4e, #f5e79e)";
        break;
      case 'info':
      default:
        backgroundColor = "linear-gradient(to right, #5bc0de, #0dcaf0)";
        break;
    }

    Toastify({
      text: message,
      duration: duration,
      gravity: "top",
      position: "right",
      backgroundColor: backgroundColor,
      stopOnFocus: true
    }).showToast();
  }
</script>