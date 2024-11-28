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
use App\Helpers\CodeHelper;

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
            ->where('grade.status !=', 0)
            ->orderBy('grade.id', 'ASC')
            ->findAll();

        return $this->respond($data);
    }


    public function create()
    {
        $model = new GradeModel();
        $session_user_id = $this->session->get('session_user_id');
        $category_id = $this->request->getVar('category_id');
        $type = $this->request->getVar('type');
        // echo $type;exit;
        $rules = [
            'type' => 'required',
            'category_id' => 'required',
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique_with_category[grade.name,category_id,' . $category_id . ',type,' . $type . ']',
        ];
        // print_r($rules['name']);
        // exit;
        $messages = [
            'name' => [
                'required' => 'The Grade Name field is required.',
                'regex_match' => 'The Grade Name field contains invalid characters.',
                'is_unique_with_category' => 'The Grade Name field must be unique.',
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
            $code = CodeHelper::generateUniqueCode($model, 'code');

            // echo $code;exit;
            $data = [
                'name' => $this->request->getVar('name'),
                'type' => $this->request->getVar('type'),
                'category_id' => $this->request->getVar('category_id'),
                'created_by' => $this->request->getVar('created_by'),
                'status' => $this->request->getVar('status'),
            ];

            $model->insert($data);

            $grade_id = $model->getInsertID();

            $garden_model = new GardenModel();
            $garden_details  = $garden_model->where('category_id', $this->request->getVar('category_id'))
                ->where('status', 1)->findAll();

            // print_r($garden_details);exit;
            if (count($garden_details)) {
                $garden_grade = new GardenGradeModel();
                foreach ($garden_details as $key => $value) {
                    $gradegarden_details = $garden_grade->where('garden_id', $value['id'])->orderBy('order_seq', 'DESC')->first();
                    // print_r($gradegarden_details);
                    // exit;
                    if ($gradegarden_details != null) {

                        $newOrderSeq = $gradegarden_details['order_seq'] + 1;

                        $data = [
                            'garden_id' => $value['id'],
                            'grade_id' => $grade_id,
                            'category_id' => $this->request->getVar('category_id'),
                            'order_seq' => $newOrderSeq
                        ];
                    }
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
        $data = $model->where('id', $id)->where('grade.status !=', 0)->first();
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
        $category_id = $this->request->getVar('category_id');
        $type = $this->request->getVar('type');
        // echo $type;exit;
        $rules = [
            'type' => 'required',
            'category_id' => 'required',
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique_with_category[grade.name,category_id,' . $category_id . ',type,' . $type . ',id,' . $id . ']',
        ];
        // print_r($rules);
        // exit;
        $messages = [
            'name' => [
                'required' => 'The Grade Name field is required.',
                'regex_match' => 'The Grade Name field contains invalid characters.',
                'is_unique_with_category' => 'The Grade Name field must be unique.',
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
                'type' => $this->request->getVar('type'),
                'category_id' => $this->request->getVar('category_id'),
                'updated_by' => $this->request->getVar('updated_by'),
                'status' => $this->request->getVar('status'),
            ];

            $data1 = $model->find($id);

            if ($data1) {
                $model->update($id, $data);

                if ($this->request->getVar('category_id') != $data1['category_id']) {

                    $garden_model = new GardenModel();
                    $garden_details = $garden_model->where('category_id', $this->request->getVar('category_id'))
                        ->where('status', 1)
                        ->findAll();

                    if (count($garden_details)) {
                        $garden_grade = new GardenGradeModel();

                        foreach ($garden_details as $key => $value) {
                            $gradegarden_details = $garden_grade->where('garden_id', $value['id'])
                                ->orderBy('order_seq', 'DESC')
                                ->first();

                            if ($gradegarden_details != null) {
                                $newOrderSeq = $gradegarden_details['order_seq'] + 1;

                                $data = [
                                    'garden_id' => $value['id'],
                                    'grade_id' => $id,
                                    'category_id' => $this->request->getVar('category_id'),
                                    'order_seq' => $newOrderSeq
                                ];

                                // Delete all existing grades based on garden_id, category_id, and grade_id
                                $garden_grade
                                    ->where('category_id', $data1['category_id'])
                                    ->where('grade_id', $id)
                                    ->delete();

                                // Insert a new grade
                                $garden_grade->insert($data);
                            }
                        }
                    }
                }

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

        $model = new GradeModel();

        $data = $model->find($id);

        if ($data) {
            $model->update($id, ['status' => 0, 'updated_by' => $session_user_id]);

            $model = new GardenGradeModel();
            $model->where('grade_id', $id)->delete();
            $result = AutoDelete::deleteRelations('grade_id', $id);

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
