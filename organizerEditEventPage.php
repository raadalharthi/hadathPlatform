<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Edit Event";
    include_once 'include/metaData.php';

    if (empty($_SESSION['organizerID'])) {
        require_once 'include\accessDenied.php';
    } else { ?>
    </head>

    <body>
        <?php
        include_once 'include/navigationBar.php';
        ?>

        <h1>organizerEditEventPage.php</h1>

        <?php

        include_once 'include/footer.php';
        ?>
    </body>

    <?php
    }
    ?>

</html>