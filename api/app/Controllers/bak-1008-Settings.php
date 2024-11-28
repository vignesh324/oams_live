<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\SettingsModel;
use App\Models\CityModel;
use App\Models\AreaModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\AutoDelete;

class Settings extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new SettingsModel();
        $data['settings'] = $model->where('status', 1)->orderBy('id')->findAll();
        return $this->respond($data);
    }
    public function create()
    {
        // echo 'hii';exit;
        $model = new SettingsModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'delivery_time' => 'required|integer',
            'increment_amount' => 'required|numeric',
            'buyer_charges' => 'required|numeric',
            'seller_charges' => 'required|numeric',
            'leaf_sq' => 'required|numeric',
            'dust_sq' => 'required|numeric',
            'leaf_hsn' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\-]*$/]',
            'dust_hsn' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\-]*$/]',
        ];

        $messages = [
            'delivery_time' => [
                'required' => 'The Delivery Days field is required.',
                'integer' => 'The Delivery Days must be a number.',
            ],
            'increment_amount' => [
                'required' => 'The Increment Amount field is required.',
                'numeric' => 'The Increment Amount must be a number.',
            ],
            'buyer_charges' => [
                'required' => 'The Buyer Charges field is required.',
                'numeric' => 'The Buyer Charges must be a number.',
            ],
            'seller_charges' => [
                'required' => 'The Seller Charges field is required.',
                'numeric' => 'The Seller Charges must be a number.',
            ],
            'leaf_sq' => [
                'required' => 'The Leaf Sample Quantity field is required.',
                'numeric' => 'The Leaf Sample Quantity must be a number.',
            ],
            'dust_sq' => [
                'required' => 'The Dust Sample Quantity field is required.',
                'numeric' => 'The Dust Sample Quantity must be a number.',
            ],
            'leaf_hsn' => [
                'required' => 'The Leaf HSN field is required.',
                'numeric' => 'The Leaf HSN must be a number.',
            ],
            'dust_hsn' => [
                'required' => 'The Dust HSN field is required.',
                'numeric' => 'The Dust HSN must be a number.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'delivery_time' => $this->request->getVar('delivery_time'),
                'increment_amount' => $this->request->getVar('increment_amount'),
                'buyer_charges' => $this->request->getVar('buyer_charges'),
                'seller_charges' => $this->request->getVar('seller_charges'),
                'leaf_sq' => $this->request->getVar('leaf_sq'),
                'dust_sq' => $this->request->getVar('dust_sq'),
                'dust_hsn' => $this->request->getVar('dust_hsn'),
                'leaf_hsn' => $this->request->getVar('leaf_hsn'),
                'created_by' => $this->request->getVar('created_by'),
                'status' => 1,
                'buyer_show' => $this->request->getVar('buyer_show'),
            ];
            // print_r($data);exit;
            $model->insert($data);

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Inserted Successfully',
                'data' => $data
            ];

            return $this->respondCreated($response);
        }
    }


    public function show($id = null)
    {
        $model = new SettingsModel();
        $data = $model->where('status', 1)->orderBy('id', 'DESC')->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found');
        }
    }
}
