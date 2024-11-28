<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\InwardModel;
use App\Models\InwardItemModel;
use App\Models\CenterModel;
use App\Models\SellerModel;
use App\Models\GardenModel;
use App\Models\WarehouseModel;
use App\Models\GradeModel;
use App\Models\StockModel;
use App\Models\ProductLogModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use App\Helpers\ProductLog;

use Config\Services;


class Inward extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new InwardModel();
        $inwards = $model->select('inward.*, center.name AS center_name, seller.name AS seller_name, garden.name AS garden_name, warehouse.name AS warehouse_name, 
            (SELECT COUNT(*) FROM inward_return WHERE inward.id = inward_return.inward_id) AS cnt,
            (SELECT COUNT(*) FROM inward_items WHERE inward.id = inward_items.inward_id AND inward_items.is_assigned = 1) AS auction_cnt, 
            (SELECT COUNT(*) FROM inward_items WHERE inward.id = inward_items.inward_id AND inward_items.is_addedtocart = 1) AS cart_cnt')
            ->join('center', 'center.id = inward.center_id', 'left')
            ->join('seller', 'seller.id = inward.seller_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
            ->where('inward.status', 1)
            ->orderBy('inward.id', 'DESC')
            ->findAll();

        $data['inward'] = $inwards; // Corrected variable name
        return $this->respond($data);
    }


    public function create()
    {
        $model = new InwardModel();

        $session_user_id = $this->session->get('session_user_id');
        $garden_id = $this->request->getVar('garden_id');
        $grade_ids = $this->request->getVar('grade_id');
        $invoice_nos = $this->request->getVar('invoice_no');
        // $gradeIdsString = implode(',', $grade_ids);

        $rules = [
            'center_id' => 'required|numeric',
            'seller_id' => 'required|numeric',
            'garden_id' => 'required|numeric',
            'warehouse_id' => 'required|numeric',
            'gp_no' => 'required',
            'gp_total' => 'required|numeric',
            'gp_date' => 'required',
            'arrival_date' => 'required',
            'total_qty' => 'required|numeric',
            // 'invoice_no.*' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\-]*$/]|is_unique_with_grade_and_garden[inward_items.invoice_id,inward.garden_id,' . $garden_id . ',inward_items.grade_id,' . $gradeIdsString . ']',
            'grade_id.*' => 'required',
            'bag_type.*' => 'required',
            'no_of_bags.*' => 'required|greater_than[0]',
            'serial_no_from.*' => 'required',
            'serial_no_to.*' => 'required',
            'weight_nett.*' => 'required',
            'weight_tare.*' => 'required',
            'weight_gross.*' => 'required',
            'total_net.*' => 'required',
            'total_tare.*' => 'required',
            'total_gross.*' => 'required',

        ];


        $messages = [
            "center_id" => [
                "required" => "Please select Center.",
                "numeric" => "Center must be numeric.",
            ],
            "seller_id" => [
                "required" => "Please select Seller",
                "numeric" => "Seller must be numeric."
            ],
            "total_qty" => [
                "required" => "Please enter total quantity",
                "numeric" => "Total quantity must be numeric."
            ],
            "garden_id" => [
                "required" => "Please select Garden.",
                "numeric" => "Garden must be numeric."
            ],
            "warehouse_id" => [
                "required" => "Please select Warehouse.",
                "numeric" => "Warehouse ID must be numeric."
            ],
            "gp_no" => [
                "required" => "GP Number is required."
            ],
            "gp_total" => [
                "required" => "GP Total is required.",
                "numeric" => "GP Total must be numeric."
            ],
            "gp_date" => [
                "required" => "GP Date is required.",
                "valid_date" => "GP Date must be a valid date."
            ],
            "arrival_date" => [
                "required" => "Arrival Date is required.",
                "valid_date" => "Arrival Date must be a valid date."
            ],
            "quantity" => [
                "required" => "Quantity is required.",
                "numeric" => "Quantity must be numeric."
            ],
            "grade_id.*" => [
                "required" => "Please select Grade.",
                "numeric" => "Grade ID must be numeric."
            ],
            "bag_type.*" => [
                "required" => "Bag type is required."
            ],
            "serial_no_from.*" => [
                "required" => "Sno From is required."
            ],
            "serial_no_to.*" => [
                "required" => "Sno To is required."
            ],
            "weight_nett.*" => [
                "required" => "Net weight is required.",
                "numeric" => "Net weight must be numeric."
            ],
            "weight_tare.*" => [
                "required" => "Tare weight is required.",
                "numeric" => "Tare weight must be numeric."
            ],
            "weight_gross.*" => [
                "required" => "Gross weight is required.",
                "numeric" => "Gross weight must be numeric."
            ],
            "no_of_bags.*" => [
                "required" => "No of bags is required.",
                "numeric" => "No of bags must be numeric.",
                "greater_than" => "No of bags more than 0.",
            ],
            "total_net.*" => [
                "required" => "Total Net weight is required.",
                "numeric" => "Total Net weight must be numeric."
            ],
            "total_tare.*" => [
                "required" => "Total Tare weight is required.",
                "numeric" => "Total Tare weight must be numeric."
            ],
            "total_gross.*" => [
                "required" => "Total Gross weight is required.",
                "numeric" => "Total Gross weight must be numeric."
            ]
        ];

        foreach ($invoice_nos as $key => $invoice_no) {
            $rules['invoice_no.' . $key] = 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\-]*$/]|is_unique_with_grade_and_garden[inward_items.invoice_id,inward.garden_id,' . $garden_id . ',inward_items.grade_id,' . $grade_ids[$key] . ']';
            $messages['invoice_no.' . $key] =  [
                "required" => "Invoice No field is required.",
                "numeric" => "Invoice No must be numeric.",
                "is_unique_with_grade_and_garden" => "Invoice No must be unique.",
                'regex_match' => 'The Invoice No field contains invalid characters.',
            ];
        }

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'center_id' => $this->request->getVar('center_id'),
                'seller_id' => $this->request->getVar('seller_id'),
                'garden_id' => $this->request->getVar('garden_id'),
                'warehouse_id' => $this->request->getVar('warehouse_id'),
                'gp_no' => $this->request->getVar('gp_no'),
                'gp_date' => date("Y-m-d", strtotime($this->request->getVar('gp_date'))),
                'arrival_date' => date("Y-m-d", strtotime($this->request->getVar('arrival_date'))),
                'quantity' => $this->request->getVar('total_qty'),
                'remark' => $this->request->getVar('remarks'),
                'gross_total_weight' => $this->request->getVar('gross_total_weight'),
                'nett_total_weight' => $this->request->getVar('nett_total_weight'),
                'created_by' => $this->request->getVar('session_user_id'),
                'status' => 1,
            ];



            $model->insert($data);
            $inwardId = $model->getInsertID();
            for ($i = 0; $i < count($this->request->getVar('invoice_no')); $i++) {
                if ($this->request->getVar('invoice_no')[$i] != '' && $this->request->getVar('grade_id')[$i] != '') {
                    $inwardItemModel = new InwardItemModel();
                    $inwardItemData = [
                        'invoice_id' => $this->request->getVar('invoice_no')[$i],
                        'inward_id' => $inwardId,
                        'grade_id' => $this->request->getVar('grade_id')[$i],
                        'bag_type' => $this->request->getVar('bag_type')[$i],
                        'no_of_bags' => $this->request->getVar('no_of_bags')[$i],
                        'sno_from' => $this->request->getVar('serial_no_from')[$i],
                        'sno_to' => $this->request->getVar('serial_no_to')[$i],
                        'weight_net' => $this->request->getVar('weight_nett')[$i],
                        'weight_tare' => $this->request->getVar('weight_tare')[$i],
                        'weight_gross' => $this->request->getVar('weight_gross')[$i],
                        'total_net' => $this->request->getVar('total_net')[$i],
                        'total_tare' => $this->request->getVar('total_tare')[$i],
                        'total_gross' => $this->request->getVar('total_gross')[$i],
                        'created_by' => $this->request->getVar('session_user_id'),
                        'status' => 1,
                    ];

                    $inwardItemModel->insert($inwardItemData);
                    $inwarditemId = $inwardItemModel->getInsertID();

                    $result = ProductLog::logProductAction($inwarditemId, 'Added to Stock', $this->request->getVar('no_of_bags')[$i], $this->request->getVar('session_user_id'));

                    $stockModel = new StockModel();
                    $stockData = [
                        'inward_item_id' => $inwarditemId,
                        'inward_id' => $inwardId,
                        'qty' => $this->request->getVar('no_of_bags')[$i],
                        'warehouse_id' => $this->request->getVar('warehouse_id'),
                    ];
                    $stockModel->insert($stockData);
                }
            }

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Inserted Successfully'
            ];

            return $this->respondCreated($response);
        }
    }

    public function show($id = null)
    {
        $model = new InwardModel();
        $inward = $model->find($id);

        if ($inward) {
            $inwardItemModel = new InwardItemModel();
            $inwardItems = $inwardItemModel->where('inward_id', $inward['id'])->findAll();

            $inward['inwardItems'] = $inwardItems;

            $data['inward'] = $inward;

            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }

    public function update($id = null)
    {
        $model = new InwardModel();
        $inwardItemModel = new InwardItemModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'center_id' => 'required|numeric',
            'seller_id' => 'required|numeric',
            'garden_id' => 'required|numeric',
            'warehouse_id' => 'required|numeric',
            'gp_no' => 'required',
            'gp_date' => 'required',
            'arrival_date' => 'required',
            'total_qty' => 'required|numeric',
            'invoice_no.*' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\-]*$/]|is_unique_without_status[inward_items.invoice_id,inward_id,' . $id . ']',
            'grade_id.*' => 'required',
            'bag_type.*' => 'required',
            'no_of_bags.*' => 'required|greater_than[0]',
            'serial_no_from.*' => 'required',
            'serial_no_to.*' => 'required',
            'weight_nett.*' => 'required',
            'weight_tare.*' => 'required',
            'weight_gross.*' => 'required',
            'total_net.*' => 'required',
            'total_tare.*' => 'required',
            'total_gross.*' => 'required',
        ];


        $messages = [
            "center_id" => [
                "required" => "Please select Center.",
                "numeric" => "Center must be numeric."
            ],
            "seller_id" => [
                "required" => "Please select Seller",
                "numeric" => "Seller must be numeric."
            ],
            "garden_id" => [
                "required" => "Please select Garden.",
                "numeric" => "Garden must be numeric."
            ],
            "total_qty" => [
                "required" => "Please enter total quantity",
                "numeric" => "total quantity must be numeric."
            ],
            "warehouse_id" => [
                "required" => "Please select Warehouse.",
                "numeric" => "Warehouse ID must be numeric."
            ],
            "gp_no" => [
                "required" => "GP Number is required."
            ],
            "gp_date" => [
                "required" => "GP Date is required.",
                "valid_date" => "GP Date must be a valid date."
            ],
            "arrival_date" => [
                "required" => "Arrival Date is required.",
                "valid_date" => "Arrival Date must be a valid date."
            ],
            "quantity" => [
                "required" => "Quantity is required.",
                "numeric" => "Quantity must be numeric."
            ],
            "invoice_no.*" => [
                "required" => "Invoice No field is required.",
                "is_unique_without_status" => "Invoice No must be unique.",
                "numeric" => "Invoice ID must be numeric.",
                'regex_match' => 'The Invoice No field contains invalid characters.',
            ],
            "grade_id.*" => [
                "required" => "Please select Grade.",
                "numeric" => "Grade ID must be numeric."
            ],
            "bag_type.*" => [
                "required" => "Bag type is required."
            ],
            "serial_no_from.*" => [
                "required" => "Sno From is required."
            ],
            "serial_no_to.*" => [
                "required" => "Sno To is required."
            ],
            "weight_nett.*" => [
                "required" => "Net weight is required.",
                "numeric" => "Net weight must be numeric."
            ],
            "weight_tare.*" => [
                "required" => "Tare weight is required.",
                "numeric" => "Tare weight must be numeric."
            ],
            "weight_gross.*" => [
                "required" => "Gross weight is required.",
                "numeric" => "Gross weight must be numeric."
            ],
            "no_of_bags.*" => [
                "required" => "No of bags is required.",
                "numeric" => "No of bags must be numeric.",
                "greater_than" => "No of bags more than 0."
            ],
            "total_net.*" => [
                "required" => "Total Net weight is required.",
                "numeric" => "Total Net weight must be numeric."
            ],
            "total_tare.*" => [
                "required" => "Total Tare weight is required.",
                "numeric" => "Total Tare weight must be numeric."
            ],
            "total_gross.*" => [
                "required" => "Total Gross weight is required.",
                "numeric" => "Total Gross weight must be numeric."
            ],
        ];



        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'center_id' => $this->request->getVar('center_id'),
                'seller_id' => $this->request->getVar('seller_id'),
                'garden_id' => $this->request->getVar('garden_id'),
                'warehouse_id' => $this->request->getVar('warehouse_id'),
                'gp_no' => $this->request->getVar('gp_no'),
                'gp_date' => date("Y-m-d", strtotime($this->request->getVar('gp_date'))),
                'arrival_date' => date("Y-m-d", strtotime($this->request->getVar('arrival_date'))),
                'quantity' => $this->request->getVar('total_qty'),
                'nett_total_weight' => $this->request->getVar('nett_total_weight'),
                'gross_total_weight' => $this->request->getVar('gross_total_weight'),
                'remark' => $this->request->getVar('remarks'),
                'updated_by' => $this->request->getVar('session_user_id'),
            ];

            $model->update($id, $data);
            $model = new InwardItemModel();

            $model->where('inward_id', $id)->delete();

            for ($i = 0; $i < count($this->request->getVar('invoice_no')); $i++) {
                if ($this->request->getVar('invoice_no')[$i] != '' && $this->request->getVar('grade_id')[$i] != '') {
                    $inwardItemModel = new InwardItemModel();
                    $inwardItemData = [
                        'invoice_id' => $this->request->getVar('invoice_no')[$i],
                        'inward_id' => $id,
                        'grade_id' => $this->request->getVar('grade_id')[$i],
                        'bag_type' => $this->request->getVar('bag_type')[$i],
                        'no_of_bags' => $this->request->getVar('no_of_bags')[$i],
                        'sno_from' => $this->request->getVar('serial_no_from')[$i],
                        'sno_to' => $this->request->getVar('serial_no_to')[$i],
                        'weight_net' => $this->request->getVar('weight_nett')[$i],
                        'weight_tare' => $this->request->getVar('weight_tare')[$i],
                        'weight_gross' => $this->request->getVar('weight_gross')[$i],
                        'total_net' => $this->request->getVar('total_net')[$i],
                        'total_tare' => $this->request->getVar('total_tare')[$i],
                        'total_gross' => $this->request->getVar('total_gross')[$i],
                        'updated_by' => $this->request->getVar('session_user_id'),
                    ];

                    $inwardItemModel->insert($inwardItemData);
                }
            }

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Updated Successfully',
                'data' => $data,
                'inward_data' => $inwardItemData
            ];

            return $this->respond($response);
        }
    }
    public function delete($id = null)
    {
        $model = new InwardModel();
        $session_user_id = $this->request->getHeaderLine('Authorization1');

        $data = $model->find($id);

        if ($data) {
            $updateData = ['status' => 0];
            $model->update($id, $updateData);

            $inwardItemModel = new InwardItemModel();
            $inwardItems = $inwardItemModel->where('inward_id', $id)->findAll();

            foreach ($inwardItems as $item) {
                $inwardItemModel->update($item['id'], ['status' => 0]);
                $result = ProductLog::logProductAction($item['id'], 'Removed from Stock', $item['no_of_bags'], $session_user_id);
            }

            // Prepare response data
            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Deleted Successfully',
                'data' => $updateData
            ];

            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with id: ' . $id);
        }
    }

    public function addDatas()
    {

        $center_model = new CenterModel();
        $seller_model = new SellerModel();
        $garden_model = new GardenModel();
        $grade_model = new GradeModel();
        $warehouse_model = new WarehouseModel();
        $data['centers'] = $center_model->where('status', 1)->findAll();
        $data['sellers'] = $seller_model->where('status', 1)->findAll();
        $data['gardens'] = $garden_model->where('status', 1)->where('seller_id', $data['sellers'][0]['id'])->findAll();
        $data['sellers_with_gardens'] = [];

        foreach ($data['sellers'] as $seller) {
            $gardens = $garden_model->where('status', 1)
                ->where('seller_id', $seller['id'])
                ->findAll();
            $seller['gardens'] = $gardens;
            $data['sellers_with_gardens'][] = $seller;
        }
        $data['warehouses'] = $warehouse_model->where('status', 1)->findAll();
        $data['grades'] = $grade_model->where('status', 1)->findAll();
        return $this->respond($data);
    }

    public function InwardItemDetail()
    {
        $inwarditem_model = new InwardItemModel();
        $data = $inwarditem_model->where('status', 1)->findAll();
        return $this->respond($data);
    }


    public function ItemDetail($id)
    {
        $inwarditem_model = new InwardItemModel();
        $data = $inwarditem_model->select('inward_items.*,grade.name AS grade_name, stock.qty AS stock_qty')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('stock', 'inward_items.id = stock.inward_item_id', 'left')
            ->where('inward_items.id', $id)->where('inward_items.status', 1)->first();
        return $this->respond($data);
    }

    public function gardenGrade()
    {
        $garden_id = $this->request->getVar('garden_id');
        $garden = new GardenModel();

        if ($garden_id) {
            $garden_detail = $garden->where('id', $garden_id)->first();
            $category_id = $garden_detail['category_id'];

            $gardenGrade = new GradeModel();
            $grades = $gardenGrade->where('category_id', $category_id)->where('status', 1)->findAll();

            if (count($grades)) {

                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => 'Success',
                    'data' => $grades
                ];
                return $this->respond($response);
            }
        } else {
            return $this->failNotFound('No Data Found with id : ' . $garden_id);
        }
    }

    public function Detail($id = null)
    {
        $model = new InwardModel();
        $inward = $model->select('inward.*,center.name AS center_name,seller.name AS seller_name,seller.gst_no,seller.fssai_no,seller.address AS seller_address,garden.name AS garden_name,warehouse.name AS warehouse_name')
            ->join('center', 'center.id = inward.center_id', 'left')
            ->join('seller', 'seller.id = inward.seller_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
            ->find($id);

        if ($inward) {
            $inwardItemModel = new InwardItemModel();
            $inwardItems = $inwardItemModel->select('inward_items.*,grade.name AS grade_name')
                ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                ->where('inward_id', $inward['id'])->where('inward_items.status', 1)->findAll();

            $inward['inwardItems'] = $inwardItems;

            $data['inward'] = $inward;

            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }

    public function InwardItemDetail1()
    {
        $inwarditem_model = new InwardItemModel();
        $inward_model = new InwardModel();
        $data = $inwarditem_model->select('inward_items.*,garden.name AS garden_name')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->where('inward_items.status', 1)->findAll();
        return $this->respond($data);
    }
}
