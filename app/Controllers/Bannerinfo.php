<?php

namespace App\Controllers;

use App\Models\Banner;
use CodeIgniter\RESTful\ResourceController;

class Bannerinfo extends ResourceController
{
    protected $format = 'json';
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function __construct()
    {
        parent::__construct();
        $this->banner = new Banner();
    }
    public function index()
    {
        $check = $this->authUser();
        if ($check) {
        $data = [
            'message' => 'success',
            'data_banners' => $this->banner->where('status_ind', 1)->orderBy('display_order', 'ASC')->findAll(),
        ];
        return $this->respond($data, 200);
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
