<?php


// Flag to track validation status
$validationPassed = true;
$messages = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        include('../include/connection.php');

        $userType = $_POST['userType'];
        $email = $_POST['email'];

        // to prevent from mysqli injection
        $email = stripcslashes($email);

        $email = mysqli_real_escape_string($conn, $email);

        // Start the session
        session_start();

        // First, try to find the email in the attendee table
        $queryAttendee = "SELECT email FROM attendee WHERE email = '$email'";
        $resultAttendee = mysqli_query($conn, $queryAttendee);

        // Check if the email exists in the attendee table
        if (mysqli_num_rows($resultAttendee) > 0) {
            $_SESSION['attendeeEmail'] = $email;
        } else {
            // If not found in attendee table, check the organizer table
            $queryOrganizer = "SELECT email FROM organizer WHERE email = '$email'";
            $resultOrganizer = mysqli_query($conn, $queryOrganizer);

            // Check if the email exists in the organizer table
            if (mysqli_num_rows($resultOrganizer) > 0) {
                $_SESSION['organizerEmail'] = $email;
            } else {

            }
        }

        if (mysqli_num_rows($resultAttendee) == 0 && mysqli_num_rows($resultOrganizer) == 0) {
            $messages[] = "There is no email address like this registered with us.";
            $validationPassed = false;
        } else {
            $emailPattern = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/";

            if (empty($email)) {
                $messages[] = "Email address not provided. Please enter your email address.";
                $validationPassed = false;
            }

            if (!preg_match($emailPattern, $email)) {
                $messages[] = "Please enter a valid email address.";
                $validationPassed = false;
            }
        }

        if (!$validationPassed) {
            // Concatenate all messages into a single alert
            $alertMessage = implode("\\n", $messages);
            echo "<script type='text/javascript'>";
            echo "alert('$alertMessage');";
            echo "window.location.href = '../guestResetPasswordPage.php';";
            echo "</script>";
        } else {

            $_SESSION['email'] = $email;
            $_SESSION['userType'] = $userType;
            // Redirect to OTP verification page on successful validation
            echo "<script type='text/javascript'>";
            echo "window.location.href = 'sendOTP.php';";
            echo "</script>";
        }
    } else {
        // If the form is not submitted, redirect back to the signup page
        header('Location: ../guestResetPasswordPage.php');
        exit;
    }
?>