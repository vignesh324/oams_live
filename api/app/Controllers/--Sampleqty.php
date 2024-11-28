<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\StateModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;


class Sampleqty extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new StateModel();
        $data['state'] = $model->orderBy('id')->findAll();
        return $this->respond($data);
    }
    public function create()
    {
        $model = new StateModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|is_unique[state.name]',
            'code' => 'required|is_unique[state.code]|alpha_dash',
        ];

        if (!$this->validate($rules)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'name' => $this->request->getVar('name'),
                'code' => $this->request->getVar('code'),
                'created_by' => $session_user_id,
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
        $model = new StateModel();
        $data = $model->where('id', $id)->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $model = new StateModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required',
            'code' => 'required|alpha_dash',
        ];

        if (!$this->validate($rules)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'name' => $this->request->getVar('name'),
                'code' => $this->request->getVar('code'),
                'updated_by' => $session_user_id,
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
        $model = new StateModel();

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
