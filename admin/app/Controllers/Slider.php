<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Slider as Slider_Model;

class Slider extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->slider_model = new Slider_Model();
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
        $data["session"] = $session;
        $data["title"] = "Slider Details";
        $data["slider"] = $this->slider_model->orderBy('sliderId', 'DESC')->findAll();
        $data["page_heading"] = "Add New Slider";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        if ($this->permission[0] > 0) {
            $data["link"] = "addNewSlider";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["edit_slider"] = "edit_slider";
        } else {
            $data["edit_slider"] = "#";
        }
        if ($this->permission[2] > 0) {
            $data["delete_slider"] = "delete_slider";
        } else {
            $data["delete_slider"] = "#";
        }
        $data["view"] = "sliders/sliderlist";
        return view('templates/default', $data);
    }

    public function addNewSlider()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data['session'] = $session;
        $data["slider_display_order"] = $this->slider_model->select(['display_order'])->orderBy('sliderId', 'DESC')->findAll();
        $data['title'] = 'Add Slider Details';
        $data['pade_title1'] = 'Slider Heading';
        $data['pade_title2'] = 'Upload Slider Image';
        $data['pade_title3'] = 'Choose Slider Status';
        $data['pade_title4'] = 'Enter Display Order';
        $data['menuslinks'] = $this->request->uri->getSegment(1);
        $data["view"] = "sliders/slidersave";
        return view('templates/default', $data);
    }

    public function savesliders()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if ($this->request->getMethod() == "post") {
            extract($this->request->getPost());
            $input = $this->validate([
                "slider_heading" => "required|min_length[3]",
                "status_ind" => "required",
                "display_order" => "required",
            ]);

            if (!empty($input)) {
                $validated = $this->validate([
                    "file" => [
                        "uploaded[file]",
                        "mime_in[file,image/jpg,image/jpeg,image/gif,image/png]",
                        "max_size[file,4096]",
                    ],
                ]);

          

                if (!empty($slider_id_hidd)) {
                    if ($validated) {
                        $slider_picture = $this->slider_model->select(['slider'])->where('sliderId', $id)->first();
                        $file_delete = dirname(__FILE__, 3);          
                        foreach ($slider_picture as $slider) {
                            unlink($file_delete . "/uploads/" . $slider);
                        }
                        $checkDisplayOrder = $this->slider_model
                        ->where("display_order", $display_order)
                        ->where("sliderId!=", $slider_id_hidd)
                        ->countAllResults();
                     
                        if ($checkDisplayOrder > 0) {
                            $this->session->setFlashdata( "error", "Display Order Already Taken." );
                            return redirect()->to("slider");
                        }
                        
                        $avatar = $this->request->getFile("file");
                        if ($avatar->isValid() && !$avatar->hasMoved()) {
                            $slider_pic = $avatar->getName();
                            $ext = $avatar->getClientExtension();
                            $avatar->move("admin/uploads/", $slider_pic);
                            $filepath = base_url() . "/uploads/" . $slider_pic;
                            session()->setFlashdata("filepath", $filepath);
                            session()->setFlashdata("extension", $ext);
                        }
                        $udata["slider"] = $slider_pic;
                        $udata["slider_heading"] = $slider_heading;
                        $udata["status_ind"] = $status_ind;
                        $udata["display_order"] = $display_order;
                    } else {
                        $checkDisplayOrder = $this->slider_model
                        ->where("display_order", $display_order)
                        ->where("sliderId!=", $slider_id_hidd)
                        ->countAllResults();
                     
                        if ($checkDisplayOrder > 0) {
                            $this->session->setFlashdata( "error", "Display Order Already Taken." );
                            return redirect()->to("slider");
                        }
                        $udata["slider_heading"] = $slider_heading;
                        $udata["status_ind"] = $status_ind;
                        $udata["display_order"] = $display_order;
                    }

                    $updated = $this->slider_model
                        ->where("sliderId", $slider_id_hidd)
                        ->set($udata)
                        ->update();
                    if ($updated) {
                        $session->setFlashdata(
                            "success",
                            "Updated Successfully"
                        );
                        return redirect()->to("slider");
                    } else {
                        $session->setFlashdata(
                            "error",
                            "Slider Details failed to update."
                        );
                        return redirect()->to("slider");
                    }
                } else {
                    if ($validated) {
                        $checkDisplayOrder = $this->slider_model
                        ->where("display_order", $display_order)
                        ->countAllResults();
                        if ($checkDisplayOrder > 0) {
                            $this->session->setFlashdata( "error", "Display Order Already Taken." );
                            return redirect()->to("slider");
                        }
                        $avatar = $this->request->getFile("file");
                        if ($avatar->isValid() && !$avatar->hasMoved()) {
                            $slider_pic = $avatar->getName();
                            $ext = $avatar->getClientExtension();
                            $avatar->move("admin/uploads/", $slider_pic);
                            $filepath = base_url() . "/uploads/" . $slider_pic;
                            session()->setFlashdata("filepath", $filepath);
                            session()->setFlashdata("extension", $ext);
                        }

                        $udata["slider"] = $slider_pic;
                        $udata["slider_heading"] = $slider_heading;
                        $udata["status_ind"] = $status_ind;
                        $udata["display_order"] = $display_order;
                        $save = $this->slider_model->save($udata);
                        if ($save) {
                            $session->setFlashdata(
                                "success",
                                "Saved Successfully"
                            );
                            return redirect()->to("slider");
                        } else {
                            $session->setFlashdata(
                                "error",
                                "Slider Details failed to save."
                            );
                            return redirect()->to("slider");
                        }
                    }
                }

            } else {
                $session->setFlashdata(
                    "error",
                    "Slider Details failed to save."
                );
                return redirect()->to("slider");
            }
        }
        $session->setFlashdata(
            "error",
            "Slider Details failed to save."
        );
        return redirect()->to("slider");
    }

    public function editslider($id = '')
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if ($id == null) {
            return redirect("Admindashboard");
        }
        $data["query"] = $this->slider_model->where("sliderId", $id)->first();
        $data["slider_display_order"] = $this->slider_model->select(['display_order'])->orderBy('sliderId', 'DESC')->findAll();
        $data['title'] = 'Edit Slider Details';
        $data['pade_title1'] = 'Edit Slider Heading';
        $data['pade_title2'] = 'Upload Slider Image';
        $data['pade_title3'] = 'Choose Slider Status';
        $data['pade_title4'] = 'Enter Display Order';
        $data['session'] = $session;
        $data['menuslinks'] = $this->request->uri->getSegment(1);
        $data["view"] = "sliders/slidersave";
        return view('templates/default', $data);
    }

    public function delete_slider($id = "")
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if ($id == null) {
            return redirect("Admindashboard");
        }
        $slider_picture = $this->slider_model->select(['slider'])->where('sliderId', $id)->first();
        $file_delete = dirname(__FILE__, 3);

        foreach ($slider_picture as $slider) {
            unlink($file_delete . "/uploads/" . $slider);
        }
        $delete = $this->slider_model->where("sliderId", $id)->delete();
        if ($delete) {
            $session->setFlashdata(
                "success",
                "Slider has been deleted successfully."
            );
        } else {
            $session->setFlashdata(
                "error",
                "Slider Deletion failed due to unknown ID."
            );
        }
        return redirect()->to("slider");
    }
}