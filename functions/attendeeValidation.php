<?php

// Flag to track validation status
$validationPassed = true;
$messages = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include ('../include/connection.php');

    $userType = $_POST['userType'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $birthDate = $_POST['birthDate'];
    $college = $_POST['college'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if image is uploaded and there is no error
    $imageProvided = isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK;

    // Initialize variables related to file upload
    $target_dir = '../assets/uploadedImages/';
    $file_name = '';
    $target_file = '';

    if ($imageProvided) {
        $temp = $_FILES['image']['tmp_name'];
        $uniq = time() . rand(1000, 9999);
        $info = pathinfo($_FILES['image']['name']);
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $fileType = strtolower($info['extension']);

        // to prevent from mysqli injection
        $firstName = stripcslashes($firstName);
        $lastName = stripcslashes($lastName);
        $email = stripcslashes($email);
        $pass = stripcslashes($pass);
        $confirmPassword = stripcslashes($confirmPassword);

        $firstName = mysqli_real_escape_string($conn, $firstName);
        $lastName = mysqli_real_escape_string($conn, $lastName);
        $email = mysqli_real_escape_string($conn, $email);
        $pass = mysqli_real_escape_string($conn, $pass);
        $confirmPassword = mysqli_real_escape_string($conn, $confirmPassword);

        // Query the database to find if the email is already registered in attendee or organizer table
        $query = "SELECT email FROM attendee WHERE email = '$email' UNION SELECT email FROM organizer WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $messages[] = "This email is already registered. Please use a different email.";
            $validationPassed = false;
        }

        $namePattern = "/^[A-Za-z]+$/";
        $emailPattern = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/";
        $passwordPattern = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z\d]).{8,}$/";

        //    Allow certain files formats
        if ($fileType !== "jpg" && $fileType !== "png" && $fileType !== "jpeg") {
            $messages[] = 'Sorry, only JPG, PNG, and JPEG formats are allowed.';
            $validationPassed = false;
        } else {
            $file_name = "file_" . $uniq . "." . $info['extension'];
            $target_file = $target_dir . $file_name;
            move_uploaded_file($temp, $target_file);
        }
    } else {
        $messages[] = 'Image file is not provided or there was an error uploading.';
        $validationPassed = false;
    }

    if (empty($firstName)) {
        $messages[] = "First name not provided. Please enter your first name.";
        $validationPassed = false;
    }

    if (!preg_match($namePattern, $firstName)) {
        $messages[] = "First name can only contain English letters. Please enter a valid first name.";
        $validationPassed = false;
    }

    if (empty($lastName)) {
        $messages[] = "Last name not provided. Please enter your last name.";
        $validationPassed = false;
    }

    if (!preg_match($namePattern, $lastName)) {
        $messages[] = "Last name can only contain English letters. Please enter a valid last name.";
        $validationPassed = false;
    }

    if ($gender === "Select your gender" || $gender === "") {
        $messages[] = "Please select your gender.";
        $validationPassed = false;
    }

    if (empty($birthDate)) {
        $messages[] = "Birth date not provided. Please enter your birth date.";
        $validationPassed = false;
    } else {
        // Convert birth date to PHP DateTime object
        $birthDateObject = DateTime::createFromFormat('Y-m-d', $birthDate);
        $today = new DateTime();

        // Calculate age
        $diff = $today->diff($birthDateObject);
        $age = $diff->y;

        // Check if the user is older than 10 years
        if ($age < 10) {
            $messages[] = "You must be older than 10 years to sign up.";
            $validationPassed = false;
        }
    }

    if ($college === "Select College" || $college === "") {
        $messages[] = "Please select a college.";
        $validationPassed = false;
    }

    if (empty($email)) {
        $messages[] = "Email address not provided. Please enter your email address.";
        $validationPassed = false;
    }

    if (!preg_match($emailPattern, $email)) {
        $messages[] = "Please enter a valid email address.";
        $validationPassed = false;
    }

    if (empty($pass)) {
        $messages[] = "Password not provided. Please enter your password.";
        $validationPassed = false;
    }

    if (!preg_match($passwordPattern, $pass)) {
        $messages[] = "Password must be at least 8 characters long and include uppercase and lowercase letters, a number, and a special character.";
        $validationPassed = false;
    }

    if (empty($confirmPassword)) {
        $messages[] = "Please re-enter your password.";
        $validationPassed = false;
    }

    if ($pass !== $confirmPassword) {
        $messages[] = "Passwords do not match.";
        $validationPassed = false;
    }

    if (!$validationPassed) {
        // Concatenate all messages into a single alert
        $alertMessage = implode("\\n", $messages);
        echo "<script type='text/javascript'>";
        echo "alert('$alertMessage');";
        echo "window.location.href = '../attendeeSignupPage.php';";
        echo "</script>";
    } else {

        session_start();
        $_SESSION['userType'] = $userType;
        $_SESSION['image'] = $target_file;
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['gender'] = $gender;
        $_SESSION['birthDate'] = $birthDate;
        $_SESSION['college'] = $college;
        $_SESSION['email'] = $email;
        $_SESSION['pass'] = $pass;

        // Redirect to OTP verification page on successful validation
        echo "<script type='text/javascript'>";
        echo "window.location.href = 'sendOTP.php';";
        echo "</script>";
    }
} else {
    // If the form is not submitted, redirect back to the signup page
    header('Location: ../attendeeSignupPage.php');
    exit;
}
?>