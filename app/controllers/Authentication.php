<?php
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
            if (isset($data['username'])) {
                if ($login->auth($data)) {
                } else {
                    echo json_encode(array('message' => 'incorrect login credentials'));
                }
            } else {
                echo json_encode(array('message' => 'invalid operation'));
            }
        }
    }
}
