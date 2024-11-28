<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\SoldStockModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\AutoDelete;
use App\Helpers\CodeHelper;


class SoldStock extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new SoldStockModel();

        $data['stocks'] = $model->select('sold_stock.*,auction.sale_no AS sale_no,
        inward_items.weight_net,auction_items.lot_no,auction_items.sample_quantity AS auction_items_sample_quantity,
        bid_info.bid_price AS highest_bid_price, 
        bid_info.buyer_id, 
        bid_info.buyer_name AS highest_bidder_name,
        ')
            ->join('auction', 'auction.id = sold_stock.auction_id', 'left')
            ->join('auction_items', 'auction_items.id = sold_stock.auction_item_id', 'left')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join(
                '(SELECT ab.auction_item_id, 
             ab.bid_price, 
             ab.buyer_id, 
             b.name AS buyer_name 
      FROM auction_biddings ab 
      LEFT JOIN buyer b ON ab.buyer_id = b.id 
      WHERE ab.bid_price = (
          SELECT MAX(ab2.bid_price) 
          FROM auction_biddings ab2 
          WHERE ab2.auction_item_id = ab.auction_item_id
        AND ab2.bid_type != 3
              )  AND ab.bid_type != 3
                  GROUP BY ab.auction_item_id) AS bid_info',
                'bid_info.auction_item_id = auction_items.id',
                'left'
            )
            ->where('sold_stock.qty !=',0)
            ->findAll();

        return $this->respond($data);
    }

    public function create()
    {
        $model = new AreaModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[area.name]',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
        ];

        $messages = [
            "name" => [
                "required" => "The Area Name field is required.",
                'regex_match' => 'The Area Name field contains invalid characters.',
                'is_unique' => 'The Area Name field must be unique.',
            ],
            "state_id" => [
                "required" => "Please select State.",
            ],
            "city_id" => [
                "required" => "Please select City.",
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
        $model = new AreaModel();
        $data = $model->select('area.*, city.name as city_name, state.name as state_name')
            ->join('city', 'city.id = area.city_id', 'left')
            ->join('state', 'state.id = area.state_id', 'left')
            ->where('area.status !=', 0)
            ->orderBy('area.id')
            ->where('area.id', $id)->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $model = new AreaModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[area.name,id,' . $id . ']',
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
        ];

        $messages = [
            "name" => [
                "required" => "The Area Name field is required.",
                'regex_match' => 'The Area Name field contains invalid characters.',
                'is_unique' => 'The Area Name field must be unique.',
            ],
            "state_id" => [
                "required" => "Please select State.",
            ],
            "city_id" => [
                "required" => "Please select City.",
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
        $model = new AreaModel();

        $data = $model->find($id);

        if ($data) {
            $model->update($id, ['status' => 0]);
            $result = AutoDelete::deleteRelations('area_id', $id);

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

    public function cityArea($id = null)
    {
        $model = new AreaModel();

        $data['area'] = $model->select('area.id, area.name')
            ->where('area.city_id', $id)
            ->where('area.status', 1)
            ->findAll();

        if ($data['area']) {

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Success',
                'data' => $data
            ];

            // print_r($response);exit;

            return $this->respond($response);
        } else {
            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Success',
                'data' => ['area' => []]
            ];
            return $this->respond($response);
        }
    }
}
