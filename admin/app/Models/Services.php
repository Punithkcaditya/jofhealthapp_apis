<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;


class Services extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'services';
    protected $primaryKey       = 'service_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['servicename', 'servicesdescription', 'service_heading1', 'servicescondition', 'service_plan_heading',   'service_heading2', 'subscriptionplane_img', 'services', 'button_heading' ,'duration', 'subscription_button_heading', 'Service_thumbnail', 'status_ind'];

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

    function __construct() {
        parent::__construct();
        $this->db = Database::connect();
        $this->table = 'services';
        $this->primary_key = array();
        $this->data = array();
       
    }
    
    public function get_row($id) {
    
        $this->db->table($this->table)->orderBy('ar.id' ,'DESC');
        $sql = 'SELECT ser.*,serdesc.service_description as service_descriptions  from '.$this->table . ' as ser LEFT JOIN subscriptionplandescs as serdesc ON ser.service_id = serdesc.service_id where ser.service_id = '.$id.' GROUP BY serdesc.service_description';
       // $sql = ' SELECT ar.*,u.locations_name as locations_name from '.$this->table . ' as ar LEFT JOIN locations as u ON ar.id = u.city_id where ar.status_ind = 1';
       $query = $this->db->query($sql);
       $result = $query->getResultArray();
       return $result;
   }
}
