<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\InwardModel;
use App\Models\InwardItemModel;
use App\Models\InwardReturnModel;
use App\Models\ProductLogModel;
use App\Models\CenterModel;
use App\Models\SellerModel;
use App\Models\GardenModel;
use App\Models\WarehouseModel;
use App\Models\GradeModel;
use App\Models\StockModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use App\Helpers\ProductLog;

use Config\Services;


class Inwardreturn extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new InwardReturnModel();
        $inwards = $model->select('inward_return.*,inward_items.invoice_id AS inward_items_invoice_id,center.name AS center_name,seller.name AS seller_name,garden.name AS garden_name,warehouse.name AS warehouse_name,inward.quantity AS tot_qty,inward_items.total_gross AS gross_total, user.name AS user_name')
            ->join('inward_items', 'inward_items.id = inward_return.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_return.inward_id', 'left')
            ->join('center', 'center.id = inward.center_id', 'left')
            ->join('seller', 'seller.id = inward.seller_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('user', 'user.id = inward_return.created_by', 'left')
            ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
            ->findAll();

        $data['inwardreturn'] = $inwards;
        return $this->respond($data);
    }


    public function create()
    {
        $model = new InwardReturnModel();

        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'invoice_id' => 'required|numeric',
            'date' => 'required',
            'reason' => 'required',
            'return_quantity' => 'required|numeric' // Adding validation for return_quantity
        ];
        $messages = [
            "invoice_id" => [
                "required" => "Please Select Invoice no.",
                "numeric" => "Invoice no must be numeric."
            ],
            "return_quantity" => [
                "required" => "Return quantity is required.",
                "numeric" => "Return quantity must be numeric."
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {

            $inward_details = new InwardItemModel();
            $inward_details = $inward_details->where("id", $this->request->getVar('invoice_id'))->first();
            $return_quantity = $this->request->getVar('return_quantity');

            $stock_model = new StockModel();
            //echo $inward_details['id'];exit;
            $existing_stock = $stock_model->selectSum('qty')->where('inward_item_id', $inward_details['id'])->get();
            $result = $existing_stock->getResultArray();
            $sumQty = $result[0]['qty'];

            $data = [
                'date' => date("Y-m-d", strtotime($this->request->getVar('date'))),
                'inward_id' => $inward_details['inward_id'],
                'inward_item_id' => $inward_details['id'],
                'inward_invoice_no' => $this->request->getVar('invoice_id'),
                'reason' => $this->request->getVar('reason'),
                'return_quantity' => $this->request->getVar('return_quantity'),
                'created_by' => $this->request->getVar('created_by'),
                'status' => 1,
            ];
            session()->set('session_user_id', $this->request->getVar('created_by'));

            $model->insert($data);

            $result = ProductLog::logProductAction($inward_details['id'], 'Returned to Stock', $this->request->getVar('return_quantity'), $this->request->getVar('created_by'));

            if ($sumQty > 0)
                $no_of_bags = $sumQty - $return_quantity;
            else
                $no_of_bags = $inward_details['no_of_bags'] - $return_quantity;


            $return_status = ($no_of_bags != 0) ? 2 : 1;

            $id = $inward_details['id'];

            $data = [
                'return_status' => $return_status,
                'status' => 1,
            ];
            $model = new InwardItemModel();
            $model->update($id, $data);

            $model = new StockModel();
            $model->where('inward_item_id', $id)->set('qty', $no_of_bags)->update();
            //$model->where('inward_item_id', $id)->update(['qty' => $no_of_bags]);


            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Inserted Successfully',
            ];

            return $this->respondCreated($response);
        }
    }

    public function show($id = null)
    {
        $model = new InwardReturnModel();
        $inward_return = $model->select('inward_return.*, inward_items.*, grade.name AS grade_name, stock.qty AS stock_qty, user.name AS user_name')
            ->join('inward_items', 'inward_items.id = inward_return.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('stock', 'inward_items.id = stock.inward_item_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('user', 'user.id = inward_return.created_by', 'left')
            ->where('inward_return.id', $id)
            ->first();

        if ($inward_return) {
            $data = $inward_return;
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }

    public function delete($id = null)
    {
        $model = new InwardReturnModel();
        $data = $model->find($id);

        $inward_item_id = $data['inward_item_id'];
        $inward_item_qty = $data['return_quantity'];


        $model->delete($id);


        $stock_model = new StockModel();
        $existing_stock = $stock_model->selectSum('qty')->where('inward_item_id', $inward_item_id)->get();
        $result = $existing_stock->getResultArray();
        $sumQty = $result[0]['qty'];
        $no_of_bags = $sumQty + $inward_item_qty;

        $model = new StockModel();
        $model->where('inward_item_id', $inward_item_id)->set('qty', $no_of_bags)->update();

        $session_user_id = $this->request->getHeaderLine('Authorization1');

        $result = ProductLog::logProductAction($inward_item_id, 'Restored form Stocks', $inward_item_qty, $session_user_id);

        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Deleted Successfully'
        ];

        return $this->respondCreated($response);
    }
}
