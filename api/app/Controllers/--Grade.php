<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\GradeModel;
use App\Models\GardenGradeModel;
use App\Models\GardenModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\AutoDelete;

class Grade extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new GradeModel();
    
        $data['grade'] = $model->select('grade.*, category.name as category_name')
            ->join('category', 'category.id = grade.category_id', 'left') 
            ->where('grade.status',1)
            ->orderBy('grade.id', 'ASC')
            ->findAll();
    
        return $this->respond($data);
    }
    

    public function create()
    {
        $model = new GradeModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[grade.name]',
            'code' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\-]*$/]|is_unique[grade.code]',
            'type' => 'required',
            'category_id' => 'required',
        ];

        $messages = [
            'name' => [
                'required' => 'The Grade Name field is required.',
                'regex_match' => 'The Grade Name field contains invalid characters.',
                'is_unique' => 'The Grade Name field must be unique.',
            ],
            'code' => [
                'required' => 'The Code field is required.',
                'regex_match' => 'The Code field contains invalid characters.',
                'is_unique' => 'The Code field must be unique.',
            ],
            'type' => [
                'required' => 'Please select Grade Type.',
            ],
            'category_id' => [
                'required' => 'Please select Category.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'name' => $this->request->getVar('name'),
                'code' => $this->request->getVar('code'),
                'type' => $this->request->getVar('type'),
                'category_id' => $this->request->getVar('category_id'),
                'created_by' => $session_user_id,
                'status' => 1,
            ];

            $model->insert($data);

            $grade_id = $model->getInsertID();

            $garden_model = new GardenModel();
            $garden_details  = $garden_model->where('status', 1)->findAll();
            if (count($garden_details)) {
                foreach ($garden_details as $key => $value) {
                    $garden_grade = new GardenGradeModel();
                    $gradegarden_details = $garden_grade->where('garden_id', $value['id'])->orderBy('order_seq', 'DESC')->first();
                    $newOrderSeq = $gradegarden_details['order_seq'] + 1;

                    $data = [
                        'garden_id' => $value['id'],
                        'grade_id' => $grade_id,
                        'order_seq' => $newOrderSeq
                    ];
                    $garden_grade->insert($data);
                }
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
        $model = new GradeModel();
        $data = $model->where('id', $id)->where('grade.status',1)->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    
    public function update($id = null)
    {
        $model = new GradeModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[grade.name,id,' . $id . ']',
            'code' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\-]*$/]|is_unique[grade.code,id,' . $id . ']',
            'type' => 'required',
            'category_id' => 'required',
        ];

        $messages = [
            'name' => [
                'required' => 'The Grade Name field is required.',
                'regex_match' => 'The Grade Name field contains invalid characters.',
                'is_unique' => 'The Grade Name field must be unique.',
            ],
            'code' => [
                'required' => 'The Code field is required.',
                'regex_match' => 'The Code field contains invalid characters.',
                'is_unique' => 'The Code field must be unique.',
            ],
            'type' => [
                'required' => 'Please select Grade Type.',
            ],
            'category_id' => [
                'required' => 'Please select Category.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'name' => $this->request->getVar('name'),
                'code' => $this->request->getVar('code'),
                'type' => $this->request->getVar('type'),
                'category_id' => $this->request->getVar('category_id'),
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
        $model = new GradeModel();

        $data = $model->find($id);

        if ($data) {
            $model->update($id,['status' => 0]);

            $model = new GardenGradeModel();
            $model->where('grade_id', $id)->delete();
            $result = AutoDelete::deleteRelations('grade_id',$id);

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
