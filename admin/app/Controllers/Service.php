<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');
use App\Controllers\BaseController;
use App\Models\Services as Services_Model;
use App\Models\SubscriptionPlanDesc as SubscriptionPlanDesc_Model;

class Service extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->services_model = new Services_Model();
        $this->subscriptionplandesc_model = new SubscriptionPlanDesc_Model();
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
        $data['title'] = 'Service Details';
        $data['services'] = $this
            ->services_model->
        orderBy('service_id', 'DESC')->findAll();
        $data['page_heading'] = 'Add New Service';
        $data['request'] = $this->request;
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);
        if ($this->permission[0] > 0) {
            $data["link"] = "addnewservicelist";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["edit_service"] = "edit_service";
        } else {
            $data["edit_service"] = "#";
        }
        if ($this->permission[2] > 0) {
            $data["delete_service"] = "delete_service";
        } else {
            $data["delete_service"] = "#";
        }
        $data["view"] = "services/servicelist";
        return view('templates/default', $data);
    }

// add new services

    public function addNewServices()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data['session'] = $session;
        $data['title'] = 'Add Service Details';
        $data['pade_title2'] = 'Select Company Name';
        $data['pade_title1'] = 'Add Services Description';
        $data['pade_title2'] = 'Add Conditions';
        $data['pade_title6'] = 'Add Services';
        $data['pade_title3'] = 'Add Service  Name';
        $data['pade_title4'] = 'Select Service Category';
        $data['pade_title5'] = 'Add Service Cost For Type One';
        $data['pade_title7'] = 'Add Service Heading 1';
        $data['pade_title8'] = 'Add Service Heading 2';
        $data['pade_title12'] = 'Add Service Button Text';
        $data['pade_title10'] = 'Add Subscription Plan Image';
        $data['pade_title11'] = 'Add Subscription Plan Heading';
        $data['pade_title23'] = 'Add Subscription Button Text';
         $data['pade_title16'] = 'Add Subscription Plan Description';
        $data['page_heading'] = 'Add Service Thumbnail';
        $data['i'] = 1;
        $data['z'] = 1;
        $data['request'] = $this->request;
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);
        $data["view"] = "services/addnewservices";
        return view('templates/default', $data);
    }

// save sewrvice

    public function savenewserviceinfo()
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

        $input = $this->validate(['service_name' => 'required|min_length[3]', 'editor1' => 'required|min_length[5]', 'editor2' => 'required|min_length[5]', 'editor3' => 'required|min_length[5]', 'service_plan_heading' => 'required', 'service_heading1' => 'required', 'service_heading2' => 'required', 'serviceduration' => 'required', 'status_ind' => 'required']);
        if (!empty($input)) {
            $validated = $this->validate([
                "file" => [
                    "uploaded[file]",
                    "mime_in[file,image/jpg,image/jpeg,image/gif,image/png]",
                    "max_size[file,4096]",
                ],
            ]);
            $avatar = $this
                ->request
                ->getFile('file');
            $avatar2 = $this
                ->request
                ->getFile('image');
            $nameone = $avatar->getClientName();
            $nametwo = $avatar2->getClientName();
         
            if (!empty($service_id_hidd)) {
                $data = []; 
                $data1 = [];
                $data2 = [];               
                if (!empty($nameone) || !empty($nametwo)) {
                    $data3 = array('service_plan_heading' => $service_plan_heading,   'servicename' => $service_name, 'servicesdescription' => $editor1, 'service_heading1' => $service_heading1, 'subscription_button_heading' => $subscription_button_heading, 'service_heading2' => $service_heading2, 'servicescondition' => $editor2, 'services' => $editor3, 'duration' => $serviceduration, 'status_ind' => $status_ind, 'button_heading' => $button_heading, 'created_at' => date('Y-m-d'));   
                    if (isset($nameone) && ($avatar->isValid() && !$avatar->hasMoved())) {
                        $name = $avatar->getName();
                        $ext = $avatar->getClientExtension();
                        $avatar->move('admin/uploads/', $name);
                        $filepath = base_url() . "/uploads/" . $name;
                        session()->setFlashdata('filepath', $filepath);
                        session()->setFlashdata('extension', $ext);
                        $data1 = array('Service_thumbnail' => $avatar->getClientName()); 
                    }
                    if (isset($nametwo) && ($avatar2->isValid() && !$avatar2->hasMoved())) {
                        $name = $avatar2->getName();
                        $ext = $avatar2->getClientExtension();
                        $avatar2->move('admin/uploads/', $name);
                        $filepath = base_url() . "/uploads/" . $name;
                        session()->setFlashdata('filepath', $filepath);
                        session()->setFlashdata('extension', $ext);
                        $data2 = array('subscriptionplane_img' => $avatar2->getClientName()); 
                    }
                    $data =  $data1 + $data2 + $data3;                    
                    $update = $this
                        ->services_model
                        ->where('service_id', $service_id_hidd)->set($data)->update();
                      
                    if ($update) {
                        $delete = $this->subscriptionplandesc_model->where('service_id', $service_id_hidd)->delete();
                        if ($delete) {
                            foreach ($subscrpldsc as $key => $sid) {
                                if (empty($subscrpldsc[$key])) {
                                    unset($subscrpldsc[$key]);
                                }
                            }
                            foreach ($subscrpldsc as $subscrpldscchild) {
                                $data = [
                                    'service_description' => $subscrpldscchild,
                                    'service_id' => $service_id_hidd,
                                    'created_date' => date('Y-m-d'),

                                ];
                                $saved = $this->subscriptionplandesc_model->save($data);
                                if ($saved) {
                                    $status = true;
                                    $session->setFlashdata('success', 'Saved Successfully');
                                } else {
                                    $session->setFlashdata('error', 'Failed to save');
                                }
                            }
                        }} else {
                        $session->setFlashdata('error', 'Service Failed to update');
                    }
                    return redirect()->to('services');
                } else {
                    $data = ['service_plan_heading' => $service_plan_heading,   'servicename' => $service_name, 'servicesdescription' => $editor1, 'service_heading1' => $service_heading1, 'service_heading2' => $service_heading2, 'servicescondition' => $editor2, 'services' => $editor3, 'duration' => $serviceduration, 'status_ind' => $status_ind, 'subscription_button_heading' => $subscription_button_heading, 'button_heading' => $button_heading, 'created_at' => date('Y-m-d')];                   
                    $update = $this
                        ->services_model
                        ->where('service_id', $service_id_hidd)->set($data)->update();
                    if ($update) {
                        $delete = $this->subscriptionplandesc_model->where('service_id', $service_id_hidd)->delete();
                        if ($delete) {
                            foreach ($subscrpldsc as $key => $sid) {
                                if (empty($subscrpldsc[$key])) {
                                    unset($subscrpldsc[$key]);
                                }
                            }
                            foreach ($subscrpldsc as $subscrpldscchild) {
                                $data = [
                                    'service_description' => $subscrpldscchild,
                                    'service_id' => $service_id_hidd,
                                    'created_date' => date('Y-m-d'),
                                ];
                                $saved = $this->subscriptionplandesc_model->save($data);
                                if ($saved) {
                                    $status = true;
                                    $session->setFlashdata('success', 'Saved Successfully');
                                } else {
                                    $session->setFlashdata('error', 'Failed to save');
                                }
                            }
                        }
                    } else {
                        $session->setFlashdata('error', 'Service Failed to update');
                    }
                    return redirect()->to('services');
                }
            } else {
                if ($validated) {
                    $avatar = $this
                        ->request
                        ->getFile('file');
                    $avatar2 = $this
                        ->request
                        ->getFile('image');
                    if ($avatar->isValid() && !$avatar->hasMoved()) {
                        $name = $avatar->getName();
                        $ext = $avatar->getClientExtension();
                        $avatar->move('admin/uploads/', $name);
                        $filepath = base_url() . "/uploads/" . $name;
                        session()->setFlashdata('filepath', $filepath);
                        session()->setFlashdata('extension', $ext);
                    }
                    if ($avatar2->isValid() && !$avatar2->hasMoved()) {
                        $name = $avatar2->getName();
                        $ext = $avatar2->getClientExtension();
                        $avatar2->move('admin/uploads/', $name);
                        $filepath = base_url() . "/uploads/" . $name;
                        session()->setFlashdata('filepath', $filepath);
                        session()->setFlashdata('extension', $ext);
                    }
                    $data = ['Service_thumbnail' => $avatar->getClientName(), 'subscriptionplane_img' => $avatar2->getClientName(), 'service_plan_heading' => $service_plan_heading,   'servicename' => $service_name, 'servicesdescription' => $editor1, 'service_heading1' => $service_heading1, 'service_heading2' => $service_heading2, 'servicescondition' => $editor2, 'services' => $editor3, 'duration' => $serviceduration, 'subscription_button_heading' => $subscription_button_heading, 'status_ind' => $status_ind, 'button_heading' => $button_heading, 'created_at' => date('Y-m-d')];
                    $save = $this
                        ->services_model
                        ->insert($data);
                    $user_id = $this->services_model->getInsertID();
                    if (!empty($user_id)) {
                        foreach ($subscrpldsc as $key => $sid) {
                            if (empty($subscrpldsc[$key])) {
                              unset($subscrpldsc[$key]);
                            }
                        }
                        foreach ($subscrpldsc as $subscrpldscchild) {
                            $data = [
                                'service_description' => $subscrpldscchild,
                                'service_id' => $user_id,
                                'created_date' => date('Y-m-d'),
                            ];
                            $saved = $this->subscriptionplandesc_model->save($data);
                            if ($saved) {
                                $status = true;
                                $session->setFlashdata('success', 'Saved Successfully');
                            } else {
                                $session->setFlashdata('error', 'Failed to save');
                            }
                        }
                    }
                } else {
                    $session->setFlashdata('error', 'Please select a valid file');
                }
            }
        } else {
            $session->setFlashdata('error', 'Fill All Fields');
        }
        return redirect()->to('services');
    }

// edit service

    public function edit_service($id = '')
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
        $data['title'] = 'Edit Services Details';
        $data['pade_title2'] = 'Edit Conditions';
        $data['pade_title6'] = 'Edit Services';
        $data['pade_title1'] = 'Edit Sevice Description';
        $data['pade_title3'] = 'Edit Service  Name';
        $data['pade_title4'] = 'Select Service Category';
        $data['pade_title5'] = 'Edit Service Cost For Type One';
        $data['pade_title7'] = 'Edit Service Heading 1';
        $data['pade_title8'] = 'Edit Service Heading 2';
        $data['pade_title17'] = 'Edit Subscription Plan Frequency Type Two';
        $data['pade_title18'] = 'Edit Subscription Plan Frequency Type Three';
        $data['pade_title19'] = 'Edit Service Cost For Type Two';
        $data['pade_title20'] = 'Edit Service Cost For Type Three';
        $data['pade_title10'] = 'Edit Subscription Plan Image';
        $data['pade_title11'] = 'Edit Subscription Plan Heading';
        $data['pade_title12'] = 'Edit Service Button Text';
        $data['pade_title23'] = 'Edit Subscription Button Text';
        $data['pade_title13'] = 'Edit Subscription Plan Type';
        $data['pade_title14'] = 'Edit Subscription Plan Frequency Type One';
        $data['pade_title15'] = 'Edit Subscription Plan Cost';
        $data['pade_title16'] = 'Edit Subscription Plan Description';
        $data['i'] = 1;
        $data['z'] = 1;
        $data['query'] = $this
            ->services_model->get_row($id);
        $data['page_heading'] = 'Edit Sevice Thumbnail';
        $data['request'] = $this->request;
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);
        $data["view"] = "services/addnewservices";
        return view('templates/default', $data);
    }

// service delete

    public function service_delete($id = '')
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
        $slider_picture = $this->services_model->select(['Service_thumbnail'])->where('service_id', $id)->first();
        $file_delete = dirname(__FILE__, 3);
        $subscrtp_picture = $this->services_model->select(['subscriptionplane_img'])->where('service_id', $id)->first();
        $delete = $this
            ->services_model
            ->where('service_id', $id)->delete();
            if ($delete) {
                $deletetwo = $this->subscriptionplandesc_model->where('service_id', $id)->delete();
                if ($deletetwo) {
                    $session->setFlashdata('success', 'Service has been deleted successfully.');
                } else {
                    $session->setFlashdata('error', 'Service failed due to unknown ID.');
                }
            } else {
                $session->setFlashdata('error', 'Service failed due to unknown ID.');
            }
        $data['request'] = $this->request;
        $data['menuslinks'] = $this
            ->request
            ->uri
            ->getSegment(1);

        return redirect()->to("services");
    }

}
