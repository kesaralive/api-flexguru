<?php
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;



class Auth
{
    private $key = 'privatekey';

    public function __construct()
    {
    }

    public function auth()
    {
        $iat = time();
        $exp = time() + 60 * 60;
        $payload = array(
            'iss' => 'http://localhost/api-flexguru', //issuer
            'aud' => 'http://localhost/flexguru', //audience
            'iat' => $iat, //time JWT was issued
            'exp' => $exp //time JWT expires
        );
        $jwt = JWT::encode($payload, $this->key, 'HS512');
    }

    //Basic auth for private area
    public function private()
    {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header("WWW-Authenticate: Basic realm=\"Private Area\"");
            header("HTTP/1.0 401 Unauthorized");
            echo json_encode(
                array('message' => 'Sorry, you need proper credentials')
            );
            exit;
        } else {
            if (($_SERVER['PHP_AUTH_USER'] == 'kesara' && $_SERVER['PHP_AUTH_PW'] == '12345')) {
            } else {
                header("WWW-Authenticate: Basic realm=\"Private Area\"");
                header("HTTP/1.0 401 Unauthorized");
                echo json_encode(array('message' => 'You have no permission to do this!'));
                exit;
            }
        }
    }

    //Public auth
    public function public()
    {
        //Future improvements
    }
}
