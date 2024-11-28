<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\InwardModel;
use App\Models\InwardItemModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;


class inward extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new InwardModel();
        $inwards = $model->where('status',1)->findAll();
        echo '<pre>';print_r($inwards);exit;

        foreach ($inwards as $inward) {
            $inward['inward_tems'] = $model->join('inward_items', 'inward_items.inward_id = inward.id', 'left')->findAll();
        }
        $data['inward'] = $inwards;
        return $this->respond($data);
    }
    public function create()
    {
        $model = new InwardModel();
        $inwardItemModel = new InwardItemModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'center_id' => 'required|numeric',
            'seller_id' => 'required|numeric',
            'garden_id' => 'required|numeric',
            'warehouse_id' => 'required|numeric',
            'gp_no' => 'required',
            'gp_date' => 'required|valid_date',
            'arrival_date' => 'required|valid_date',
            'quantity' => 'required|numeric',
        ];

        $inwarditemsrules = [
            'invoice_id' => 'required|numeric',
            'grade_id' => 'required|numeric',
            'bag_type' => 'required',
            'sno_from' => 'required',
            'sno_to' => 'required',
            'weight_net' => 'required|numeric',
            'weight_tare' => 'required|numeric',
            'weight_gross' => 'required|numeric',
            'total_net' => 'required|numeric',
            'total_tare' => 'required|numeric',
            'total_gross' => 'required|numeric',
        ];

        $messages = [
            'center_id.required' => 'Center ID is required.',
            'center_id.numeric' => 'Center ID must be numeric.',
            'seller_id.required' => 'Seller ID is required.',
            'seller_id.numeric' => 'Seller ID must be numeric.',
            'garden_id.required' => 'Garden ID is required.',
            'garden_id.numeric' => 'Garden ID must be numeric.',
            'warehouse_id.required' => 'Warehouse ID is required.',
            'warehouse_id.numeric' => 'Warehouse ID must be numeric.',
            'gp_no.required' => 'GP Number is required.',
            'gp_date.required' => 'GP Date is required.',
            'gp_date.valid_date' => 'GP Date must be a valid date.',
            'arrival_date.required' => 'Arrival Date is required.',
            'arrival_date.valid_date' => 'Arrival Date must be a valid date.',
            'quantity.required' => 'Quantity is required.',
            'quantity.numeric' => 'Quantity must be numeric.',
        ];

        $inwarditemsMessages = [
            'invoice_id.required' => 'Invoice ID is required.',
            'invoice_id.numeric' => 'Invoice ID must be numeric.',
            'grade_id.required' => 'Grade ID is required.',
            'grade_id.numeric' => 'Grade ID must be numeric.',
            'bag_type.required' => 'Bag type is required.',
            'sno_from.required' => 'Sno From is required.',
            'sno_to.required' => 'Sno To is required.',
            'weight_net.required' => 'Net weight is required.',
            'weight_net.numeric' => 'Net weight must be numeric.',
            'weight_tare.required' => 'Tare weight is required.',
            'weight_tare.numeric' => 'Tare weight must be numeric.',
            'weight_gross.required' => 'Gross weight is required.',
            'weight_gross.numeric' => 'Gross weight must be numeric.',
            'total_net.required' => 'Total Net weight is required.',
            'total_net.numeric' => 'Total Net weight must be numeric.',
            'total_tare.required' => 'Total Tare weight is required.',
            'total_tare.numeric' => 'Total Tare weight must be numeric.',
            'total_gross.required' => 'Total Gross weight is required.',
            'total_gross.numeric' => 'Total Gross weight must be numeric.',
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'center_id' => $this->request->getVar('center_id'),
                'seller_id' => $this->request->getVar('seller_id'),
                'garden_id' => $this->request->getVar('garden_id'),
                'warehouse_id' => $this->request->getVar('warehouse_id'),
                'gp_no' => $this->request->getVar('gp_no'),
                'gp_date' => $this->request->getVar('gp_date'),
                'arrival_date' => $this->request->getVar('arrival_date'),
                'quantity' => $this->request->getVar('quantity'),
                'created_by' => $session_user_id,
                'status' => 1,
            ];

            $model->insert($data);
            $inwardId = $model->getInsertID(); // Get the last inserted ID

            // Now insert into the inward_items table
            if (!$this->validate($inwarditemsrules, $inwarditemsMessages)) {
                $validationErrors = $this->validator->getErrors();
                return $this->fail($validationErrors, 422); // Bad request
            } else {
                $inwardItemData = [
                    'invoice_id' => $this->request->getVar('invoice_id'),
                    'inward_id' => $inwardId,
                    'grade_id' => $this->request->getVar('grade_id'),
                    'bag_type' => $this->request->getVar('bag_type'),
                    'sno_from' => $this->request->getVar('sno_from'),
                    'sno_to' => $this->request->getVar('sno_to'),
                    'weight_net' => $this->request->getVar('weight_net'),
                    'weight_tare' => $this->request->getVar('weight_tare'),
                    'weight_gross' => $this->request->getVar('weight_gross'),
                    'total_net' => $this->request->getVar('total_net'),
                    'total_tare' => $this->request->getVar('total_tare'),
                    'total_gross' => $this->request->getVar('total_gross'),
                    'created_by' => $session_user_id,
                    'status' => 1,
                ];

                $inwardItemModel->insert($inwardItemData);

                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => 'Inserted Successfully',
                    'data' => $data,
                    'inward_data' => $inwardItemData
                ];

                return $this->respondCreated($response);
            }
        }
    }

    public function show($id = null)
    {
        $model = new InwardModel();
        $inward = $model->find($id);

        if ($inward) {
            $inwardItemModel = new InwardItemModel();
            $inwardItems = $inwardItemModel->where('inward_id', $inward['id'])->findAll();

            $inward['inwardItems'] = $inwardItems;

            $data['inward'] = $inward;
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }

    public function update($id = null)
    {
        $model = new InwardModel();
        $inwardItemModel = new InwardItemModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'center_id' => 'required|numeric',
            'seller_id' => 'required|numeric',
            'garden_id' => 'required|numeric',
            'warehouse_id' => 'required|numeric',
            'gp_no' => 'required',
            'gp_date' => 'required|valid_date',
            'arrival_date' => 'required|valid_date',
            'quantity' => 'required|numeric',
        ];

        $inwarditemsrules = [
            'invoice_id' => 'required|numeric',
            'grade_id' => 'required|numeric',
            'bag_type' => 'required',
            'sno_from' => 'required',
            'sno_to' => 'required',
            'weight_net' => 'required|numeric',
            'weight_tare' => 'required|numeric',
            'weight_gross' => 'required|numeric',
            'total_net' => 'required|numeric',
            'total_tare' => 'required|numeric',
            'total_gross' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'center_id' => $this->request->getVar('center_id'),
                'seller_id' => $this->request->getVar('seller_id'),
                'garden_id' => $this->request->getVar('garden_id'),
                'warehouse_id' => $this->request->getVar('warehouse_id'),
                'gp_no' => $this->request->getVar('gp_no'),
                'gp_date' => $this->request->getVar('gp_date'),
                'arrival_date' => $this->request->getVar('arrival_date'),
                'quantity' => $this->request->getVar('quantity'),
                'updated_by' => $session_user_id,
                'status' => 1,
            ];

            $model->update($id, $data);


            // Now insert into the inward_items table
            if (!$this->validate($inwarditemsrules)) {
                $validationErrors = $this->validator->getErrors();
                return $this->fail($validationErrors, 422); // Bad request
            } else {
                $inwardItemData = [
                    'invoice_id' => $this->request->getVar('invoice_id'),
                    'grade_id' => $this->request->getVar('grade_id'),
                    'bag_type' => $this->request->getVar('bag_type'),
                    'sno_from' => $this->request->getVar('sno_from'),
                    'sno_to' => $this->request->getVar('sno_to'),
                    'weight_net' => $this->request->getVar('weight_net'),
                    'weight_tare' => $this->request->getVar('weight_tare'),
                    'weight_gross' => $this->request->getVar('weight_gross'),
                    'total_net' => $this->request->getVar('total_net'),
                    'total_tare' => $this->request->getVar('total_tare'),
                    'total_gross' => $this->request->getVar('total_gross'),
                    'updated_by' => $session_user_id,
                    'status' => 1,
                ];

                $inwardItemModel->update($id, $inwardItemData);

                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => 'Updated Successfully',
                    'data' => $data,
                    'inward_data' => $inwardItemData
                ];

                return $this->respond($response);
            }
        }
    }
    public function delete($id = null)
    {
        $model = new InwardModel();

        $data = $model->find($id);

        if ($data) {
            $model->delete($id);

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Deleted Successfully',
                'data' => $data
            ];

            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
}
