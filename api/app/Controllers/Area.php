<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\AreaModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\AutoDelete;
use App\Helpers\CodeHelper;


class Area extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new AreaModel();

        $data['area'] = $model->select('area.*, city.name as city_name, state.name as state_name')
            ->join('city', 'city.id = area.city_id', 'left')
            ->join('state', 'state.id = area.state_id', 'left')
            ->where('area.status !=',0)
            ->orderBy('area.name')
            ->findAll();

        return $this->respond($data);
    }

    public function create()
    {
        $model = new AreaModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[area.name]',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
        ];

        $messages = [
            "name" => [
                "required" => "The Area Name field is required.",
                'regex_match' => 'The Area Name field contains invalid characters.',
                'is_unique' => 'The Area Name field must be unique.',
            ],
            "state_id" => [
                "required" => "Please select State.",
            ],
            "city_id" => [
                "required" => "Please select City.",
            ],
        ];        

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $code = CodeHelper::generateUniqueCode($model, 'code');

            // echo $code;exit;
            $data = [
                'name' => $this->request->getVar('name'),
                'state_id' => $this->request->getVar('state_id'),
                'city_id' => $this->request->getVar('city_id'),
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
        $model = new AreaModel();
        $data = $model->select('area.*, city.name as city_name, state.name as state_name')
            ->join('city', 'city.id = area.city_id', 'left')
            ->join('state', 'state.id = area.state_id', 'left')
            ->where('area.status !=',0)
            ->orderBy('area.name')
            ->where('area.id', $id)->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $model = new AreaModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[area.name,id,' . $id . ']',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
        ];

        $messages = [
            "name" => [
                "required" => "The Area Name field is required.",
                'regex_match' => 'The Area Name field contains invalid characters.',
                'is_unique' => 'The Area Name field must be unique.',
            ],
            "state_id" => [
                "required" => "Please select State.",
            ],
            "city_id" => [
                "required" => "Please select City.",
            ],
        ];             

        if (!$this->validate($rules,$messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'name' => $this->request->getVar('name'),
                'state_id' => $this->request->getVar('state_id'),
                'city_id' => $this->request->getVar('city_id'),
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
        $session_user_id = $this->request->getHeaderLine('Authorization1');

        $model = new AreaModel();

        $data = $model->find($id);

        if ($data) {
            $model->update($id,['status' => 0, 'updated_by' => $session_user_id]);
            $result = AutoDelete::deleteRelations('area_id',$id);

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

    public function cityArea($id = null)
    {
        $model = new AreaModel();

        $data['area'] = $model->select('area.id, area.name')
            ->where('area.city_id', $id)
            ->where('area.status', 1)
            ->orderBy('area.name')
            ->findAll();

        if ($data['area']) {

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Success',
                'data' => $data
            ];

            // print_r($response);exit;

            return $this->respond($response);
        } else {
            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Success',
                'data' => ['area' => []]
            ];
            return $this->respond($response);
        }
    }
}
