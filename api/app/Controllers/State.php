<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\StateModel;
use App\Models\CityModel;
use App\Models\AreaModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\AutoDelete;
use App\Helpers\CodeHelper;

class State extends ResourceController
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
        $data['state'] = $model->where('status !=', 0)->orderBy('name')->findAll();
        return $this->respond($data);
    }
    public function create()
    {
        $model = new StateModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[state.name]',
        ];

        $messages = [
            'name' => [
                'required' => 'The State Name field is required.',
                'regex_match' => 'The State Name field contains invalid characters.',
                'is_unique' => 'The State Name field must be unique.',
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
        $model = new StateModel();
        $data = $model->where('id', $id)->where('status !=', 0)->orderBy('name')
            ->first();
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
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[state.name,id,' . $id . ']',
        ];

        $messages = [
            'name' => [
                'required' => 'The State Name field is required.',
                'regex_match' => 'The State Name field contains invalid characters.',
                'is_unique' => 'The State Name field must be unique.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'name' => $this->request->getVar('name'),
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

        $model = new StateModel();

        $data = $model->find($id);

        if ($data) {
            $model->update($id, ['status' => 0, 'updated_by' => $session_user_id]);
            $result = AutoDelete::deleteRelations('state_id', $id);
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

    public function stateCityArea()
    {
        $state_model = new StateModel();
        $city_model = new CityModel();
        $area_model = new AreaModel();

        $data['state'] = $state_model
            ->select('id, name')
            ->where('status', 1)
            ->orderBy('name')
            ->findAll();

        foreach ($data['state'] as &$state) {
            $state['city'] = $city_model
                ->select('id, name')
                ->where('status', 1)
                ->where('state_id', $state['id'])
                ->orderBy('name')
                ->findAll();

            foreach ($state['city'] as &$city) {
                $city['area'] = $area_model
                    ->select('id, name')
                    ->where('status', 1)
                    ->where('city_id', $city['id'])
                    ->orderBy('name')
                    ->findAll();
            }
        }

        // echo '<pre>';print_r($data);exit;
        return $this->respond($data);
    }

    public function stateDropdown()
    {
        $model = new StateModel();
        $data['state'] = $model->where('status', 1)->orderBy('name')->findAll();
        return $this->respond($data);
    }
}
