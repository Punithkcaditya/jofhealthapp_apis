<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Banner as Banner_Model;

class Bannerinfo extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->banner_model = new Banner_Model();
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
        $data["title"] = "Banner Details";
        $data["banner"] = $this->banner_model->orderBy('banner_id', 'DESC')->findAll();
        $data["page_heading"] = "Add New Banner";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        if ($this->permission[0] > 0) {
            $data["link"] = "addNewBanner";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["edit_banner"] = "edit_banner";
        } else {
            $data["edit_banner"] = "#";
        }
        if ($this->permission[2] > 0) {
            $data["delete_banner"] = "delete_banner";
        } else {
            $data["delete_banner"] = "#";
        }
        $data["view"] = "banner/bannerlist";
        return view('templates/default', $data);
    }

    public function addNewBanner()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data['session'] = $session;
        $data["banner_display_order"] = $this->banner_model->select(['display_order'])->orderBy('banner_id', 'DESC')->findAll();
        $data['title'] = 'Add Banner Details';
        $data['pade_title1'] = 'Banner Heading';
        $data['pade_title2'] = 'Upload Banner Image';
        $data['pade_title3'] = 'Choose Banner Status';
        $data['pade_title4'] = 'Enter Display Order';
        $data['menuslinks'] = $this->request->uri->getSegment(1);
        $data["view"] = "banner/bannerssave";
        return view('templates/default', $data);
    }


    public function savebanners()
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

          

                if (!empty($banner_id_hidd)) {
                    if ($validated) {
                        $slider_picture = $this->banner_model->select(['banner'])->where('banner_id', $banner_id_hidd)->first();
                        $file_delete = dirname(__FILE__, 3);
                        foreach ($slider_picture as $slider) {
                            unlink($file_delete . "/uploads/" . $slider);
                        }
                        $checkDisplayOrder = $this->banner_model
                        ->where("display_order", $display_order)
                        ->where("banner_id!=", $banner_id_hidd)
                        ->countAllResults();
                     
                        if ($checkDisplayOrder > 0) {
                            $this->session->setFlashdata( "error", "Display Order Already Taken." );
                            return redirect()->to("banner");
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
                        $udata["banner"] = $slider_pic;
                        $udata["status_ind"] = $status_ind;
                        $udata["display_order"] = $display_order;
                    } else {
                        $checkDisplayOrder = $this->banner_model
                        ->where("display_order", $display_order)
                        ->where("banner_id!=", $banner_id_hidd)
                        ->countAllResults();
                     
                        if ($checkDisplayOrder > 0) {
                            $this->session->setFlashdata( "error", "Display Order Already Taken." );
                            return redirect()->to("banner");
                        }
                        $udata["status_ind"] = $status_ind;
                        $udata["display_order"] = $display_order;
                    }

                    $updated = $this->banner_model
                        ->where("banner_id", $banner_id_hidd)
                        ->set($udata)
                        ->update();
                    if ($updated) {
                        $session->setFlashdata(
                            "success",
                            "Updated Successfully"
                        );
                        return redirect()->to("banner");
                    } else {
                        $session->setFlashdata(
                            "error",
                            "Banner Details failed to update."
                        );
                        return redirect()->to("banner");
                    }
                } else {
                    if ($validated) {
                        $checkDisplayOrder = $this->banner_model
                        ->where("display_order", $display_order)
                        ->countAllResults();
                        if ($checkDisplayOrder > 0) {
                            $this->session->setFlashdata( "error", "Display Order Already Taken." );
                            return redirect()->to("banner");
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

                        $udata["banner"] = $slider_pic;
                        $udata["status_ind"] = $status_ind;
                        $udata["display_order"] = $display_order;
                        $save = $this->banner_model->save($udata);
                        if ($save) {
                            $session->setFlashdata(
                                "success",
                                "Saved Successfully"
                            );
                            return redirect()->to("banner");
                        } else {
                            $session->setFlashdata(
                                "error",
                                "Banner Details failed to save."
                            );
                            return redirect()->to("banner");
                        }
                    }
                }

            } else {
                $session->setFlashdata(
                    "error",
                    "Banner Details failed to save."
                );
                return redirect()->to("banner");
            }
        }
        $session->setFlashdata(
            "error",
            "Banner Details failed to save."
        );
        return redirect()->to("banner");
    }

    public function edit_banner($id = '')
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
        $data["query"] = $this->banner_model->where("banner_id", $id)->first();
        $data["slider_display_order"] = $this->banner_model->select(['display_order'])->orderBy('banner_id', 'DESC')->findAll();
        $data['title'] = 'Edit Banner Details';
        $data['pade_title1'] = 'Edit Banner Heading';
        $data['pade_title2'] = 'Upload Banner Image';
        $data['pade_title3'] = 'Choose Banner Status';
        $data['pade_title4'] = 'Enter Display Order';
        $data['session'] = $session;
        $data['menuslinks'] = $this->request->uri->getSegment(1);
        $data["view"] = "banner/bannerssave";
        return view('templates/default', $data);
    }

    public function delete_banner($id = "")
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
        $slider_picture = $this->banner_model->select(['banner'])->where('banner_id', $id)->first();
        $file_delete = dirname(__FILE__, 3);

        foreach ($slider_picture as $slider) {
            unlink($file_delete . "/uploads/" . $slider);
        }
        $delete = $this->banner_model->where("banner_id", $id)->delete();
        if ($delete) {
            $session->setFlashdata(
                "success",
                "Banner has been deleted successfully."
            );
        } else {
            $session->setFlashdata(
                "error",
                "Banner Deletion failed due to unknown ID."
            );
        }
        return redirect()->to("slider");
    }
}
