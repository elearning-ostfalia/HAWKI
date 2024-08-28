<?php

// use library for dealing with OpenID connect
require PRIVATE_PATH . '/vendor/autoload.php';

use Jumbojett\OpenIDConnectClient;

if (file_exists(ENV_FILE_PATH)){
    $env = parse_ini_file(ENV_FILE_PATH);
}

// Create OpenID connect client

$oidc = new OpenIDConnectClient(
    isset($env) ? $env["OIDC_IDP"] : getenv("OIDC_IDP"),
    isset($env) ? $env["OIDC_CLIENT_ID"] : getenv("OIDC_CLIENT_ID"),
    isset($env) ? $env["OIDC_CLIENT_SECRET"] : getenv("OIDC_CLIENT_SECRET")
);

# Demo is dealing with HTTP rather than HTTPS
$testuser = isset($env) ? $env["TESTUSER"] : getenv("TESTUSER");
if ($testuser) {
    $oidc->setHttpUpgradeInsecureRequests(false);
}

$scope = $env["OIDC_SCOPE"];
if (!empty($scope)) {
    $scope_array = array_map('trim', explode(',', $scope));
    $oidc->addScope($scope_array);
} else {
    $oidc->addScope(['profile','email']);
}


// try {
    $oidc->authenticate();
/*} catch (Exception $ex) {
    echo 'OpenID connect error: ' . PHP_EOL;
    echo $ex->getMessage();
    exit();
}*/

// Set session variable username
$firstname = $oidc->requestUserInfo('given_name');
$surname = $oidc->requestUserInfo('family_name');
$initials = substr($firstname, 0, 1) . substr($surname, 0, 1);
#
$_SESSION['initials'] = $initials;

$_SESSION['username'] = $initials; // $oidc->requestUserInfo('email');

header("Location: interface");
exit();

?>