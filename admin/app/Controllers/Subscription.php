<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');
use App\Controllers\BaseController;
use App\Models\Services as Services_Model;
use App\Models\Subscriptions as Subscriptions_Model;

class Subscription extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->subscriptions_model = new Subscriptions_Model();
        $this->services_model = new Services_Model();
        $request = \Config\Services::request();
        helper(['form', 'url', 'string']);
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        } else {
            $role_id = $pot["role_id"];
        }
        $menutext = $this->request->uri->getSegment(2);
        if (isset($_SESSION['sidebar_menuitems'])) {
            foreach ($_SESSION['sidebar_menuitems'] as $main_menus):
                if (strtolower($main_menus->menuitem_link) == strtolower($menutext)) {
                    $permissions = $this->admin_roles_accesses_model->get_permisions($role_id, $main_menus->menuitem_id);
                    $this->permission = array($permissions->add_permission, $permissions->edit_permission, $permissions->delete_permission);
                } else {
                    if (!empty($main_menus->submenus)):
                        foreach ($main_menus->submenus as $submenus):
                            if (strtolower($submenus->menuitem_link) == strtolower($menutext)) {
                                $permissions = $this->admin_roles_accesses_model->get_permisions($role_id, $submenus->menuitem_id);
                                $this->permission = array($permissions->add_permission, $permissions->edit_permission, $permissions->delete_permission);
                            }
                        endforeach;
                    endif;
                }
            endforeach;
        }
    }

    public function index()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data['session'] = $session;
        $data['title'] = 'Subscription Age Details';
        $data['subscriptions'] = $this
            ->subscriptions_model->
        orderBy('subscription_id', 'DESC')->findAll();
        $data['page_heading'] = 'Add New Subscription Age Details';
        $data['request'] = $this->request;
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);
        $data['services'] = $this->services_model->select(['servicename'])->select(['service_id'])->findAll();
        if ($this->permission[0] > 0) {
            $data["link"] = "addnewsubscriptionagelist";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["edit_subscriptionagelist"] = "edit_subscriptionagelist";
        } else {
            $data["edit_subscriptionagelist"] = "#";
        }
        if ($this->permission[2] > 0) {
            $data["delete_subscriptionagelist"] = "delete_subscriptionagelist";
        } else {
            $data["delete_subscriptionagelist"] = "#";
        }
        $data["view"] = "subscription/subscriptionslist";
        return view('templates/default', $data);
    }

    public function addNewSubscription()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data['services'] = $this->services_model->select(['servicename'])->select(['service_id'])->findAll();
        $data['session'] = $session;
        $data['title1'] = 'Add Subscription Age Details';
        $data['title2'] = 'Subscription Age Details';
        $data['pade_title2'] = 'Enter Subscription Age From';
        $data['pade_title2'] = 'Enter Subscription Age To';
        $data['pade_title3'] = 'Add Conditions';
        $data['pade_title3'] = 'Choose Service';
        $data['i'] = 1;
        $data['request'] = $this->request;
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);
        $data["view"] = "subscription/addnewsubscriptionslists";
        return view('templates/default', $data);
    }

    public function savenewsubscription()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        } else {
            $user_id = $pot['user_id'];
            $role_id = $pot['role_id'];
        }
        // helper(['form', 'url']);
        if ($this
            ->request
            ->getMethod() == 'post') {
            extract($this
                    ->request
                    ->getPost());
        }
        if($age_start > $age_end){
            $session->setFlashdata('error', 'Age From Field cannot have value larger than Age To Value');
            return redirect()->to('subscription_age_group');   
        }
        $input = $this->validate(['age_start' => 'required', 'age_end' => 'required', 'service_choosen' => 'required']);
        if (!empty($input)) {
            if (!empty($subscrption_id_hidd)) {
                $data = [];
                $data = ['age_start' => $age_start, 'age_end' => $age_end, 'service_choosen' => $service_choosen, 'created_at' => date('Y-m-d')];
                $update = $this
                    ->subscriptions_model
                    ->where('subscription_id', $subscrption_id_hidd)->set($data)->update();

                if ($update) {
                    $session->setFlashdata('success', 'Saved Successfully');
                } else {
                    $session->setFlashdata('error', 'Failed to Saved');
                }
                return redirect()->to('subscription_age_group');

            } else {
                $groups = array();
                for ($i = 0; $i < count($age_start); $i++) {
                    $key = $i;
                    $groups[$key] = array($age_start[$i], $age_end[$i], $service_choosen[$i]);
                }

                foreach ($groups as $key => $groupsall) {
                    $data = [
                        'age_start' => $groupsall[0],
                        'age_end' => $groupsall[1],
                        'service_name' => $groupsall[2],
                    ];
                    $saved = $this->subscriptions_model->save($data);

                }
                $session->setFlashdata('success', 'Saved Successfully');
            }
        } else {
            $session->setFlashdata('error', 'Fill All Fields');
        }
        return redirect()->to('subscription_age_group');
    }

    public function appendplandetails()
    {
        if ($this
            ->request
            ->getMethod() == 'post') {
            extract($this
                    ->request
                    ->getPost());
            $msg['services'] = $this->services_model->select(['servicename'])->select(['service_id'])->findAll();
            $msg['status'] = $counts;
            // echo $content;
            echo json_encode($msg);
            exit();
        }

    }

    // edit service

    public function edit_subscriptionagelist($id = '')
    {

        if ($id == null) {
            return redirect()->to("Admindashboard");
        }
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data['session'] = $session;
        $data['title1'] = 'Edit Subscription Age Details';
        $data['title2'] = 'Subscription Age Details';
        $data['pade_title2'] = 'Edit Subscription Age From';
        $data['pade_title2'] = 'Edit Subscription Age To';
        $data['pade_title3'] = 'Edit Conditions';
        $data['pade_title3'] = 'Choose Service';
        $data['i'] = 1;
        $data['services'] = $this->services_model->select(['servicename'])->select(['service_id'])->findAll();
        $data['query'] = $this->subscriptions_model->where('subscription_id', $id)->first();
        $data['request'] = $this->request;
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);
        $data["view"] = "subscription/addnewsubscriptionslists";
        return view('templates/default', $data);
    }

// service delete

    public function delete_subscriptionagelist($id = '')
    {
        if ($id == null) {
            return redirect()->to("Admindashboard");
        }
        $this->session = session();
        $session = session();
        $pot = json_decode(json_encode($session->get('userdata')), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $delete = $this
            ->subscriptions_model
            ->where('subscription_id', $id)->delete();
        if ($delete) {
            $session->setFlashdata('success', 'Subscription has been deleted successfully.');
        } else {
            $session->setFlashdata('error', 'Subscription failed due to unknown ID.');
        }
        $data['request'] = $this->request;
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);

        return redirect()->to("subscription_age_group");
    }
}
