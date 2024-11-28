<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\ModuleModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;


class Modules extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new ModuleModel();
        $data['modules'] = $model->where('status !=',0)->orderBy('id')->findAll();
        return $this->respond($data);
    }
    public function create()
    {
        $model = new ModuleModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required'
        ];

        if (!$this->validate($rules)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 400); // Bad request
        } else {
            $data = [
                'name' => $this->request->getVar('name'),
                'code' => $this->request->getVar('code'),
                'state_id' => $this->request->getVar('state_id'),
                'city_id' => $this->request->getVar('city_id'),
                'created_by' => $this->request->getVar('session_user_id'),
                'status' => 1,
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
        $model = new ModuleModel();
        $data = $model->where('id', $id)->where('status !=',0)->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $model = new ModuleModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required',
            'code' => 'required',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 400); // Bad request
        } else {
            $data = [
                'name' => $this->request->getVar('name'),
                'code' => $this->request->getVar('code'),
                'state_id' => $this->request->getVar('state_id'),
                'city_id' => $this->request->getVar('city_id'),
                'updated_by' => $this->request->getVar('session_user_id'),
                'status' => 1,
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
        $model = new ModuleModel();

        $data = $model->find($id);

        if ($data) {
            $model->delete($id);

            $response = [
                'message' => 'Deleted Successfully'
            ];

            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
}
