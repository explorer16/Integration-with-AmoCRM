<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;

class GetToken extends Controller
{
    function get()
    {
        $clientId = config('amocrm.clientId');
        $clientSecret = config('amocrm.clientSecret');
        $redirectUri = config('amocrm.redirectUri');

        $apiClient = new \AmoCRM\Client\AmoCRMApiClient($clientId, $clientSecret, $redirectUri);

        if (isset($_GET['referer'])) {
            $apiClient->setAccountBaseDomain($_GET['referer']);
        }

        if (!isset($_GET['code'])) {
            $state = bin2hex(random_bytes(16));
            $_SESSION['oauth2state'] = $state;
            if (isset($_GET['button'])) {
                echo $apiClient->getOAuthClient()->getOAuthButton(
                    [
                        'title' => 'Установить интеграцию',
                        'compact' => true,
                        'class_name' => 'className',
                        'color' => 'default',
                        'error_callback' => 'handleOauthError',
                        'state' => $state,
                    ]
                );
                die;
            } else {
                $authorizationUrl = $apiClient->getOAuthClient()->getAuthorizeUrl([
                    'state' => $state,
                    'mode' => 'post_message',
                ]);
                header('Location: ' . $authorizationUrl);
                die;
            }
        }
        
        
        $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($_GET['code']);
        if (!$accessToken->hasExpired()) {
            Token::saveToken([
                'accessToken' => $accessToken->getToken(),
                'refreshToken' => $accessToken->getRefreshToken(),
                'expires' => $accessToken->getExpires(),
                'baseDomain' => $apiClient->getAccountBaseDomain(),
            ]);
        }
        
        return redirect()->back();
    }
    
}
