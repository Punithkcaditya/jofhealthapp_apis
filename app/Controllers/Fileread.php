<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Fileread extends ResourceController
{

    public function __construct()
    {
        parent::__construct();
        $request = \Config\Services::request();
        helper(['form', 'url', 'string']);
        $session = session();
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $check = $this->authUser();
        if ($check) {
            helper("filesystem");
            helper('file');
            $autoload['helper'] = array('file');
            $path = base_url() . "/uploads/file/data.txt";
            $data = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $dataArray = array();
            foreach ($data as $line) {
                list($key, $value) = explode('=', $line, 2); // split each line into key-value pairs
                $dataArray[$key] = $value; // add the key-value pair to the data array
            }
            // $file_contents = readfile($path);
            // $data = ['file_contents' => $file_contents];
            // $data1 = json_encode($data);
            return $this->respond($dataArray, 200);
        }else {
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
