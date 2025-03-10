<?php

namespace App\Services\Auth;

use Jumbojett\OpenIDConnectClient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

use Illuminate\Support\Facades\Log;

class OidcService
{
    protected $oidc;

    public function __construct()
    {
        // Retrieve configuration settings
        $idp = config('open_id_connect.oidc_idp');
        $clientId = config('open_id_connect.oidc_client_id');
        $clientSecret = config('open_id_connect.oidc_client_secret');

        // Validate configuration settings
        if (empty($idp) || empty($clientId) || empty($clientSecret)) {
            throw new \InvalidArgumentException('OIDC configuration variables are not set properly.');
        }

        // Initialize the OpenID Connect client
        $this->oidc = new OpenIDConnectClient($idp, $clientId, $clientSecret);

        // Add scopes as an array
        $scopes = config('open_id_connect.oidc_scopes');
        $this->oidc->addScope($scopes);
        $allowInsecure = (bool) env('APP_ALLOW_INSECURE', false);
        if ($allowInsecure) {
            $this->oidc->setHttpUpgradeInsecureRequests(false);
        }
    }

    public function authenticate()
    {
        try {
            // Attempt to authenticate the user
            $this->oidc->authenticate();

            // Retrieve attribute mapping from configuration
            $firstNameAttr = config('open_id_connect.attribute_map.firstname');
            $lastNameAttr = config('open_id_connect.attribute_map.lastname');
            $emailAttr = config('open_id_connect.attribute_map.email');
            $employeetypeAttr = config('open_id_connect.attribute_map.employeetype');
            $usernameAttr = config('open_id_connect.attribute_map.username');

            // Retrieve user information
            $email = $this->oidc->requestUserInfo($emailAttr);
            $employeetype = $this->oidc->requestUserInfo($employeetypeAttr);
            if (empty(trim($employeetype))) {
                // employeetype must not be empty. But which values are expected?
                $employeetype = 'TODO map employeetype';
            }

            $firstname = $this->oidc->requestUserInfo($firstNameAttr);
            $surname = $this->oidc->requestUserInfo($lastNameAttr);
            $username =$this->oidc->requestUserInfo($usernameAttr);

            $name = trim("$firstname $surname");

            // Return UserInfo array to authentication controller
            // if (!empty($_SERVER['REMOTE_USER'])) {
            $result=
                 [
                    'username' => $username, // $_SERVER['REMOTE_USER'],
                    'name' => $name,
                    'email' => $email,
                    'employeetype' => $employeetype,
                ];
/*            } else {
                throw new \RuntimeException('REMOTE_USER is not set.');
            }*/
            return $result;
        } catch (\Exception $e) {
            // Handle errors, such as authentication failures
            // Log::info('OIDC authenticate error: ' . $e->getMessage());
            return response()->json(['error' => 'Authentication failed: ' . $e->getMessage()], 401);
        }
    }
}
