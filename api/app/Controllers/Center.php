<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\CenterModel;
use App\Models\CenterGardenModel;
use App\Models\GardenModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\AutoDelete;
use App\Helpers\CodeHelper;

class Center extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new CenterModel();

        // Join the CenterModel with the city, state, and area tables
        $data['center'] = $model->select('center.*, city.name as city_name, state.name as state_name, area.name as area_name')
            ->join('city', 'city.id = center.city_id', 'left')
            ->join('state', 'state.id = center.state_id', 'left')
            ->join('area', 'area.id = center.area_id', 'left')
            ->where('center.status !=',0)
            ->orderBy('center.id')
            ->findAll();

        return $this->respond($data);
    }
    public function centerDropdown()
    {
        $model = new CenterModel();

        // Join the CenterModel with the city, state, and area tables
        $data['center'] = $model->select('center.*, city.name as city_name, state.name as state_name, area.name as area_name')
            ->join('city', 'city.id = center.city_id', 'left')
            ->join('state', 'state.id = center.state_id', 'left')
            ->join('area', 'area.id = center.area_id', 'left')
            ->where('center.status',1)
            ->orderBy('center.id')
            ->findAll();

        return $this->respond($data);
    }
    public function create()
    {
        $model = new CenterModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[center.name]',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'area_id' => 'required|numeric',
        ];

        $messages = [
            "name" => [
                "required" => "The Center Name field is required.",
                'regex_match' => 'The Center Name field contains invalid characters.',
                'is_unique' => 'The Center Name field must be unique.',
            ],
            "state_id" => [
                "required" => "The State field is required.",
            ],
            "city_id" => [
                "required" => "The City field is required.",
            ],
            "area_id" => [
                "required" => "The Area field is required.",
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
                'area_id' => $this->request->getVar('area_id'),
                'created_by' => $this->request->getVar('created_by'),
                'status' => $this->request->getVar('status'),
            ];

            
            $model->insert($data);

            $centerId = $model->getInsertID();

            $model = new GardenModel();
            $garden = $model->where('status', 1)->findAll();
            foreach ($garden as $key => $value) {
                $model = new CenterGardenModel();
                $datas = [];
                $datas = [
                    'center_id' => $centerId,
                    'garden_id' => $value['id'],
                    'order_seq' => $key + 1,
                ];
                $model->insert($datas);
            }

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
        $model = new CenterModel();
        $data = $model->select('center.*, city.name as city_name, state.name as state_name, area.name as area_name')
            ->join('city', 'city.id = center.city_id', 'left')
            ->join('state', 'state.id = center.state_id', 'left')
            ->join('area', 'area.id = center.area_id', 'left')
            ->orderBy('center.id')
            ->where('center.status !=',0)
            ->where('center.id', $id)->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $model = new CenterModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[center.name,id,' . $id . ']',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'area_id' => 'required|numeric',
        ];

        $messages = [
            "name" => [
                "required" => "The Center Name field is required.",
                'regex_match' => 'The Center Name field contains invalid characters.',
                'is_unique' => 'The Center Name field must be unique.',
            ],
            "state_id" => [
                "required" => "The State field is required.",
            ],
            "city_id" => [
                "required" => "The City field is required.",
            ],
            "area_id" => [
                "required" => "The Area field is required.",
            ],
        ];
        

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'name' => $this->request->getVar('name'),
                'state_id' => $this->request->getVar('state_id'),
                'city_id' => $this->request->getVar('city_id'),
                'area_id' => $this->request->getVar('area_id'),
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

        $model = new CenterModel();

        $data = $model->find($id);

        if ($data) {
            $model->update($id,['status' => 0, 'updated_by' => $session_user_id]);
            

            $model = new CenterGardenModel();
            $model->where('center_id', $id)->delete();

            $result = AutoDelete::deleteRelations('center_id',$id);

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

    public function assignGarden()
    {
        $model = new CenterGardenModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'center_id' => 'required|numeric',
            'garden_id' => 'required',
            'order_seq' => 'required',
        ];

        $center_id = $this->request->getVar('center_id');
        $garden_ids = $this->request->getVar('garden_id');
        $order_seqs = $this->request->getVar('order_seq');

        // Check if the center ID already exists
        $existingData = $model->where('center_id', $center_id)->where('status',1)->findAll();

        // echo '<pre>';print_r($existingData);exit;

        if ($existingData) {
            $existingresponse = [
                'status' => 201,
                'error' => false,
                'message' => 'Already exists',
                'data' => $existingData
            ];
            return $this->respond($existingresponse);
        }

        if (!$this->validate($rules)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $insertData = [];

            foreach ($garden_ids as $key => $garden_id) {
                $data = [
                    'center_id' => $center_id,
                    'garden_id' => $garden_id,
                    'order_seq' => $order_seqs[$key],
                ];

                $model->insert($data);

                $insertData[] = $data;
            }

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Inserted Successfully',
                'data' => $insertData
            ];

            return $this->respondCreated($response);
        }
    }


    public function showGardensByCenter($id = null)
    {
        $model = new CenterGardenModel();

        $assignedGardens = $model->where('center_id', $id)->findAll();

        // print_r($assignedGardens);exit;

        if (empty($assignedGardens)) {
            return $this->failNotFound('No gardens found for the specified center ID.');
        }

        $response = [
            'status' => 200,
            'data' => $assignedGardens
        ];

        return $this->respond($response);
    }



    public function centergardenlist()
    {
        $model = new CenterGardenModel();
       
        $data['center_garden'] = $model->select('center_garden.*, center.name as center_name,city.name as city_name, state.name as state_name, area.name as area_name, garden.name as garden_name')
            ->join('center', 'center.id = center_garden.center_id', 'left')
            ->join('city', 'city.id = center.city_id', 'left')
            ->join('state', 'state.id = center.state_id', 'left')
            ->join('area', 'area.id = center.area_id', 'left')
            ->join('garden', 'garden.id = center_garden.garden_id', 'left')
            ->where('center_garden.status',1)
            ->orderBy('center_garden.id')
            ->findAll();

        return $this->respond($data);
    }

    public function assignCenterGardenlist($id)
    {
        $model = new CenterGardenModel();

        $data['center_garden'] = $model->select('center_garden.*, center.name as center_name, garden.name as garden_name')
            ->join('center', 'center.id = center_garden.center_id', 'left')
            ->join('garden', 'garden.id = center_garden.garden_id', 'left')
            ->where('center_garden.center_id', $id)
            ->orderBy('center_garden.order_seq')
            ->findAll();

        return $this->respond($data);
    }

    public function reOrderGarden()
    {
        $input_data = file_get_contents("php://input");

        // Decode the JSON data
        $requestData = json_decode($input_data, true);

        foreach ($requestData as $item) {

            $sequence = $item['sequence'];
            $id = $item['id'];
            // $center_id = $item['center_id'];

            $model = new CenterGardenModel();
            $data = ['order_seq' => $sequence];


            $model->update($id, $data);
        }

        return $this->respond($data);
    }
}
