<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\InwardItemModel;
use App\Models\InwardModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;


class warehousearchivestock extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new InwardItemModel();
        $inwarditems = $model->where('status', 4)->orderBy('id')->findAll();

        foreach ($inwarditems as &$inwarditem) {
            $inwarditem['inward'] = (new InwardModel())->where('id', $inwarditem['inward_id'])->findAll();
        }
        $data['inwarditems'] = $inwarditems;
        return $this->respond($data);
    }

    public function show($id = null)
    {
        $model = new InwardItemModel();
        $inwardItem = $model->where('status', 4)->find($id);

        if ($inwardItem) {
            // Load related inward details
            $inwardModel = new InwardModel();
            $inwardDetails = $inwardModel->find($inwardItem['inward_id']);

            if ($inwardDetails) {
                $inwardItem['inward'] = $inwardDetails;
            }

            $data['inwarditem'] = $inwardItem;
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }


}
