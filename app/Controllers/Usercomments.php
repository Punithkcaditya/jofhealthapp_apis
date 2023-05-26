<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Database;
use App\Models\Usercomment;
use CodeIgniter\HTTP\Response;
class Usercomments extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */

     public function __construct()
     {
         parent::__construct();
         $this->usercomment = new Usercomment();
         $this->session = \Config\Services::session();
    
         // Build your query using the Query Builder
     }


     public function index()
     {
         $db = Database::connect();
         $check = $this->authUser();
         if ($check) {
             $header_values = getallheaders();
             $userdata = $this->users->select('user_id')->where('login_token', $header_values['token'])->first();
             if (empty($userdata['user_id'])) {
                 return $this->fail('No User Found!!');
             }
             $validation = $this->validate([
                 'comments' => 'required',
                 "imageuploaded" => "required",
                 "meal_id" => "required",
             ]);
             if (!$validation) {
                 return $this->failValidationErrors($this->validator->getErrors());
             }
                $imageuploaded = esc($this->request->getVar('imageuploaded'));
                $comments = esc($this->request->getVar('comments'));
                $meal_id = esc($this->request->getVar('meal_id'));
                $checkUser = $this->usercomment
                ->where("user_id=", $userdata['user_id'])
                ->where("meal_id=", $meal_id)
                ->countAllResults();
             if ($checkUser > 0) {
                $comments = [
                    'imageuploaded' => $imageuploaded,
                    'comments' => $comments,
                    'meal_id' => $meal_id,
                    'user_id' => $userdata['user_id'],
                ];
                $response = [
                    'message' => 'Comment Added Successfully',
                ];
                $usersId = $this->usercomment->insert($comments);
                // $usersId = $this->usercomment
                // ->where("user_id", $userdata['user_id'])
                // ->set($comments)
                // ->update();
                if ($usersId) {
                    return $this->respondCreated($response);
                }
             } else {
                 $comments = [
                     'imageuploaded' => $imageuploaded,
                     'comments' => $comments,
                     'meal_id' => $meal_id,
                     'user_id' => $userdata['user_id'],
                 ];
                 $response = [
                     'message' => 'Comment Added Successfully',
                 ];
                 $usersId = $this->usercomment->insert($comments);
             }
             if ($usersId) {
                 return $this->respondCreated($response);
             }
             return $this->fail('Sorry! No Comments Added');
         } else {
             return $this->fail('Invalid Token !!');
         }
 
     }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $check = $this->authUser();
        if ($check) {
            $data = [
                'message' => 'success',
                'data_posts' => $this->usercomment->select('user_id')->select('imageuploaded')->select('comments')->where('meal_id', $id)->findAll(),
            ];
             if(empty( $data['data_posts'])){
                  return $this->respond('Service unavailable', 503); 
            }
            return $this->respond($data, 200);
        }else {
            return $this->fail('Invalid Token !!');
        }
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
