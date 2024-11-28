<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\SellerModel;
use App\Models\GardenModel;
use Applications\USER\Controllers\Garden;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\AutoDelete;
use App\Helpers\CodeHelper;

class Seller extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new SellerModel();

        $data['seller'] = $model->select('seller.*, city.name as city_name, state.name as state_name, area.name as area_name')
            ->join('city', 'city.id = seller.city_id', 'left')
            ->join('state', 'state.id = seller.state_id', 'left')
            ->join('area', 'area.id = seller.area_id', 'left')
            ->where('seller.status !=',0)
            ->orderBy('seller.id')
            ->findAll();

        return $this->respond($data);
    }
    public function sellerDropdown()
    {
        $model = new SellerModel();

        $data['seller'] = $model->select('seller.*, city.name as city_name, state.name as state_name, area.name as area_name')
            ->join('city', 'city.id = seller.city_id', 'left')
            ->join('state', 'state.id = seller.state_id', 'left')
            ->join('area', 'area.id = seller.area_id', 'left')
            ->where('seller.status',1)
            ->orderBy('seller.id')
            ->findAll();

        return $this->respond($data);
    }
    public function create()
    {
        $model = new SellerModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[seller.name]',
            'seller_prefix' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s\-\/_]*$/]|is_unique[seller.seller_prefix]',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'area_id' => 'required|numeric',
            'gst_no' => 'required',
            'fssai_no' => 'required',
            'tea_board_no' => 'required',
            'address' => 'required',
        ];

        $messages = [
            'name' => [
                'required' => 'The Seller Name field is required.',
                'regex_match' => 'The Seller Name field contains invalid characters.',
                'is_unique' => 'The Seller Name has already been taken.',
            ],
            'seller_prefix' => [
                'required' => 'The Seller Prefix field is required.',
                'regex_match' => 'The Seller Prefix field contains invalid characters.',
                'is_unique' => 'The Seller Prefix has already been taken.',
            ],
            'state_id' => [
                'required' => 'The State field is required.',
                'numeric' => 'The State must be a number.',
            ],
            "tea_board_no" => [
                "required" => "The Tea Board Number field is required."
            ],
            'city_id' => [
                'required' => 'The City field is required.',
                'numeric' => 'The City must be a number.',
            ],
            'area_id' => [
                'required' => 'The Area field is required.',
                'numeric' => 'The Area must be a number.',
            ],
            'gst_no' => [
                'required' => 'The GST Number field is required.',
            ],
            'fssai_no' => [
                'required' => 'The FSSAI number field is required.',
            ],
            'address' => [
                'required' => 'The address field is required.',
            ],
            'charges' => [
                'required' => 'The charges field is required.',
            ],
        ];        

        if (!$this->validate($rules,$messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $code = CodeHelper::generateUniqueCode($model, 'code');

            // echo $code;exit;
            $data = [
                'name' => $this->request->getVar('name'),
                'seller_prefix' => $this->request->getVar('seller_prefix'),
                'state_id' => $this->request->getVar('state_id'),
                'city_id' => $this->request->getVar('city_id'),
                'area_id' => $this->request->getVar('area_id'),
                'gst_no' => $this->request->getVar('gst_no'),
                'fssai_no' => $this->request->getVar('fssai_no'),
                'tea_board_no' => $this->request->getVar('tea_board_no'),
                'address' => $this->request->getVar('address'),
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
        $model = new SellerModel();
        $data = $model->select('seller.*, city.name as city_name, state.name as state_name, area.name as area_name')
            ->join('city', 'city.id = seller.city_id', 'left')
            ->join('state', 'state.id = seller.state_id', 'left')
            ->join('area', 'area.id = seller.area_id', 'left')
            ->where('seller.status !=',0)
            ->orderBy('seller.id')
            ->where('seller.id', $id)->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $model = new SellerModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[seller.name,id,' . $id . ']',
            'seller_prefix' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s\-\/_]*$/]|is_unique[seller.seller_prefix,id,' . $id . ']',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'area_id' => 'required|numeric',
            'gst_no' => 'required',
            'fssai_no' => 'required',
            'tea_board_no' => 'required',
            'address' => 'required',
        ];

        $messages = [
            'name' => [
                'required' => 'The Seller Name field is required.',
                'regex_match' => 'The Seller Name field contains invalid characters.',
                'is_unique' => 'The Seller Name has already been taken.',
            ],
            'seller_prefix' => [
                'required' => 'The Seller Prefix field is required.',
                'regex_match' => 'The Seller Prefix field contains invalid characters.',
                'is_unique' => 'The Seller Prefix has already been taken.',
            ],
            "tea_board_no" => [
                "required" => "The Tea Board Number field is required."
            ],
            'state_id' => [
                'required' => 'The State field is required.',
                'numeric' => 'The State must be a number.',
            ],
            'city_id' => [
                'required' => 'The City field is required.',
                'numeric' => 'The City must be a number.',
            ],
            'area_id' => [
                'required' => 'The Area field is required.',
                'numeric' => 'The Area must be a number.',
            ],
            'gst_no' => [
                'required' => 'The GST Number field is required.',
            ],
            'fssai_no' => [
                'required' => 'The FSSAI number field is required.',
            ],
            'address' => [
                'required' => 'The address field is required.',
            ],
            'charges' => [
                'required' => 'The charges field is required.',
            ],
        ];        

        if (!$this->validate($rules,$messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'name' => $this->request->getVar('name'),
                'seller_prefix' => $this->request->getVar('seller_prefix'),
                'state_id' => $this->request->getVar('state_id'),
                'city_id' => $this->request->getVar('city_id'),
                'area_id' => $this->request->getVar('area_id'),
                'gst_no' => $this->request->getVar('gst_no'),
                'fssai_no' => $this->request->getVar('fssai_no'),
                'tea_board_no' => $this->request->getVar('tea_board_no'),
                'address' => $this->request->getVar('address'),
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

        $model = new SellerModel();

        $data = $model->find($id);

        if ($data) {
            $model->update($id,['status' => 0, 'updated_by' => $session_user_id]);
            $result = AutoDelete::deleteRelations('seller_id',$id);

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

    public function sellergarden($id = null)
    {
        $model = new GardenModel();

        $data['sellerGarden'] = $model->select('garden.id,garden.name,garden.vacumm_bag')
            ->where('garden.seller_id', $id)
            ->where('garden.status', 1)
            ->findAll();


        if ($data['sellerGarden']) {

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Success',
                'data' => $data
            ];

            // print_r($response);exit;

            return $this->respond($response);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
}
