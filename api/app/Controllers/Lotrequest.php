<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\LotrequestModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\AutoDelete;
use App\Helpers\CodeHelper;

class Lotrequest extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new LotrequestModel();
        $data['hsn'] = $model->where('hsn.status !=',0)->orderBy('id')->findAll();
        return $this->respond($data);
    }
    public function create()
    {
        $model = new LotrequestModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[hsn.name]',
        ];

        $messages = [
            "name" => [
                "required" => "The Hsn Name field is required.",
                'regex_match' => 'The Hsn Name field contains invalid characters.',
                'is_unique' => 'The Hsn Name field must be unique.',
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
        $model = new LotrequestModel();
        $data = $model->where('id', $id)->where('hsn.status !=',0)->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $model = new LotrequestModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[hsn.name,id,' . $id . ']',
        ];

        $messages = [
            "name" => [
                "required" => "The Hsn Name field is required.",
                'regex_match' => 'The Hsn Name field contains invalid characters.',
                'is_unique' => 'The Hsn Name field must be unique.',
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

        $model = new LotrequestModel();

        $data = $model->find($id);

        if ($data) {
            $model->update($id,['status' => 0, 'updated_by' => $session_user_id]);
            //$result = AutoDelete::deleteRelations('hsn_id',$id);

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
