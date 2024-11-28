<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\SampleQuantityModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;


class Samplequantity extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new SampleQuantityModel();
        $data['sampleQuantity'] = $model->where('status !=',0)->orderBy('id')->findAll();
        return $this->respond($data);
    }
    public function samplequantityDropdown()
    {
        $model = new SampleQuantityModel();
        $data['sampleQuantity'] = $model->where('status',1)->orderBy('id')->findAll();
        return $this->respond($data);
    }
    public function create()
    {
        $model = new SampleQuantityModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'quantity' => 'required|numeric',
        ];

        $messages = [
            "quantity" => [
                "required" => "Quantity is required.",
                "numeric" => "Quantity must be numeric."
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'quantity' => $this->request->getVar('quantity'),
                'created_by' => $this->request->getVar('created_by'),
                'status' => $this->request->getVar('status'),
            ];

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
        $model = new SampleQuantityModel();
        $data = $model->where('id', $id)->where('status !=',0)->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $model = new SampleQuantityModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'quantity' => 'required|numeric',
        ];

        $messages = [
            "quantity" => [
                "required" => "Quantity is required.",
                "numeric" => "Quantity must be numeric."
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'quantity' => $this->request->getVar('quantity'),
                'updated_by' => $this->request->getVar('updated_by'),
                'status' => $this->request->getVar('status'),
            ];

            $data1 = $model->find($id);

            if ($data1) {
                $model->update($id, $data);

                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => 'Updated Successfully',
                    'data' => $data
                ];

                return $this->respond($response);
            } else {
                return $this->failNotFound('No Data Found with id : ' . $id);
            }
        }
    }
    public function delete($id = null)
    {
        $model = new SampleQuantityModel();

        $data = $model->find($id);

        if ($data) {
            $model->update($id,['status' => 0]);

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
