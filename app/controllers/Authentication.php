<?php
require $_SERVER['DOCUMENT_ROOT'] . '/api-flexguru/app/vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class Authentication extends Controller
{
    public function __construct()
    {
        $auth = new Auth;
        $auth->private();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] = "POST") {
            $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);

            $data = json_decode(file_get_contents("php://input"), true);
            $login = $this->model("Login");

            if (isset($data['username']) && isset($data['password'])) {
                if ($login->auth($data)) {

                    //Take userid
                    $userid = $login->userid($data['username']);

                    //Generate JWT token
                    $iat = time();
                    $exp = time() + 300;

                    $session = $this->model("Session");
                    if (!$session->sessionByusername($data['username'])) {
                        if ($session->create($userid, $iat, $exp)) {
                            $payload = array(
                                'iss' => 'localhost', //issuer
                                // 'aud' => 'localhost', //audience
                                'userId' => $userid,
                                'iat' => $iat, //time JWT was issued
                                'exp' => $exp //time JWT expires
                            );

                            $jwt = JWT::encode($payload, SECRET_KEY, 'HS512');
                            echo json_encode(array(
                                'token' => $jwt,
                                'expires' => $exp
                            ));
                        }
                    } else {
                        echo json_encode(array('message' => 'duplicate logins not allowed'));
                    }
                } else {
                    echo json_encode(array('message' => 'incorrect login credentials'));
                }
            } else {
                echo json_encode(array('message' => 'invalid operation'));
            }
        }
    }
}
