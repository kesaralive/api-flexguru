<?php
class Auth
{
    public function __construct()
    {
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
