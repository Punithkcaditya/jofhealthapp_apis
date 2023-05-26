<?php

namespace App\Controllers;
use App\Models\Subscriptions;
use CodeIgniter\RESTful\ResourceController;

class Subscription extends ResourceController
{
    public function __construct()
    {
        parent::__construct();
        $this->SubscriptionsModel = new Subscriptions(); 
     }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index($id = null)
    {
        $check = $this->authUser();
        if ($check) {
        $data = [
            'message' => 'success',
            // 'data_subscription' => $this->SubscriptionsModel->where('subscription_id', $id)->first(),
            'data_subscriptiondetails' => $this->SubscriptionsModel
                                    ->select("subscriptions.subscription_id, CONCAT(subscriptions.age_start, '-', subscriptions.age_end) as aggroups, services.service_id, services.servicename as service")
                                    ->join('services',"services.service_id = subscriptions.service_name",'left')
                                    ->where("subscriptions.subscription_id = $id")->first(),
        ];
        if (empty($data['data_subscriptiondetails'])) {
            return $this->fail('No Subscription Found!!');
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
