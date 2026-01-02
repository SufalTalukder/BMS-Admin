<main class="main">
  <div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column justify-content-center">
            <div class="d-flex justify-content-center py-0">
            </div>
            <div class="card mb-3">
              <div class="card-body">
                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4"><img style="max-width: 23px;" src="<?= base_url('assets/img/logo.png') ?>" alt="logo">&nbsp;Login to Your Account</h5>
                  <p class="text-center small">Enter your username & password to login</p>
                </div>
                <div class="row g-3 needs-validation">
                  <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                  <div class="col-12">
                    <label for="yourUsername" class="form-label">Username</label>
                    <div class="input-group has-validation">
                      <span class="input-group-text" id="inputGroupPrepend">@</span>
                      <input type="email" name="email" class="form-control" id="authEmailAddress" maxlength="50" required>
                    </div>
                  </div>
                  <div class="col-12">
                    <label for="yourPassword" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="authPassword" minlength="8" maxlength="20" required>
                  </div>
                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                  </div>
                  <div class="col-12" style="padding-bottom: 30px;">
                    <button class="btn btn-primary w-100" type="button" id="btnClick">
                      <span id="loginSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                      <span id="loginText" class="submitLoginBtn">Login</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="credits">
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</main>

<script type="text/javascript">
  // Network got interrupted
  const banner = document.getElementById('offlineBanner');

  function updateStatus() {
    banner.style.display = navigator.onLine ? 'none' : 'block';
  }

  window.addEventListener('online', updateStatus);
  window.addEventListener('offline', updateStatus);
  updateStatus();

  // Submit
  $(document).on('click', '.submitLoginBtn', function(e) {
    e.preventDefault();
    const email = $('#authEmailAddress').val().trim();
    const password = $('#authPassword').val().trim();

    if (!email || !password) {
      showToast("Username & password required.", "error");
      return;
    }

    const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
    const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

    $('#btnClick').addClass('disabled');
    $('#loginSpinner').show();
    $('#loginText').text('');

    $.ajax({
      url: "<?= base_url('submit-login') ?>",
      type: "POST",
      dataType: "json",
      data: {
        email: email,
        password: password,
        [csrfName]: csrfHash
      },
      success: function(response) {
        if (response.status) {
          showToast("Login successfully!", "success");
          setTimeout(() => {
            window.location.href = "<?= base_url('admin/activity-service') ?>";
          }, 100);
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
        $('#btnClick').removeClass('disabled');
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

  window.addEventListener('online', () => {
    window.location.href = document.referrer || "<?= base_url('admin/activity-service') ?>";
  });

  let i = 0;
  setInterval(() => {
    document.getElementById('dots').textContent = '.'.repeat(i++ % 4);
  }, 500);
</script>