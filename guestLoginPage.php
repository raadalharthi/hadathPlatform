<!DOCTYPE html>

<html lang="en">

<head>
  <?php
  $title = "Login";
  include_once 'include/metaData.php';
  ?>
</head>

<body>
  <?php
  include_once 'include/navigationBar.php';

  if (empty($_SESSION['adminID']) and empty($_SESSION['userID'])) { ?>

    <div class="container-fluid ps-md-0">
      <div class="row g-0">
        <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
        <div class="col-md-8 col-lg-6">
          <div class="login d-flex align-items-center py-5">
            <div class="container">
              <div class="row">
                <div class="col-md-9 col-lg-8 mx-auto">
                  <h3 class="login-heading mb-4">Welcome back!</h3>

                  <!-- Login Form -->
                  <form name="login" action="authentication.php" onsubmit="return validation()" method="POST">
                    <div class="form-floating mb-3">
                      <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                      <label for="email">Email address</label>
                    </div>
                    <div class="form-floating mb-3">
                      <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                      <label for="password">Password</label>
                    </div>

                    <div class="form-check mb-3">
                      <input class="form-check-input" type="checkbox" value="" id="rememberPasswordCheck">
                      <label class="form-check-label" for="rememberPasswordCheck">
                        Remember password
                      </label>
                    </div>

                    <input type="hidden" id="type" name="type" value="<?php echo $title ?>">

                    <div class="d-grid">
                      <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2" type="submit"
                        value="login" id="btn">Login</button>
                      <div class="text-center">
                        <a class="small" href="guestForgotPasswordPage.php">Forgot password?</a>
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
        var password = document.login.password.value;
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regular expression for email validation

        if (email.length == "" && password.length == "") {
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
          if (password.length == "") {
            alert("Password not provided. Please enter your password.");
            return false;
          }
        }
      }  
    </script>

    <!-- switching to sign up form-->
    <div class="signup">
      <span class="signup">Don't have an account?
        <label><a href="signup.php"> Signup</a></label>
      </span>
    </div>
    <br>

    <div class="signup">
      <span class="signup">Sign In as Admin
        <label><a href="adminLogIn.php"> Admin Dashboard</a></label>
      </span>
    </div>
    </div>

    <!--Signup form-->

    </div>
    </div>

    <?php
  } else {
    ?>

    <div style="text-align: center; margin-top:25%;">
      <h1>You already Logged in as
        <?php if (empty($_SESSION['adminID'])) {
          echo "customer";
        } else {
          echo "Admin";
        } ?>
      </h1>
      <br>
      <form action="signout.php" method="POST"><button class="btn btn-outline-success" type="submit">Sign Out</button>
      </form>
    </div>

    <?php
  }
  include_once 'include/footer.php';
  ?>
</body>

</html>