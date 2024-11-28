<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\CityModel;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use App\Helpers\AutoDelete;
use App\Helpers\CodeHelper;

class City extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new CityModel();

        $data['city'] = $model->select('city.*, state.name as state_name')
            ->join('state', 'state.id = city.state_id','left')
            ->where('city.status !=',0)
            ->orderBy('city.name')
            ->findAll();

        return $this->respond($data);
    }

    public function create()
    {
        $model = new CityModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[city.name]',
            'state_id' => 'required|numeric',
        ];

        $messages = [
            "name" => [
                "required" => "The City Name field is required.",
                'regex_match' => 'The City Name field contains invalid characters.',
                'is_unique' => 'The City Name field must be unique.',
            ],
            "state_id" => [
                "required" => "Please select State.",
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
        $model = new CityModel();
        $data = $model->select('city.*, state.name as state_name')
            ->join('state', 'state.id = city.state_id','left')
            ->orderBy('city.name')
            ->where('city.id', $id)->where('city.status !=',0)->first();

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $model = new CityModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [ 
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[city.name,id,' . $id . ']',
            'state_id' => 'required|numeric',
        ];

        $messages = [
            "name" => [
                "required" => "The City Name field is required.",
                'regex_match' => 'The City Name field contains invalid characters.',
                'is_unique' => 'The City Name field must be unique.',
            ],
            "state_id" => [
                "required" => "Please select State.",
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'name' => $this->request->getVar('name'),
                'state_id' => $this->request->getVar('state_id'),
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

        $model = new CityModel();

        $data = $model->find($id);

        if ($data) {
            $model->update($id,['status' => 0, 'updated_by' => $session_user_id]);
            $result = AutoDelete::deleteRelations('city_id',$id);

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

    public function stateCity($id = null)
    {
        $model = new CityModel();

        $data['city'] = $model->select('city.id, city.name')
            ->where('city.state_id', $id)
            ->where('city.status',1)
            ->orderBy('city.name')
            ->findAll();


        if ($data['city']) {

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
                'data' => ['city' => []]
            ];
            return $this->respond($response);
        }
    }
}
