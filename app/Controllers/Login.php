<?php

namespace App\Controllers;

use App\Models\Users;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class Login extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->users = new Users();
        $this->session = \Config\Services::session();
        $this->session->start();
    }
    public function index()
    {
        helper(['form']);
        $rules = [
            'user_phone' => 'required|min_length[6]',
            'password' => 'required|min_length[6]',
        ];
        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $model = new Users();
        $user = $model->where("user_phone", $this->request->getVar('user_phone'))->first();
        if (!$user) {
            return $this->failNotFound('User Not Found, Please Register');
        }

        $verify = password_verify($this->request->getVar('password'), $user['password']);
        if (!$verify) {
            return $this->fail('Wrong Password');
        }

        $key = getenv('TOKEN_SECRET');
        $payload = array(
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "uid" => $user['user_id'],
            "user_phone" => $user['user_phone'],
        );

        $token = JWT::encode($payload, $key, 'HS256');

        return $this->respond($token);
    }

    public function login()
    {
        helper(['form']);
        $rules = [
            'user_phone' => 'required',
            'password' => 'required',
        ];
        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $model = new Users();
        $user = $model->where("user_phone", $this->request->getVar('user_phone'))->first();
        if (!$user) {
            return $this->failNotFound('User Not Found, Please Register');
        }

        $verify = password_verify($this->request->getVar('password'), $user['password']);
        if (!$verify) {
            return $this->fail('Wrong Password');
        }

        $login_token = $this->getToken(16);
        $updated = $model->update($user['user_id'],
            [
                'login_token' => $login_token,
            ]);
        if ($updated) {
            $response = array('success' => 1, 'result' => 'Login Successful', 'token' => $login_token);
        } else {
            $response = array('success' => 0, 'result' => 'Invalid Phone Number or Password !!');
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function getToken($length_of_string)
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result),
            0, $length_of_string);
    }

    public function watsapplogin(){
        helper(['form']);
        $rules = [
            'user_phone' => 'required',
            'user_name' => 'required'
        ];
        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }
        $model = new Users();
        $user = $model->where("user_phone", $this->request->getVar('user_phone'))->first();
        $login_token = $this->getToken(16);
        $password = $this->generateRandomPassword();
        if (!$user) {
            $users = [
                'user_name' => esc($this->request->getVar('user_name')),
               'user_phone' =>  substr(esc($this->request->getVar('user_phone'), 2)),
                'login_token'=>  $login_token,
                'password'=>  password_hash($password, PASSWORD_BCRYPT),
            ];
            $usersId = $model->insert($users);
            if ($usersId) {
                $response = array('user' => 1, 'token' => $login_token);
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        }else{
            $user_phone = esc($this->request->getVar('user_phone'));
            // return $this->respond($login_token);
            // $responseupdate = $model->update($user_phone,
            // [
            //     'user_name' => esc($this->request->getVar('user_name')),
            //     'login_token'=>  $login_token,
            // ]);
 
              $responseupdate = [
                'user_name' => esc($this->request->getVar('user_name')),
                'login_token'=>  $login_token,
            ];

            $data = $model->where('user_phone', $user_phone)->set($responseupdate)->update();
            if ($data) {
            $response = array('user' => 0, 'token' => $login_token);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
            }
        }
    }


    public function generateRandomPassword($length = 7)
    {
        // Define the character set to use
        $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';

        // Get the length of the character set
        $charsetLength = strlen($charset);

        // Initialize an empty password string
        $password = '';

        // Generate a random character for each position in the password
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = rand(0, $charsetLength - 1);
            $password .= $charset[$randomIndex];
        }

        return $password;
    }
}
