<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\InwardItemModel;
use App\Models\InwardModel;
use App\Models\StockModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;


class Warehousestock extends ResourceController
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
        $inwarditems = $model->select('inward_items.*,garden.name AS garden_name,grade.name AS grade_name,stock.qty AS stock_qty')
            ->join('stock', 'inward_items.id = stock.inward_item_id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('garden', 'inward.garden_id = garden.id', 'left')
            ->where('inward_items.status', 1)
            ->where('stock.qty !=', 0)
            ->orderBy('id')->findAll();
        $data['inwarditems'] = $inwarditems;
        return $this->respond($data);
    }

    public function show($id = null)
    {
        $model = new InwardItemModel();
        $inwardItem = $model->where('status', 3)->find($id);

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


    public function auctionstockbiddingsession()
    {
        $model = new StockModel();
        $inwarditems = $model->select('inward_items.*,grade.name AS grade_name,garden.name AS garden_name,warehouse.name AS warehouse_name,stock.qty AS stock_qty')
            ->join('inward_items', 'inward_items.id = stock.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('warehouse', 'warehouse.id = stock.warehouse_id', 'left')
            ->where('stock.qty !=', 0)->orderBy('id')->findAll();
        $data['stock'] = $inwarditems;
        return $this->respond($data);
    }
}
