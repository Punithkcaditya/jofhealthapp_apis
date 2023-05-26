<?php

namespace App\Models;
use Config\Database;
use CodeIgniter\Model;

class Guest extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'guest_list';
    protected $primaryKey       = 'guest_list_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'phone', 'Sangeet', 'Ladies_Mehendi', 'Tel_Baan', 'Baraat_Wedding_Reception', 'no_of_guest', 'guest_comment', 'created_at'];

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


    public function insertValue($data){
        $builder = $this->db->table("guest_list");
        return $builder->insert($data);
    }

}
