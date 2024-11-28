<?php

namespace App\Controllers;

use App\Models\CenterGardenModel;
use App\Models\CenterModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\GardenModel;
use App\Models\GardenGradeModel;
use App\Models\GradeModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\CodeHelper;

class Garden extends ResourceController
{
    use ResponseTrait;
    protected $session;
    public function __construct()
    {
        $this->session = Services::session();
    }
    public function index()
    {
        $model = new GardenModel();

        $data['garden'] = $model->select('garden.*, city.name as city_name, state.name as state_name, area.name as area_name, seller.name as seller_name,category.name as category_name')
            ->join('city', 'city.id = garden.city_id', 'left')
            ->join('category', 'category.id = garden.category_id', 'left')
            ->join('state', 'state.id = garden.state_id', 'left')
            ->join('area', 'area.id = garden.area_id', 'left')
            ->join('seller', 'seller.id = garden.seller_id', 'left')
            ->where('garden.status !=', 0)
            ->orderBy('garden.id')
            ->findAll();


        return $this->respond($data);
    }
    public function gardenDropdown()
    {
        $model = new GardenModel();

        $data['garden'] = $model->select('garden.*, city.name as city_name, state.name as state_name, area.name as area_name, seller.name as seller_name,category.name as category_name')
            ->join('city', 'city.id = garden.city_id', 'left')
            ->join('category', 'category.id = garden.category_id', 'left')
            ->join('state', 'state.id = garden.state_id', 'left')
            ->join('area', 'area.id = garden.area_id', 'left')
            ->join('seller', 'seller.id = garden.seller_id', 'left')
            ->where('garden.status', 1)
            ->orderBy('garden.id')
            ->findAll();


        return $this->respond($data);
    }
    public function create()
    {
        $model = new GardenModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[garden.name]',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'area_id' => 'required|numeric',
            'seller_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'address' => 'required',
        ];

        $messages = [
            'name' => [
                'required' => 'The Garden Name field is required.',
                'regex_match' => 'The Garden Name field contains invalid characters.',
                'is_unique' => 'The Garden Name field must be unique.',
            ],
            'state_id' => [
                'required' => 'Please select State.',
            ],
            'city_id' => [
                'required' => 'Please select City.',
            ],
            'area_id' => [
                'required' => 'Please select Area.',
            ],
            'seller_id' => [
                'required' => 'Please select Seller.',
            ],
            'address' => [
                'required' => 'The Address field is required.',
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

            $vacumm_bag = $this->request->getVar('vacumm_bag') == 'on' ? 1 : 0;
            // echo $vacumm_bag;exit;
            $data = [
                'name' => $this->request->getVar('name'),
                'vacumm_bag' => $vacumm_bag,
                'state_id' => $this->request->getVar('state_id'),
                'city_id' => $this->request->getVar('city_id'),
                'area_id' => $this->request->getVar('area_id'),
                'seller_id' => $this->request->getVar('seller_id'),
                'category_id' => $this->request->getVar('category_id'),
                'address' => $this->request->getVar('address'),
                'created_by' => $this->request->getVar('created_by'),
                'status' => $this->request->getVar('status'),
            ];

            $model->insert($data);

            $gardenId = $model->getInsertID();

            $model = new GradeModel();
            $grades = $model->where('category_id', $this->request->getVar('category_id'))->where('status', 1)->findAll();
            foreach ($grades as $key => $value) {
                $model = new GardenGradeModel();
                $datas = [];
                $datas = [
                    'garden_id' => $gardenId,
                    'grade_id' => $value['id'],
                    'category_id' => $this->request->getVar('category_id'),
                    'order_seq' => $key + 1,
                ];
                $model->insert($datas);
            }


            $center_model = new CenterModel();
            $center_details  = $center_model->where('status', 1)->findAll();
            if (count($center_details)) {
                foreach ($center_details as $key => $value) {
                    $center_garden = new CenterGardenModel();
                    $center_garden_details = $center_garden->where('center_id', $value['id'])->orderBy('order_seq', 'DESC')->first();
                    if (isset($center_garden_details))
                        $order_seq = $center_garden_details['order_seq'];
                    else
                        $order_seq = 0;

                    $newOrderSeq = $order_seq + 1;

                    $data = [
                        'center_id' => $value['id'],
                        'garden_id' => $gardenId,
                        'order_seq' => $newOrderSeq
                    ];
                    $center_garden->insert($data);
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
        $model = new GardenModel();
        $data = $model->select('garden.*, city.name as city_name, state.name as state_name, area.name as area_name, seller.name as seller_name')
            ->join('city', 'city.id = garden.city_id', 'left')
            ->join('state', 'state.id = garden.state_id', 'left')
            ->join('area', 'area.id = garden.area_id', 'left')
            ->join('seller', 'seller.id = garden.seller_id', 'left')
            ->orderBy('garden.id')
            ->where('garden.status !=', 0)
            ->where('garden.id', $id)->first();

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $model = new GardenModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[garden.name,id,' . $id . ']',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'area_id' => 'required|numeric',
            'seller_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'address' => 'required',
        ];

        $messages = [
            'name' => [
                'required' => 'The Garden Name field is required.',
                'regex_match' => 'The Garden Name field contains invalid characters.',
                'is_unique' => 'The Garden Name field must be unique.',
            ],
            'state_id' => [
                'required' => 'Please select State.',
            ],
            'city_id' => [
                'required' => 'Please select City.',
            ],
            'area_id' => [
                'required' => 'Please select Area.',
            ],
            'seller_id' => [
                'required' => 'Please select Seller.',
            ],
            'category_id' => [
                'required' => 'Please select Category.',
            ],
            'address' => [
                'required' => 'The Address field is required.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $vacumm_bag = $this->request->getVar('vacumm_bag') == 'on' ? 1 : 0;
            $data = [
                'name' => $this->request->getVar('name'),
                'vacumm_bag' => $vacumm_bag,
                'state_id' => $this->request->getVar('state_id'),
                'category_id' => $this->request->getVar('category_id'),
                'city_id' => $this->request->getVar('city_id'),
                'area_id' => $this->request->getVar('area_id'),
                'seller_id' => $this->request->getVar('seller_id'),
                'address' => $this->request->getVar('address'),
                'updated_by' => $this->request->getVar('updated_by'),
                'status' => $this->request->getVar('status'),
            ];

            $data1 = $model->find($id);

            $category_detail = $model->where('id', $id)->first();

            $current_category_id = $category_detail['category_id'];


            if ($data1) {
                $model->update($id, $data);

                if ($this->request->getVar('category_id') != $current_category_id) {
                    $model = new GardenGradeModel();
                    $model->where('garden_id', $id)->delete();
                    $model1 = new GradeModel();
                    $grades = $model1->where('category_id', $this->request->getVar('category_id'))->where('status', 1)->findAll();
                    foreach ($grades as $key => $value) {
                        $model = new GardenGradeModel();
                        $datas = [];
                        $datas = [
                            'garden_id' => $id,
                            'grade_id' => $value['id'],
                            'category_id' => $this->request->getVar('category_id'),
                            'order_seq' => $key + 1,
                        ];
                        $model->insert($datas);
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

        $model = new GardenModel();

        $data = $model->find($id);

        if ($data) {
            $model->update($id, ['status' => 0, 'updated_by' => $session_user_id]);

            $model = new GardenGradeModel();
            $model->where('garden_id', $id)->delete();

            $model = new CenterGardenModel();
            $model->where('garden_id', $id)->delete();

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


    public function assignGrade()
    {
        $model = new GardenGradeModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'garden_id' => 'required|numeric',
            'grade_id' => 'required',
            'order_seq' => 'required'
        ];

        $garden_id = $this->request->getVar('garden_id');
        $grade_ids = $this->request->getVar('grade_id');
        $order_seqs = $this->request->getVar('order_seq');


        if (!$this->validate($rules)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {

            $insertData = [];

            // Iterate through arrays simultaneously
            foreach ($grade_ids as $key => $grade_id) {
                $data = [
                    'garden_id' => $garden_id,
                    'grade_id' => $grade_id,
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

    public function showGradesByGarden($id = null)
    {
        $model = new GardenGradeModel();

        $assignedGrades = $model->where('garden_id', $id)->findAll();

        if (empty($assignedGrades)) {
            return $this->failNotFound('No grades found for the specified garden ID.');
        }

        $response = [
            'status' => 200,
            'data' => $assignedGrades
        ];

        return $this->respond($response);
    }

    public function showGradesByCategory($id = null)
    {
        $model = new GardenGradeModel();

        $data['garden_grade'] = $model->select('garden_grade.*, grade.name as grade_name')
            ->join('grade', 'grade.id = garden_grade.grade_id', 'left')
            ->where('garden_grade.category_id', $id)
            ->where('grade.status', 1)
            ->whereIn('garden_grade.id', function ($subQuery) use ($id) {
                $subQuery->select('MIN(id)')
                    ->from('garden_grade')
                    ->where('category_id', $id)
                    ->groupBy('grade_id')
                    ->orderBy('order_seq');
            })
            ->findAll();

        return $this->respond($data);
    }

    public function gardengradelist()
    {
        $model = new GardenGradeModel();

        $data['garden_grade'] = $model->select('garden_grade.*, garden.name as garden_name, grade.name as grade_name,city.name as city_name, state.name as state_name, area.name as area_name, seller.name as seller_name')
            ->join('garden', 'garden.id = garden_grade.garden_id', 'left')
            ->join('city', 'city.id = garden.city_id', 'left')
            ->join('state', 'state.id = garden.state_id', 'left')
            ->join('area', 'area.id = garden.area_id', 'left')
            ->join('seller', 'seller.id = garden.seller_id', 'left')
            ->join('grade', 'grade.id = garden_grade.grade_id', 'left')
            ->where('grade.status', 1)
            ->orderBy('garden_grade.id')
            ->findAll();

        return $this->respond($data);
    }

    public function assignGardengradelist($id)
    {
        $model = new GardenGradeModel();
        $gardenmodel = new GardenModel();
        $garden_data = $gardenmodel->find($id);
        // print_r($garden_data);exit;
        $data['garden_grade'] = $model->select('garden_grade.*, grade.name as grade_name')
            ->join('grade', 'grade.id = garden_grade.grade_id', 'left')
            ->where('garden_grade.garden_id', $id)
            ->where('grade.status', 1)
            ->whereIn('garden_grade.id', function ($subQuery) use ($id) {
                $subQuery->select('MIN(id)')
                    ->from('garden_grade')
                    ->where('garden_id', $id)
                    ->groupBy('grade_id')
                    ->orderBy('order_seq');
            })
            ->findAll();

        return $this->respond($data);
    }

    public function reOrderGrade()
    {
        $input_data = file_get_contents("php://input");

        // Decode the JSON data
        $requestData = json_decode($input_data, true);

        $model = new GardenGradeModel();

        foreach ($requestData as $item) {
            $sequence = $item['sequence'];
            $garde_id = $item['grade_id'];
            $garden_id = $item['garden_id'];
            $category_id = $item['category_id'];

            $updateData[] = [
                'grade_id' => $item['grade_id'],
                'order_seq' => $item['sequence'],
                'garden_id' => $garden_id,
                'category_id' => $category_id
            ];
            // $data = ['order_seq' => $sequence, 'category_id' => $category_id];

        }
        // echo '<pre>';
        // print_r($updateData);
        // exit;

        $model->where('category_id', $category_id)
            ->where('garden_id', $garden_id)
            ->delete();

        if ($model->insertBatch($updateData)) {
            return $this->respond(['status' => '200', 'message' => 'Order sequence updated successfully']);
        } else {
            return $this->respond(['status' => '500', 'message' => 'Failed to update order sequence']);
        }
    }
    public function reOrderCategoryGrade()
    {
        // Get the raw JSON input data
        $input_data = file_get_contents("php://input");

        // Decode the JSON data
        $requestData = json_decode($input_data, true);

        // Check if JSON decoding was successful and data is in expected format
        if (is_array($requestData)) {
            $model1 = new GardenModel();
            $model = new GardenGradeModel();

            // Find all gardens matching the category and status
            $gardens = $model1->where('category_id', $requestData[0]['category_id'])
                ->where('status', 1)
                ->findAll();

            $gardenIds = array_column($gardens, 'id');

            $updateData = [];

            foreach ($gardenIds as $garden_id) {

                foreach ($requestData as $item) {
                    // Prepare the data to update for the current garden
                    $updateData[] = [
                        'grade_id' => $item['grade_id'],
                        'order_seq' => $item['sequence'],
                        'garden_id' => $garden_id,
                        'category_id' => $requestData[0]['category_id']
                    ];
                }
            }
            // print_r($updateData);
            // exit;
            $model->where('category_id', $requestData[0]['category_id'])
                ->whereIn('garden_id', $gardenIds)->delete();

            $model->insertBatch($updateData);

            // exit;

            // Return success response
            return $this->respond(['status' => '200', 'message' => 'Order sequence updated successfully']);
        } else {
            // Return error response if request data is not valid
            return $this->respond(['status' => '422', 'message' => 'Invalid input data']);
        }
    }
}
