<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Add Event";
    include_once 'include/metaData.php';

    if (empty($_SESSION['organizerID'])) {
        require_once 'include\accessDenied.php';
    } else { ?>
    </head>

    <body>
        <?php
        include_once 'include/navigationBar.php';
        ?>

        <h1>organizerAddEventPage.php</h1>

        <?php

        include_once 'include/footer.php';
        ?>
    </body>

    <?php
    }
    ?>

</html>