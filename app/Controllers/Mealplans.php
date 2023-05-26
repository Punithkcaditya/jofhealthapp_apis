<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MealInfo as MealInfo_Model;
use App\Models\Days as Days_Model;
use Config\Database;
class Mealplans extends ResourceController
{


    public function __construct()
    {
        parent::__construct();
        $this->mealinfo_model = new MealInfo_Model();
        $this->days_model = new Days_Model();
        $this->db = Database::connect();
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
            $header_values = getallheaders();
            $userdata = $this->users->select('user_id')->where('login_token', $header_values['token'])->first();
            if (empty($userdata['user_id'])) {
                return $this->fail('No User Found!!');
            }
            $builder = $this->db->table('days_in_week');
            $builder->select('days_in_week.date, days_in_week.day_id, meal_info.meal_id, meal_info.user_id, meal_info.meal_name , meal_info.meal_description, meal_info.meal_time');
            $builder->join('meal_info', 'meal_info.day_id = days_in_week.day_id');
            $builder->where('meal_info.user_id', $userdata['user_id']);
            $dataquery = $builder->get();
            $data['query'] = $dataquery->getResultArray();

            $builderdate = $this->db->table('days_in_week');
            $builderdate->select('date')->select('day_id');
            $builderdate->where('user_id', $userdata['user_id']);
            $dataquerydate = $builderdate->get();
            $data['date'] = $dataquerydate->getResultArray();
            if (empty($data['query'])) {
                $response = [
                    'message' => 'No Meal Plans Found',
                ];
                return $this->respondCreated($response);
            }
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
