<?php

namespace App\Controllers;
use App\Models\Users;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Me extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */

    use ResponseTrait;
    public function __construct()
    {
        $this->users = new Users();
        $this->session = \Config\Services::session();
        $this->session->start();
    }
    public function index()
    {
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if (!$header) {
            return $this->failUnauthorized('Token Required');
        }

        $token = explode(' ', $header)[1];
        try {
          // $decoded = (array) JWT::decode($token, base64_decode($key), array('HS256'));
            $decoded =   JWT::decode($token, new Key($key, 'HS256'));
            $response = [
                'user_id' => $decoded->uid,
                'user_phone' => $decoded->user_phone,
            ];
            return $this->respond($response);
        } catch (\Throwable$th) {
            return $this->fail('Invalid Token');
        }
    }

 

    public function checklogin() {
        $res = $this->authUser();
        if($res){
			$response = array('success' => 1, 'result'=>'success');
        }else{
			$response = array('success' => 0, 'result'=>'Invalid Token');
        }
        header('Content-Type: application/json');
		echo json_encode($response);
		exit;
    }

    public function authUser(){
        $header_values = getallheaders();
        $data = array();
        $response = array();
       // return $this->respond($header_values['Auth-Token']);
        if((isset($header_values['Auth-Token']) && !empty($header_values['Auth-Token']))){
            $token = $header_values['Auth-Token'];
            if(!empty($token)){
                $user_data = $this->users->where('login_token', $token)->first();
                if(!empty($user_data)){
                    $this->session->set($user_data);
                    return $this->respond($user_data);
                }else{
                    return false;
                }
            }
        }
    }
}
