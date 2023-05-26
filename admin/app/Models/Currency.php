<?php

namespace App\Models;
use Config\Database;
use CodeIgniter\Model;

class Currency extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'currency';
    protected $primaryKey       = 'currencyId';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['currencyId', 'currencyName', 'status', 'currencySymbol', 'defaultCurrency', 'sortOrder', 'created_at'];

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
        $this->table = 'currency';
        $this->primary_key = array();
        $this->data = array();
       
    }

}
