<?php

namespace App\Controllers;
use App\Models\ServiceModel;
use App\Models\SubscriptionPlanDesc;
use App\Models\Subscriptions;
use App\Models\Banner;
use CodeIgniter\RESTful\ResourceController;

class Services extends ResourceController
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
        $this->servicemodel = new ServiceModel();
        $this->banner = new Banner();
        $this->subscriptionplandesc_model = new SubscriptionPlanDesc();
        $this->SubscriptionsModel = new Subscriptions();    
     }

    public function index()
    {
        $check = $this->authUser();
        if ($check) {
        $data = [
            'message' => 'success',
            'data_services' => $this->servicemodel->select('service_id')->select('Service_thumbnail')->select('servicename')->where('status_ind', 1)->orderBy('service_id', 'ASC')->findAll(),
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

     public function showservice($id = null)
     {
         $check = $this->authUser();
         if ($check) {
         $data = [
             'message' => 'success',
             'data_service' => $this->servicemodel->select('service_heading1')->select('service_heading2')->select('servicename')->select('servicesdescription')->select('services')->select('servicescondition')->select('button_heading')->where('service_id', $id)->where('status_ind', 1)->first(),
         ];
         if (empty($data['data_service'])) {
             return $this->fail('No Services Found!!');
         }
         return $this->respond($data, 200);
     }else {
         return $this->fail('Invalid Token !!');
     }
     }

     public function bookconsultation($id = null)
     {
         $check = $this->authUser();
         if ($check) {
         $data = [
             'message' => 'success',
             'data_servicesubscription' => $this->servicemodel->select('service_plan_heading')->select('subscriptionplane_img')->select('subscription_button_heading')->where('service_id', $id)->where('status_ind', 1)->first(),
             'data_servicesubscriptionparagraphs' => $this->subscriptionplandesc_model->select('service_description')->where('service_id', $id)->findAll(),
             'data_subscriptiondetails' => $this->SubscriptionsModel
            ->select("subscriptions.subscription_id, CONCAT(subscriptions.age_start, '-', subscriptions.age_end) as aggroups,  services.servicename as service, services.service_id")
            ->join('services',"services.service_id = subscriptions.service_name",'left')
            ->findAll(),
             
         ];
         if (empty($data['data_servicesubscription'] && $data['data_servicesubscriptionparagraphs'])) {
             return $this->fail('No Services Found!!');
         }
         return $this->respond($data, 200);
     }else {
         return $this->fail('Invalid Token !!');
     }
     }

    public function show($id = null)
    {
    
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
