<?php

session_start();

$env = parse_ini_file('.env');
if (trim($env["Authentication"]) == "OIC") {
    // Do further logout processing for OpenID Connect.
    header("Location: oic_logout.php");
} else {
    session_destroy();
    header("Location: login.php");
}

exit;
?>