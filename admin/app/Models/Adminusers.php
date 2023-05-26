<?php

namespace App\Models;

use Config\Database;
use CodeIgniter\Model;

class Adminusers extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'admin_users';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['employee_id' , 'first_name', 'username', 'user_session_id', 'profile_pic', 'customer_location_id', 'is_type', 'last_active', 'login_token', 'modified_by' , 'modified_date',  'last_name', 'phone_number', 'role_id' , 'status_ind' , 'created_by', 'created_date', 'email', 'password'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
        $this->primary_key = array();
        $this->date = array();
       
        // OR $this->db = db_connect();
    }
    public $data;
    public function loginnew( $data) { 
        $sql1 = 'SELECT role_id from admin_users where password = "' .md5($data['password']). '" and email = "'.$data['email'].'"';
        $query1 = $this->db->query($sql1);
        $result1 = $query1->getResultObject();
        if(!empty($result1)){
            foreach($result1 as $value1){
                $id1  = $value1;
                    }
                    if (empty($id1) ) {
                        return false;
                    }
            $sql = 'SELECT a.*   FROM ' . $this->table . ' a LEFT JOIN admin_roles ar ON a.role_id = ar.role_id where a.role_id = ' .$id1->role_id. ' AND  a.password = "' .md5($data['password']). '" AND a.status_ind = 1 AND a.email = "'.$data['email'].'"'; 
            $query = $this->db->query($sql);
            $result = $query->getRow();
            return $result;
        }
      
	}


    public function updateData()
    {
            $builder = $this->db->table("admin_users");
      
            $updated_data = $this->data;
              
            $builder->where([
                "user_id" => $this->primary_key
            ]);
            $builder->set($updated_data);
      
            return $builder->update(); 
    }


    public function session_id() {

    
        foreach($this->primary_key as $value){
    $id  = $value;
        }
       
        $sql = 'SELECT *   FROM ' . $this->table . '  where user_id = ' .$id. ' AND  status_ind = 1';
        $query = $this->db->query($sql);
        $result = $query->getResultObject();
       
            if (!empty($result)) {
                foreach( $result as $value){
                    $user_session_id  = $value->user_session_id;
                }
//  echo "<pre>";
//         print_r($user_session_id);
//         exit;
                return $user_session_id;
            } else {
                return false;
            }
    }


    public function findroles($role_id){
        $sql3 = 'SELECT * FROM '.$this->table .' where role_id = '.$role_id.'';
        // echo '<pre>';
        // print_r($sql3);
        // exit;
        $query = $this->db->query($sql3);
        $result = $query->getResultObject();
        return $result;
    }



    public function insert_data($data = array())
    {
        $this->db->table($this->table)->insert($data);
        return $this->db->insertID();
    }




    public function get_row($id = "") {
     
        $sql2 = 'SELECT u.* , r.role_name from admin_users as u LEFT JOIN admin_roles as r ON u.role_id = r.role_id where user_id =  '.$id.' ';
        $query = $this->db->query($sql2);
        $result = $query->getResultObject();
        return $result;
    //     echo '<pre>';
    // print_r($sql2);
    // exit;
    }

 
    
}
