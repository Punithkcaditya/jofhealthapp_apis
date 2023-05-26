<?php

namespace App\Controllers;

use App\Models\Users;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
class AuthUsers extends ResourceController
{
    use ResponseTrait;
    protected $format = 'json';
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */

    public function __construct()
    {
        $this->users = new Users();
    }
    public function index()
    {
        $data = [
            'message' => 'success',
            'data_user' => $this->users->orderBy('user_id', 'DESC')->findAll(),
        ];

        return $this->respond($data, 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    function new () {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {

        $validation = $this->validate([
            'user_name' => 'required',
            "user_email" => "required|valid_email|is_unique[users.user_email]",
            "user_phone" => "required|is_unique[users.user_phone]",
            "password" => 'required',
        ]);
        if (!$validation) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        $login_token = $this->getToken(16);
        $users = [
            'user_name' => esc($this->request->getVar('user_name')),
            'user_email' => esc($this->request->getVar('user_email')),
            'user_phone' => esc($this->request->getVar('user_phone')),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
            'login_token'=>  $login_token,
        ];
        $response = [
            'message' => 'User Created Successfully',
        ];
        $usersId = $this->users->insert($users);
        if ($usersId) {
            $response = array('success' => 1, 'result' => 'Registered Successfully', 'token' => $login_token);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
            // return $this->respondCreated($users);
        }
        return $this->fail('Sorry!  User creation Failed');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $users = $this->users->find($id);
        if ($users) {
            $validation = $this->validate([
                'user_name' => 'required',
                "user_email" => "required|valid_email|min_length[6]",
                "user_phone" => "required|min_length[10]",
            ]);

            if (!$validation) {
                return $this->failValidationErrors($this->validator->getErrors());
            }
            $response = $this->users->update($id,
            [
                'user_name' => esc($this->request->getVar('user_name')),
                'user_email' => esc($this->request->getVar('user_email')),
                'user_phone' => esc($this->request->getVar('user_phone')),
            ]);
            $users = $this->users->find($id);
            if ($response) {
                return $this->respond($users, 200);
            }
            return $this->fail('Sorry! not updated');
        }
        return $this->failNotFound('Sorry! no users found');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $users = $this->users->find($id);
        if ($users) {
            $response = $this->users->delete($id);
            if ($response) {
                return $this->respond($users);
            }
            return $this->fail('Sorry! not deleted');
        }
        return $this->failNotFound('Sorry! no student found');
    }

    
    public function getToken($length_of_string)
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result),
            0, $length_of_string);
    }
}
