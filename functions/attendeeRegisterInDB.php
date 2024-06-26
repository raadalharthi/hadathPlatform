<?php

session_start();
$image = $_SESSION['image'];
$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];
$gender = $_SESSION['gender'];
$birthDate = $_SESSION['birthDate'];
$college = $_SESSION['college'];
$email = $_SESSION['email'];
$pass = $_SESSION['pass'];
$hashedPassword = md5($pass);


if (isset($_POST['enterdOTP'])) {
    $userOtp = $_POST['enterdOTP'];
    $sessionOtp = $_SESSION['otp'] ?? ''; // Safe fallback to ensure there's no undefined index error

    if ($userOtp == $sessionOtp) {

        require_once '../include/connection.php';

        // Check if the email is already registered in the organizer table
        $sql = "SELECT * FROM organizer WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // Email is already registered in the organizer table
            $_SESSION['error_message'] = 'Email is already registered as an organizer.';
            header('Location: ../registrationPage.php'); // Adjust this to your registration form page
            exit();
        } else {
            // Email is not registered in the organizer table, proceed with attendee registration
            // Insert the user data into the database, using the hashed password
            $sql = "INSERT INTO attendee (firstName, lastName, email, password, gender, college, attendeeImage, birthDate) VALUES ('$firstName', '$lastName', '$email', '$hashedPassword', '$gender', '$college', '$image', '$birthDate');";
            $result = mysqli_query($conn, $sql);

            session_destroy();

            session_start();

            if (empty($_SESSION['attendeeID'])) {
                $_SESSION['attendeeID'] = array();
            }

            $sql = "SELECT attendeeID  FROM attendee WHERE email = ? AND password = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $email, $hashedPassword);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $id = $row['attendeeID'];
            array_push($_SESSION['attendeeID'], $id);

            // Redirect to the home/index page after successful registration
            header('Location: ../index.php');
            exit();
        }

        mysqli_stmt_close($stmt);
    } else {
        // OTP is incorrect, set an error message and redirect back to the OTP page
        $_SESSION['error_message'] = 'Invalid OTP. Please try again.';
        header('Location: ../otpVerificationPage.php'); // Adjust this to your OTP form page
        exit();
    }
} else {
    // Redirect back if accessed directly without POST data or to handle other logic
    $_SESSION['error_message'] = 'OTP not provided. Please try again.';
    header('Location: ../otpVerificationPage.php'); // Adjust based on your form page
    exit();
}
