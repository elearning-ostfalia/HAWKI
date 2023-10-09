<?php

session_start();

// use library for dealing with OpenID connect
$env = parse_ini_file('.env');
$composerpath = $env["COMPOSER_PATH"];

require($composerpath . '/vendor/autoload.php');

use Jumbojett\OpenIDConnectClient;

if(isset($_SESSION['oidcClient'])) {
    // Sign out on IDP Server
    $oidc = unserialize($_SESSION['oidcClient']);
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $url = 'https://';
    } else {
        $url = 'http://';
    }

    $url .= $_SERVER['HTTP_HOST'] . '/login.php';
    session_destroy();
    $oidc->signOut($oidc->getIdToken(), $url);
} else {
    session_destroy();
    header("Location: login.php");
}

exit;