<!DOCTYPE html>

<html lang="en">

<head>
  <?php
  $title = "Login";
  include_once 'include/metaData.php';

  if (!empty($_SESSION['organizerID']) || !empty($_SESSION['attendeeID'])) {
    require_once 'include\accessDenied.php';
} else { 
  ?>
</head>

<body>
  <?php
  include_once 'include/navigationBar.php';
 ?>

    <div class="container-fluid ps-md-0">
      <div class="row g-0">
        <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"
          style="background-image: url(./assets/guestLoginPageBackground.jpg);"></div>
        <div class="col-md-8 col-lg-6">
          <div class="login d-flex align-items-center py-5">
            <div class="container">
              <div class="row">
                <div class="col-md-9 col-lg-8 mx-auto">
                  <h3 class="login-heading mb-4">Welcome back!</h3>

                  <!-- Login Form -->
                  <form name="login" action="functions/authentication.php" onsubmit="return validation()" method="POST">
                    <div class="form-floating mb-3">
                      <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="<?php echo isset($_COOKIE['email']) ? $_COOKIE['email'] : ''; ?>">
                      <label for="email">Email address<span style="color: red;">
                          *</span></label>
                    </div>
                    <div class="form-floating mb-3">
                      <input type="password" class="form-control" id="pass" name="pass" placeholder="Password" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; ?>">
                      <label for="pass">Password<span style="color: red;">
                          *</span></label>
                    </div>

                    <div class="form-check mb-3">
                      <input class="form-check-input" type="checkbox" value="" id="rememberMeCheck" onclick="rememberMe()" <?php echo isset($_COOKIE['email']) && isset($_COOKIE['password']) ? 'checked' : ''; ?>>
                      <label class="form-check-label" for="rememberMeCheck">
                        Remember me
                      </label>
                    </div>

                    <input type="hidden" id="userType" name="userType" value="<?php echo $title ?>">

                    <div class="d-grid">
                      <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2" type="submit"
                        value="login" id="btn">Login</button>
                      <div class="text-center">
                        <p style="display: inline;">Want to attend an event?</p>
                        <a class="small" href="attendeeSignupPage.php">Sign up as attendee now</a>
                        <br>
                        <p style="display: inline;">Want to organize your events?</p>
                        <a class="small" href="organizerSignupPage.php">Sign up as organizer now</a>
                        <br>
                        <p style="display: inline;">Forgot your password?</p>
                        <a class="small" href="guestResetPasswordPage.php">Reset your password</a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      function validation() {
        var email = document.login.email.value;
        var pass = document.login.pass.value;
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regular expression for email validation

        if (email.length == "" && pass.length == "") {
          alert("Email and password not provided. Please enter your email address and password.");
          return false;
        } else {
          if (email.length == "") {
            alert("Email address not provided. Please enter your email address.");
            return false;
          } else if (!emailPattern.test(email)) { // Check if email matches the pattern
            alert("Please enter a valid email address.");
            return false;
          }
          if (pass.length == "") {
            alert("Password not provided. Please enter your password.");
            return false;
          }
        }
      }

      function rememberMe() {
        var email = document.getElementById("email").value;
        var pass = document.getElementById("pass").value;
        var rememberMeCheckbox = document.getElementById("rememberMeCheck");

        if (rememberMeCheckbox.checked) {
          // Set cookies to store email and password for 3 days
          var expirationDate = new Date();
          expirationDate.setDate(expirationDate.getDate() + 3);
          document.cookie = "email=" + email + "; expires=" + expirationDate.toUTCString() + "; path=/";
          document.cookie = "password=" + pass + "; expires=" + expirationDate.toUTCString() + "; path=/";
        } else {
          // Remove cookies
          document.cookie = "email=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/";
          document.cookie = "password=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/";
        }
      }

      window.onload = function() {
        var email = document.getElementById("email");
        var pass = document.getElementById("pass");
        var rememberMeCheckbox = document.getElementById("rememberMeCheck");

        if (document.cookie.indexOf("email=") !== -1 && document.cookie.indexOf("password=") !== -1) {
          rememberMeCheckbox.checked = true;
          email.value = getCookie("email");
          pass.value = getCookie("password");
        }
      }

      function getCookie(name) {
        var cookieArr = document.cookie.split(";");
        for (var i = 0; i < cookieArr.length; i++) {
          var cookiePair = cookieArr[i].split("=");
          if (name == cookiePair[0].trim()) {
            return decodeURIComponent(cookiePair[1]);
          }
        }
        return null;
      }
    </script>

    <?php
  include_once 'include/footer.php';
}
  ?>
</body>

</html>
