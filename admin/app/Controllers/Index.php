<?php

namespace App\Controllers;

defined('BASEPATH') or exit('No direct script access allowed');

use App\Controllers\BaseController;

class Index extends BaseController
{
    protected $request;

    public function __construct()
    {
        parent::__construct();
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
        $data = [];
        $session = session();
        $user_id = $session->get("userdata");
        $input = $this->validate([
            "password" => "required|min_length[3]",
            "email" => "required|valid_email",
        ]);

        if (!empty($input)) {
            $login_detail = (array) $this->admin_users_model->loginnew(
                $this->request->getPost()
            );

            if (!empty($login_detail)) {

                unset($login_detail["logged_session_id"]);
                $user_session_id = rand("2659748135965", "088986555510245579");

                $this->admin_users_model->data["user_session_id"] = $user_session_id;

                $login_detail["logged_session_id"] = md5($user_session_id);

                $session->set("userdata", $login_detail);
                $pot = json_decode(json_encode($session->userdata), true);
                $this->admin_users_model->primary_key = [
                    "user_id" => $pot["user_id"],
                ];
                $this->admin_users_model->updateData();
                return redirect()->to("Admindashboard");
                //return view('welcome_message');
            } else {
                $session->setFlashdata("error", "Incorrect Email and Password");
                $data["session"] = $session;
                return redirect()->to("/");

            }
        }

    }

    public function dashboard()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        //          echo"<pre>";
        // var_dump($session->userdata["logged_session_id"], md5($user_session_id));
        // exit;

        $data["page_title"] =
        "Welcome - " .
        ucfirst($pot["first_name"]) .
        " " .
        ucfirst($pot["last_name"]);
        $data["page_heading"] =
        "Welcome - " .
        ucfirst($pot["first_name"]) .
        " " .
        ucfirst($pot["last_name"]);
        $data["sub_heading"] =
        "Welcome - " .
        ucfirst($pot["first_name"]) .
        " " .
        ucfirst($pot["last_name"]);
        $data["session"] = $session;
        $data["breadcrumb"] = "Admindashboard";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data['view'] = 'admin/dashboard';
        return view('templates/default', $data);
    }

    public function addrole()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        $this->loadUser();

        $role_id = $pot["role_id"];
        $data["view"] = "admin/roles";
        $data["page_title"] = "Add New Roles";
        $data["session"] = $session;
        if ($this->permission[0] > 0) {
            $data["link"] = "addnewroles";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["user_rolesedit"] = "user_rolesedit";
        } else {
            $data["user_rolesedit"] = "#";
        }
        if ($this->permission[2] > 0) {
            $data["user_delete"] = "user_delete";
        } else {
            $data["user_delete"] = "#";
        }
        // $data['breadcrumb'] = "<a href=User/$this->class_name>Roles</a> &nbsp;&nbsp; > &nbsp;&nbsp; Add Role";
        $data["page_heading"] = "Add  Roles";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["q"] = $this->admin_users_model->findroles($role_id);
        $data["roles"] = $this->admin_roles_model
            ->orderBy("role_id", "ASCE")
            ->findAll();
        return view('templates/default', $data);

    }

    public function addnewroles()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $this->loadUser();
        $data["session"] = $session;
        $data["page_title"] = "Add New Roles";
        $data["page_heading"] = "Add New Users";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/addnewroles";
        return view('templates/default', $data);
    }

    public function user_rolesedit($id = "")
    {
        if ($id == null) {
            return redirect("Admindashboard");
        }
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["userInfo"] = $this->admin_roles_model
            ->where("role_id= '{$id}'")
            ->findAll();
        $this->loadUser();
        $data["session"] = $session;
        $data["page_title"] = "Edit New Roles";
        $data["page_heading"] = "Edit New Users";
        $data["request"] = $this->request;
        $data["query"] = $this->admin_roles_model->get_row($id);
        $data["roles"] = $this->admin_roles_model
            ->orderBy("role_id", "DESC")
            ->findAll();
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/editnewroles";
        return view('templates/default', $data);
    }

    public function savenewroles()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        // echo '<pre>';
        // print_r($pot['user_id'] );
        // exit;

        $input = $this->validate([
            "role_name" => "required|min_length[3]",
            "status_ind" => "required",
        ]);

        if (!empty($input)) {
            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());
                if (!preg_match('/^[a-zA-Z_ ]*$/', $role_name)) {
                    $session->setFlashdata("error", "Special characters and Numbers are not allowed");
                    return redirect()->to("addnewroles");
                }
                $udata = [];
                $udata["role_name"] = $role_name;
                $udata["status_ind"] = $status_ind;
                $udata["created_date"] = date("Y-m-d");
                $udata["created_by"] = $pot["user_id"];
                $udata["last_modified_by"] = $pot["user_id"];
                // echo '<pre>';
                // print_r($udata);
                // exit;
                $save = $this->admin_roles_model->save($udata);
                if ($save) {
                    $session->setFlashdata("success", "Saved Successfully");
                    return redirect()->to("addnewroles");
                } else {
                    $session->setFlashdata("error", "Failed to save");
                    return redirect()->to("addnewroles");
                }
            }

        } else {
            $session->setFlashdata("error", "Enter All Fields");
            return redirect()->to("addnewroles");
        }

    }

    public function editnewroles()
    {

        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        // echo '<pre>';
        // print_r($pot['user_id'] );
        // exit;
        $input = $this->validate([
            "role_name" => "required|min_length[3]",
            "status_ind" => "required",
        ]);

        if (!empty($input)) {
            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());

                if (!empty($user_id_hidd)) {
                    $udata = [];
                    $udata["role_name"] = $role_name;
                    $udata["status_ind"] = $status_ind;
                    $udata["modified_date"] = date("Y-m-d");
                    $udata["modified_by"] = $pot["user_id"];

                    $update = $this->admin_roles_model
                        ->where("role_id", $user_id_hidd)
                        ->set($udata)
                        ->update();

                    //     echo '<pre>';
                    //     print_r($update);
                    //     exit;
                    if ($update) {
                        $session->setFlashdata(
                            "success",
                            "Updated Successfully!!"
                        );
                        return redirect()->to("user_rolesedit/$user_id_hidd");

                    } else {
                        $session->setFlashdata("success", "Failed To Update");
                        return redirect()->to("user_rolesedit/$user_id_hidd");
                    }
                }
            }

        } else {
            $session->setFlashdata("error", "Enter All Fields");
            return redirect()->to("user_rolesedit/$user_id_hidd");
        }

    }

    public function user_delete($id = "")
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        if (empty($id)) {
            $this->session->setFlashdata(
                "error",
                "user Deletion failed due to unknown ID."
            );
            return redirect()->to("Admindashboard");
        }
        $delete = $this->admin_users_model->where("user_id", $id)->delete();
        if ($delete) {
            $session->setFlashdata(
                "success",
                "User has been deleted successfully."
            );
        } else {
            $session->setFlashdata(
                "error",
                "User Deletion failed due to unknown ID."
            );
        }

        return redirect()->to("addemployee");
    }

    public function access($id = "")
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if (empty($id)) {
            $this->session->setFlashdata(
                "error",
                "user Access failed due to unknown ID."
            );
            return redirect()->to("Admindashboard");
        }
        $this->loadUser();
        $accesses = [];
        $data["query"] = $this->admin_menuitems_model->view();
        $roles_accesses = $this->admin_roles_accesses_model->view($id);
        foreach ($roles_accesses as $row) {
            $accesses[] = $row->menuitem_id;
        }
        $data["session"] = $session;
        $data["title"] = "Administrator Dashboard - ";
        $data["request"] = $this->request;
        $data["role_id"] = $id;
        $data["admin_users_accesses"] = $accesses;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/access";
        return view('templates/default', $data);
    }

    public function saveaccess()
    {
        $session = session();
        $request = \Config\Services::request();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        } else {
            $user_id = $pot["user_id"];
            $role_id = $pot["role_id"];
        }
        $input = $this->validate([
            "menuitem_id" => "required",
        ]);

        if (!empty($input)) {

            if ($user_id == 1 || $user_id == 2) {
                if ($this->request->getMethod() == "post") {
                    extract($this->request->getPost());
                    $status = true;
                    $role_id = $request->getPost("role_id");

                    $this->admin_roles_accesses_model->primary_key = [
                        "role_id" => $role_id,
                    ];
                    $delete = $this->admin_roles_accesses_model
                        ->where("role_id", $role_id)
                        ->delete();
                    if ($delete) {
                        $menuitem_ids = $request->getPost("menuitem_id");

                        foreach ($menuitem_ids as $menuitem_id) {
                            $data = [
                                "menuitem_id" => $menuitem_id,
                                "role_id" => $role_id,
                            ];

                            $save = $this->admin_roles_accesses_model->save($data);
                            if ($save) {
                                $status = true;
                            }
                        }
                    }

                    if ($status) {
                        $this->session->setFlashdata("success", "user Access saved.");
                    } else {
                        $this->session->setFlashdata(
                            "error",
                            "user Access failed due to unknown ID."
                        );
                    }
                }
            } else {
                $this->session->setFlashdata(
                    "error",
                    "Sorry! You do not have the permission."
                );
            }
        } else {
            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());
                $delete = $this->admin_roles_accesses_model
                    ->where("role_id", $role_id)
                    ->delete();
            }
            $this->session->setFlashdata("success", "user Access saved.");
        }
        //     $this->session->set_flashdata('msg', $msg);
        return redirect()->to("Admindashboard");
    }

    // permission

    public function permission($id)
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if (empty($id)) {
            $this->session->setFlashdata(
                "error",
                "user Permission failed due to unknown ID."
            );
            return redirect()->to("Admindashboard");
        }
        $this->loadUser();

        if (!empty($id)) {
            $accesses = [];
            $roles_accesses = $this->admin_roles_accesses_model->view_access($id);
            foreach ($roles_accesses as $row) {
                $accesses[] = $row->menuitem_id;
            }
            $data["session"] = $session;
            $data["role_id"] = $id;
            $data["query"] = $roles_accesses; //$_SESSION['sidebar_menuitems'];
            $data["title"] = "Role Access  Permission ";
            $data["page_heading"] = "Role Access Permission";
            $data["request"] = $this->request;
            $data["menuslinks"] = $this->request->uri->getSegment(1);
            $data["view"] = "admin/permission";
            return view('templates/default', $data);
        } else {

            $this->session->setFlashdata(
                "error",
                "Sorry! You do not have the permission."
            );
            return redirect()->to("Admindashboard");
        }

    }

// savepermission

    public function savepermission()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        $request = \Config\Services::request();
        $this->loadUser();
        if (empty($pot)) {
            return redirect()->to("/");
        } else {
            $user_id = $pot["user_id"];
            $role_id = $pot["role_id"];
        }

        if ($user_id == 1 || $role_id == 2) {
            $status = true;
            $role_id = $request->getPost("role_id");
            $i = 0;
            $menuitem_ids = $request->getPost("menuitem_id");

            foreach ($menuitem_ids as $menuitem_id) {
                $add_permission = $request->getPost("add_permission");

                if (!empty($request->getPost("add_permission")[$i])) {
                    // echo '<pre>';
                    // print_r($add_permission[$i]);
                    // exit;
                    $add_permission = $request->getPost("add_permission")[$i];
                } else {
                    $add_permission = 0;
                }
                if (!empty($request->getPost("edit_permission")[$i])) {
                    $edit_permission = $request->getPost("edit_permission")[$i];
                } else {
                    $edit_permission = 0;
                }
                if (!empty($request->getPost("delete_permission")[$i])) {
                    $delete_permission = $request->getPost("delete_permission")[$i];
                } else {
                    $delete_permission = 0;
                }
                $udata["add_permission"] = $add_permission;
                $udata["role_id"] = $role_id;
                $udata["edit_permission"] = $edit_permission;
                $udata["delete_permission"] = $delete_permission;

                $update = $this->admin_roles_accesses_model
                    ->where("menuitem_id", $menuitem_id)
                    ->where("role_id", $role_id)
                    ->set($udata)
                    ->update();

                if ($update) {
                    $status = true;
                    $udata = [];
                }
                $i++;
            }

            if ($status) {
                $this->session->setFlashdata("success", "user Permission saved.");
                return redirect()->to("Admindashboard");
            } else {
                $this->session->setFlashdata("error", "user Permission failed saved.");
                return redirect()->to("Admindashboard");
            }
        } else {
            $this->session->setFlashdata("error", "Sorry! You do not have the permission.");
            return redirect()->to("Admindashboard");
        }
    }

    public function addemployee()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["user_data"] = [];
        $this->loadUser();
        $data["session"] = $session;
        $data["title"] = "Administrator Dashboard - ";
        // $data['breadcrumb'] = "<a href=User/$this->class_name>Roles</a> &nbsp;&nbsp; > &nbsp;&nbsp; Add Role";
        $data["page_heading"] = "Add New User";
        if ($this->permission[0] > 0) {
            $data["link"] = "addNew";
        } else {
            $data["link"] = "#";
        }
        if ($this->permission[1] > 0) {
            $data["user_edit"] = "user_edit";
        } else {
            $data["user_edit"] = "#";
        }
        if ($this->permission[2] > 0) {
            $data["user_delete"] = "user_delete";
        } else {
            $data["user_delete"] = "#";
        }
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["users"] = $this->admin_users_model
            ->orderBy("user_id", "ASCE")
            ->findAll();
        $data["view"] = "admin/addusers";
        return view('templates/default', $data);
    }

// add new user

    public function addNew()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["user_data"] = [];
        $this->loadUser();
        $data["session"] = $session;
        $data["pade_title"] = "Admin New User";
        $data["link"] = "addNew";
        $data["roles"] = $this->admin_roles_model
            ->where('status_ind', 1)
            ->orderBy("role_id", "DESC")
            ->findAll();
        $data["page_heading"] = "Add New Users";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/addnewusers";
        return view('templates/default', $data);
    }

    public function addnewuser()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        helper(["form", "url", "string"]);
        $sellername = 'Seller';
        $sellerId = $this->admin_roles_model->getsellerid($sellername);
        $sellerRoleId = $sellerId[0]['role_id'];

        $input = $this->validate([
            "first_name" => "required|min_length[3]",
            "last_name" => "required|min_length[3]",
            "email" => "required|valid_email",
            "phone_number" => 'required|numeric|regex_match[/^[0-9]{10}$/]',
            "username" => "required|min_length[3]",
            "status_ind" => "required",
            "role_id" => "required",
        ]);

        if (!empty($input)) {

            $validated = $this->validate([
                "file" => [
                    "uploaded[file]",
                    "mime_in[file,image/jpg,image/jpeg,image/gif,image/png]",
                    "max_size[file,4096]",
                ],
            ]);

            if ($validated) {
                $avatar = $this->request->getFile("file");
                //$avatar->move(WRITEPATH . 'uploads');
                if ($avatar->isValid() && !$avatar->hasMoved()) {
                    $profile_pic = $avatar->getName();
                    $ext = $avatar->getClientExtension();
                    $avatar->move("admin/uploads/", $profile_pic);
                    //$avatar->move(WRITEPATH . 'uploads', $name);
                    $filepath = base_url() . "/uploads/" . $profile_pic;
                    // File path to display preview
                    // $filepath = WRITEPATH . "uploads";
                    session()->setFlashdata("filepath", $filepath);
                    session()->setFlashdata("extension", $ext);
                }
            }

            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());

                if ($password !== $cpassword) {
                    $this->session->setFlashdata(
                        "error",
                        "Password does not match."
                    );
                } else {
                    $udata = [];
                    $udata["first_name"] = $first_name;
                    $udata["last_name"] = $last_name;
                    $udata["phone_number"] = $phone_number;
                    $udata["username"] = $username;
                    $udata["role_id"] = $role_id;
                    $udata["status_ind"] = $status_ind;
                    $udata["email"] = $email;
                    $udata["employee_id"] = rand(1000, 9999);
                    $udata["created_date"] = date("Y-m-d");
                    $udata["created_by"] = $pot["user_id"];
                    $udata["profile_pic"] = $profile_pic;
                    if (!empty($password)) {
                        $udata["password"] = md5($password);
                    }
                    $checkMail = $this->admin_users_model
                        ->where("email", $email)
                        ->countAllResults();
                    $checkPhone = $this->admin_users_model
                        ->where("phone_number", $phone_number)
                        ->countAllResults();
                    $checkEmployee_id = $this->admin_users_model
                        ->where("employee_id", $udata["employee_id"])
                        ->countAllResults();
                    if ($checkMail > 0 || $checkPhone > 0) {
                        $this->session->setFlashdata(
                            "error",
                            "User Email Already Taken."
                        );
                    } else {
                        $save = $this->admin_users_model->insert_data($udata);

                        // for seller details company table

                        if ($role_id == $sellerRoleId) {
                            $data = [
                                "company_name" => $first_name,
                                'seller_last_name' => $last_name,
                                "phone_number" => $phone_number,
                                "role_id" => $role_id,
                                "status_ind" => $status_ind,
                                "created_date" => date("Y-m-d"),
                                "created_by" => $pot["user_id"],
                                "seller_user_id" => $save,
                                "company_email" => $email,
                                "profile_pic" => $profile_pic,
                            ];

                            $sellersave = $this->seller_model->dataInsert($data);
                        }

                        if ($save) {
                            $session->setFlashdata(
                                "success",
                                "Saved Successfully"
                            );

                            return redirect()->to("addNew");
                        } else {
                            $session->setFlashdata(
                                "error",
                                "User Details has failed to save."
                            );
                            return redirect()->to("addNew");
                        }
                    }
                }
            }
            // $session->setFlashdata('success', 'All Fine');
        } else {
            $session->setFlashdata("error", "Enter All Fields");
            return redirect()->to("addNew");

        }

        //return view('Modules\Admin\Views\pages\addnew', $data);
        // return view('pages/users/add', $this->data);
    }

// useredit

    public function user_edit($id = "")
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if ($id == null) {
            return redirect("Admindashboard");
        } else {
            $data["userInfo"] = $this->admin_users_model
                ->where("user_id= '{$id}'")
                ->findAll();
            $this->loadUser();
            $this->global["pageTitle"] = "Edit User";
            $session = session();
            $data["title"] = "Edit User";
            $data["query"] = $this->admin_users_model->get_row($id);
            $data["roles"] = $this->admin_roles_model
                ->orderBy("role_id", "DESC")
                ->findAll();
            $data["session"] = $session;
            // $data['breadcrumb'] = "<a href=User/$this->class_name>Roles</a> &nbsp;&nbsp; > &nbsp;&nbsp; Add Role";
            $data["page_heading"] = "edit  Users";
            $data["request"] = $this->request;
            $data["menuslinks"] = $this->request->uri->getSegment(1);
            $returnArr = [];
            foreach ($data["userInfo"] as $k => $v) {
                $returnArr = array_merge($returnArr, $v);
            }
            $a = (object) $returnArr;
            $data["view"] = "admin/editnew";
            return view('templates/default', $data);
        }
    }

// edit new user

    public function editnewuser()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        $sellername = 'Seller';
        $sellerId = $this->admin_roles_model->getsellerid($sellername);
        $sellerRoleId = $sellerId[0]['role_id'];
        $input = $this->validate([
            "first_name" => "required|min_length[3]",
            "email" => "required|valid_email",
            "phone_number" => 'required|numeric|regex_match[/^[0-9]{10}$/]',
            "username" => "required|min_length[3]",
            "status_ind" => "required",
            "role_id" => "required",
        ]);

        if (!empty($input)) {

            $validated = $this->validate([
                "file" => [
                    "uploaded[file]",
                    "mime_in[file,image/jpg,image/jpeg,image/gif,image/png]",
                    "max_size[file,4096]",
                ],
            ]);

            if ($this->request->getMethod() == "post") {
                extract($this->request->getPost());

                if (!empty($user_id_hidd)) {
                    if ($password !== $cpassword) {
                        $session->setFlashdata(
                            "error",
                            "Password does not match."
                        );
                    } else {

                        if ($validated) {
                            $avatar = $this->request->getFile("file");
                            //$avatar->move(WRITEPATH . 'uploads');
                            if ($avatar->isValid() && !$avatar->hasMoved()) {
                                $profile_pic = $avatar->getName();
                                $ext = $avatar->getClientExtension();
                                $avatar->move("admin/uploads/", $profile_pic);
                                //$avatar->move(WRITEPATH . 'uploads', $name);
                                $filepath = base_url() . "/uploads/" . $profile_pic;
                                // File path to display preview
                                // $filepath = WRITEPATH . "uploads";
                                session()->setFlashdata("filepath", $filepath);
                                session()->setFlashdata("extension", $ext);
                            }
                            $dataupd = [
                                "company_name" => $first_name,
                                'seller_last_name' => $last_name,
                                "phone_number" => $phone_number,
                                "role_id" => $role_id,
                                "status_ind" => $status_ind,
                                "created_date" => date("Y-m-d"),
                                "created_by" => $pot["user_id"],
                                "seller_user_id" => $user_id_hidd,
                                "company_email" => $email,
                                "profile_pic" => $profile_pic,
                            ];

                            $udata = [];
                            $udata["first_name"] = $first_name;
                            $udata["last_name"] = $last_name;
                            $udata["phone_number"] = $phone_number;
                            $udata["username"] = $username;
                            $udata["role_id"] = $role_id;
                            $udata["status_ind"] = $status_ind;
                            $udata["email"] = $email;
                            $udata["modified_date"] = date("Y-m-d");
                            $udata["modified_by"] = $pot["user_id"];
                            $udata["profile_pic"] = $profile_pic;
                        } else {

                            $udata = [];
                            $udata["first_name"] = $first_name;
                            $udata["last_name"] = $last_name;
                            $udata["phone_number"] = $phone_number;
                            $udata["username"] = $username;
                            $udata["role_id"] = $role_id;
                            $udata["status_ind"] = $status_ind;
                            $udata["email"] = $email;
                            $udata["modified_date"] = date("Y-m-d");
                            $udata["modified_by"] = $pot["user_id"];

                            $dataupd = [
                                "company_name" => $first_name,
                                'seller_last_name' => $last_name,
                                "phone_number" => $phone_number,
                                "role_id" => $role_id,
                                "status_ind" => $status_ind,
                                "created_date" => date("Y-m-d"),
                                "created_by" => $pot["user_id"],
                                "seller_user_id" => $user_id_hidd,
                                "company_email" => $email,

                            ];
                        }

                        if (!empty($password)) {
                            $udata["password"] = md5($password);
                        }

                        $checkMail = $this->admin_users_model
                            ->where("email", $email)
                            ->where("user_id!=", $user_id_hidd)
                            ->countAllResults();
                        $checkPhone = $this->admin_users_model
                            ->where("phone_number", $phone_number)
                            ->where("user_id!=", $user_id_hidd)
                            ->countAllResults();

                        if ($checkMail > 0 || $checkPhone > 0) {
                            $session->setFlashdata(
                                "error",
                                "User Email Already Taken Or Phone Number Already Taken."
                            );
                            return redirect()->to("user_edit/$user_id_hidd");
                        } else {
                            $update = $this->admin_users_model
                                ->where("user_id", $user_id_hidd)
                                ->set($udata)
                                ->update();

                            // update for seller company details
                            if ($role_id == $sellerRoleId) {

                                $this->seller_model
                                    ->where("seller_user_id", $user_id_hidd)
                                    ->set($dataupd)
                                    ->update();
                            }

                            if ($update) {
                                $session->setFlashdata(
                                    "success",
                                    "Updated Successfully"
                                );
                                return redirect()->to("user_edit/$user_id_hidd");
                            } else {
                                $session->setFlashdata(
                                    "error",
                                    "Failed to Update"
                                );
                                return redirect()->to("user_edit/$user_id_hidd");
                            }
                        }
                    }
                }
            }

            // $session->setFlashdata('success', 'All Fine');
        } else {
            $session->setFlashdata("error", "Enter All Fields");
            return redirect()->to("addNew");
        }

    }

    public function guestlist()
    {
        $this->loadUser();
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $data["session"] = $session;
        $data['page_heading'] = "Guest List";
        $data['link'] = "addguestlist";
        $data["breadcrumb"] = "Admindashboard";
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["guest"] = $this->guest_model
            ->orderBy("guest_list_id", "DESC")
            ->findAll();
        $data['view'] = 'admin/guestlist';
        return view('templates/default', $data);
    }
    public function addguestlist()
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        $this->loadUser();
        $data["session"] = $session;
        $udata = [];
        if ($this->request->getMethod() == "post") {
            extract($this->request->getPost());
            if (!empty($guest_list_id)) {

                $udata["name"] = $guest_name;
                $udata["phone"] = $guest_phone_number;
                $udata["email"] = $guest_email;
                $udata["Ladies_Mehendi"] = $Ladies_Mehendi;
                $udata["no_of_guest"] = $no_of_guest;
                $udata["guest_comment"] = $guest_comment;
                $udata["Sangeet"] = $Sangeet;
                $udata["Tel_Baan"] = $Tel_Baan;
                $udata["Baraat_Wedding_Reception"] = $Baraat_Wedding_Reception;
                $udata["phone"] = $guest_phone_number;
                $udata["created_at"] = date("Y-m-d");
                $update = $this->guest_model
                    ->where("guest_list_id", $guest_list_id)
                    ->set($udata)
                    ->update();
                if ($update) {
                    $session->setFlashdata("success", "Updated Successfully!!");
                    return redirect()->to("guest_edit/$guest_list_id");
                } else {
                    $session->setFlashdata("success", "Failed To Update");
                    return redirect()->to("guest_edit/$guest_list_id");
                }
            } else {

                $udata["name"] = $guest_name;
                $udata["phone"] = $guest_phone_number;
                $udata["email"] = $guest_email;
                $udata["Ladies_Mehendi"] = $Ladies_Mehendi;
                $udata["no_of_guest"] = $no_of_guest;
                $udata["guest_comment"] = $guest_comment;
                $udata["Sangeet"] = $Sangeet;
                $udata["Tel_Baan"] = $Tel_Baan;
                $udata["Baraat_Wedding_Reception"] = $Baraat_Wedding_Reception;
                $udata["phone"] = $guest_phone_number;
                $udata["created_at"] = date("Y-m-d");

                $save = $this->guest_model->save($udata);
                if ($save) {
                    $session->setFlashdata("success", "Saved Successfully");
                    return redirect()->to("guestlist");
                } else {
                    $session->setFlashdata("error", "Failed to save");
                    return redirect()->to("guestlist");
                }
            }

        }

        $data["pade_title"] = "Admin New Guest";
        $data["link"] = "addGuest";
        $data["page_heading"] = "Add New Guest";
        $data["request"] = $this->request;
        $data["menuslinks"] = $this->request->uri->getSegment(1);
        $data["view"] = "admin/addnewguest";
        return view('templates/default', $data);
    }

    public function guest_edit($id = '')
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }
        if ($id == null) {
            return redirect()->to("Admindashboard");
        } else {
            $guestinfo = $this->guest_model
                ->where("guest_list_id= '{$id}'")
                ->findAll();
            foreach ($guestinfo as $val) {
                $data["guestinfo"] = $val;
            }

            $this->loadUser();
            $data["pade_title"] = "Admin New Guest";
            $data["title"] = "Edit User";
            $data["session"] = $session;
            $data["page_heading"] = "edit  Users";
            $data["request"] = $this->request;
            $data["menuslinks"] = $this->request->uri->getSegment(1);
            $data["view"] = "admin/addnewguest";
            return view('templates/default', $data);

        }
    }

    public function guest_delete($id = '')
    {
        $session = session();
        $pot = json_decode(json_encode($session->get("userdata")), true);
        if (empty($pot)) {
            return redirect()->to("/");
        }

        if (empty($id)) {
            $this->session->setFlashdata(
                "error",
                "Guest Deletion failed due to unknown ID."
            );
            return redirect()->to("guestlist");
        }
        $delete = $this->guest_model->where("guest_list_id", $id)->delete();
        if ($delete) {
            $session->setFlashdata(
                "success",
                "Guest has been deleted successfully."
            );
        } else {
            $session->setFlashdata(
                "error",
                "Guest Deletion failed due to unknown ID."
            );
        }
        return redirect()->to("guestlist");
    }

}

// echo '<pre>';
// print_r($data["guestinfo"]);
// exit;
