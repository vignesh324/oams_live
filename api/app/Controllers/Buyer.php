<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\BuyerModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\AutoDelete;
use App\Helpers\CodeHelper;

class Buyer extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new BuyerModel();

        $data['buyer'] = $model->select('buyer.*, city.name as city_name, state.name as state_name, area.name as area_name')
            ->join('city', 'city.id = buyer.city_id', 'left')
            ->join('state', 'state.id = buyer.state_id', 'left')
            ->join('area', 'area.id = buyer.area_id', 'left')
            ->where('buyer.status !=',0)
            ->orderBy('buyer.id')
            ->findAll();

        return $this->respond($data);
    }
    public function buyerDropdown()
    {
        $model = new BuyerModel();

        $data['buyer'] = $model->select('buyer.*, city.name as city_name, state.name as state_name, area.name as area_name')
            ->join('city', 'city.id = buyer.city_id', 'left')
            ->join('state', 'state.id = buyer.state_id', 'left')
            ->join('area', 'area.id = buyer.area_id', 'left')
            ->where('buyer.status',1)
            ->orderBy('buyer.id')
            ->findAll();

        return $this->respond($data);
    }
    public function create()
    {
        $model = new BuyerModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[buyer.name]',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'area_id' => 'required|numeric',
            'gst_no' => 'required',
            'fssai_no' => 'required',
            'address' => 'required',
            'tea_board_no' => 'required',
            'email' => 'required|valid_email|is_unique[buyer.email]',
            'password' => 'required',
            'contact_person_name' => 'required',
            'contact_person_number' => 'required|numeric',
        ];

        $messages = [
            "name" => [
                "required" => "The Buyer Name field is required.",
                'is_unique' => 'The Buyer Name field must be unique.',
                'regex_match' => 'The Buyer Name field contains invalid characters.',
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
            ],
            "email" => [
                "required" => "The Email field is required.",
                'is_unique' => 'The Email field must be unique.',
                "valid_email" => "Please enter a valid Email address."
            ],
            "contact_person_name" => [
                "required" => "The Contact Person Name field is required."
            ],
            "contact_person_number" => [
                "required" => "The Contact Person Number field is required.",
                "numeric" => "The Contact Person Number must be a number."
            ],
            'charges' => [
                'required' => 'The charges field is required.',
            ],
        ];


        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $code = CodeHelper::generateUniqueCode($model, 'code');

            // echo $code;exit;
            $password = $this->request->getVar('password');
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $data = [
                'name' => $this->request->getVar('name'),
                'state_id' => $this->request->getVar('state_id'),
                'city_id' => $this->request->getVar('city_id'),
                'area_id' => $this->request->getVar('area_id'),
                'gst_no' => $this->request->getVar('gst_no'),
                'fssai_no' => $this->request->getVar('fssai_no'),
                'address' => $this->request->getVar('address'),
                'tea_board_no' => $this->request->getVar('tea_board_no'),
                'contact_person_name' => $this->request->getVar('contact_person_name'),
                'contact_person_number' => $this->request->getVar('contact_person_number'),
                'email' => $this->request->getVar('email'),
                'password' => $hashedPassword,
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
        $model = new BuyerModel();
        $data = $model->select('buyer.*, city.name as city_name, state.name as state_name, area.name as area_name')
            ->join('city', 'city.id = buyer.city_id', 'left')
            ->join('state', 'state.id = buyer.state_id', 'left')
            ->join('area', 'area.id = buyer.area_id', 'left')
            ->orderBy('buyer.id')
            ->where('buyer.id', $id)->where('buyer.status !=',0)->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $model = new BuyerModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[buyer.name,id,' . $id . ']',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'area_id' => 'required|numeric',
            'gst_no' => 'required',
            'fssai_no' => 'required',
            'address' => 'required',
            'tea_board_no' => 'required',
            'email' => 'required|valid_email|is_unique[buyer.email,id,' . $id . ']',
            'contact_person_name' => 'required',
            'contact_person_number' => 'required|numeric',
        ];

        $messages = [
            "name" => [
                "required" => "The Buyer Name field is required.",
                'is_unique' => 'The Buyer Name field must be unique.',
                'regex_match' => 'The Buyer Name field contains invalid characters.',
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
            ],
            "email" => [
                "required" => "The Email field is required.",
                'is_unique' => 'The Email field must be unique.',
                "valid_email" => "Please enter a valid Email address."
            ],
            "contact_person_name" => [
                "required" => "The Contact Person Name field is required."
            ],
            "contact_person_number" => [
                "required" => "The Contact Person Number field is required.",
                "numeric" => "The Contact Person Number must be a number."
            ],
            'charges' => [
                'required' => 'The charges field is required.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {

            $password = $this->request->getVar('password');

            $data = [
                'name' => $this->request->getVar('name'),
                'state_id' => $this->request->getVar('state_id'),
                'city_id' => $this->request->getVar('city_id'),
                'area_id' => $this->request->getVar('area_id'),
                'gst_no' => $this->request->getVar('gst_no'),
                'fssai_no' => $this->request->getVar('fssai_no'),
                'address' => $this->request->getVar('address'),
                'tea_board_no' => $this->request->getVar('tea_board_no'),
                'contact_person_name' => $this->request->getVar('contact_person_name'),
                'contact_person_number' => $this->request->getVar('contact_person_number'),
                'email' => $this->request->getVar('email'),
                'updated_by' => $this->request->getVar('updated_by'),
                'status' => $this->request->getVar('status'),
            ];

            if ($password != '')
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);

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

        $model = new BuyerModel();

        $data = $model->find($id);

        if ($data) {
            $model->update($id, ['status' => 0, 'updated_by' => $session_user_id]);
            $result = AutoDelete::deleteRelations('buyer_id', $id);

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
