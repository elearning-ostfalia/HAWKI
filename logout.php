<?php

session_start();

$env = parse_ini_file('.env');
if (trim($env["Authentication"]) == "OIDC") {
    // Do further logout processing for OpenID Connect.
    header("Location: oidc_logout.php");
} else {
    session_destroy();
    header("Location: login.php");
}

exit;
?>