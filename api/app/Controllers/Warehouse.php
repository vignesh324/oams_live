<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\WarehouseModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\AutoDelete;
use App\Helpers\CodeHelper;

class Warehouse extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new WarehouseModel();

        $data['warehouse'] = $model->select('warehouse.*, city.name as city_name, state.name as state_name, area.name as area_name')
            ->join('city', 'city.id = warehouse.city_id', 'left')
            ->join('state', 'state.id = warehouse.state_id', 'left')
            ->join('area', 'area.id = warehouse.area_id', 'left')
            ->where('warehouse.status !=',0)
            ->orderBy('warehouse.id')
            ->findAll();

        return $this->respond($data);
    }
    public function warehouseDropdown()
    {
        $model = new WarehouseModel();

        $data['warehouse'] = $model->select('warehouse.*, city.name as city_name, state.name as state_name, area.name as area_name')
            ->join('city', 'city.id = warehouse.city_id', 'left')
            ->join('state', 'state.id = warehouse.state_id', 'left')
            ->join('area', 'area.id = warehouse.area_id', 'left')
            ->where('warehouse.status',1)
            ->orderBy('warehouse.id')
            ->findAll();

        return $this->respond($data);
    }
    public function create()
    {
        $model = new WarehouseModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[warehouse.name]',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'area_id' => 'required|numeric',
            // 'gst_no' => 'required',
            // 'fssai_no' => 'required',
            'address' => 'required',
            // 'tea_board_no' => 'required',
        ];

        $messages = [
            "name" => [
                "required" => "The Warehouse Name field is required.",
                'regex_match' => 'The Warehouse Name field contains invalid characters.',
                "is_unique" => 'The Warehouse Name has already been taken.'
            ],
            "state_id" => [
                "required" => "The State field is required."
            ],
            "city_id" => [
                "required" => "The City field is required."
            ],
            "area_id" => [
                "required" => "The Area field is required."
            ],
            "gst_no" => [
                "required" => "The GST Number field is required."
            ],
            "fssai_no" => [
                "required" => "The FSSAI Number field is required."
            ],
            "address" => [
                "required" => "The Address field is required."
            ],
            "tea_board_no" => [
                "required" => "The Tea Board Number field is required."
            ]
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
                'gst_no' => $this->request->getVar('gst_no'),
                'fssai_no' => $this->request->getVar('fssai_no'),
                'address' => $this->request->getVar('address'),
                'tea_board_no' => $this->request->getVar('tea_board_no'),
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
        $model = new WarehouseModel();
        $data = $model->select('warehouse.*, city.name as city_name, state.name as state_name, area.name as area_name')
            ->join('city', 'city.id = warehouse.city_id', 'left')
            ->join('state', 'state.id = warehouse.state_id', 'left')
            ->join('area', 'area.id = warehouse.area_id', 'left')
            ->orderBy('warehouse.id')
            ->where('warehouse.id', $id)->where('warehouse.status !=',0)->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $model = new WarehouseModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[warehouse.name,id,' . $id . ']',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'area_id' => 'required|numeric',
            // 'gst_no' => 'required',
            // 'fssai_no' => 'required',
            'address' => 'required',
            // 'tea_board_no' => 'required',
        ];


        $messages = [
            "name" => [
                "required" => "The Warehouse Name field is required.",
                'regex_match' => 'The Warehouse Name field contains invalid characters.',
                "is_unique" => 'The Warehouse Name has already been taken.'
            ],
            "state_id" => [
                "required" => "The State field is required."
            ],
            "city_id" => [
                "required" => "The City field is required."
            ],
            "area_id" => [
                "required" => "The Area field is required."
            ],
            "gst_no" => [
                "required" => "The GST Number field is required."
            ],
            "fssai_no" => [
                "required" => "The FSSAI Number field is required."
            ],
            "address" => [
                "required" => "The Address field is required."
            ],
            "tea_board_no" => [
                "required" => "The Tea Board Number field is required."
            ]
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
                'gst_no' => $this->request->getVar('gst_no'),
                'fssai_no' => $this->request->getVar('fssai_no'),
                'address' => $this->request->getVar('address'),
                'tea_board_no' => $this->request->getVar('tea_board_no'),
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

        $model = new WarehouseModel();

        $data = $model->find($id);

        if ($data) {
            $model->update($id,['status' => 0, 'updated_by' => $session_user_id]);
            $result = AutoDelete::deleteRelations('warehouse_id',$id);
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
