<?php

namespace App\Controllers;

use App\Models\SliderModel;
use CodeIgniter\RESTful\ResourceController;

class Slider extends ResourceController
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
        $this->sliders = new SliderModel();
    }

    public function index()
    {
        
            $data = [
                'message' => 'success',
                'data_user' => $this->sliders->where('status_ind', 1)->orderBy('display_order', 'ASC')->findAll(),
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
