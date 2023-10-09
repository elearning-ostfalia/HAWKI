<?php

// use library for dealing with OpenID connect
$env = parse_ini_file('.env');
$composerpath = $env["COMPOSER_PATH"];

require($composerpath . '/vendor/autoload.php');

use Jumbojett\OpenIDConnectClient;

if (file_exists(".env")){
    $env = parse_ini_file('.env');
}

// Create OpenID connect client

$oidc = new OpenIDConnectClient(
    isset($env) ? $env["OIDC_IDP"] : getenv("OIDC_IDP"),
    isset($env) ? $env["OIDC_CLIENT_ID"] : getenv("OIDC_CLIENT_ID"),
    isset($env) ? $env["OIDC_CLIENT_SECRET"] : getenv("OIDC_CLIENT_SECRET")
);

$scope = $env["OIDC_SCOPE"];
if (!empty($scope)) {
    $oidc->addScope($scope);
}

# Demo is dealing with HTTP rather than HTTPS
$testuser = isset($env) ? $env["TESTUSER"] : getenv("TESTUSER");
if ($testuser) {
    $oidc->setHttpUpgradeInsecureRequests(false);
}

try {
    $oidc->authenticate();
} catch (Exception $ex) {
    echo 'OpenID connect error: ' . PHP_EOL;
    echo $ex->getMessage();
}

$_SESSION['oidcClient'] = serialize($oidc);

// Set session variable username
$firstname = $oidc->requestUserInfo('given_name');
$surname = $oidc->requestUserInfo('family_name');
$initials = substr($firstname, 0, 1) . substr($surname, 0, 1);

$_SESSION['username'] = $initials;


header("Location: interface.php");
exit();

?>

