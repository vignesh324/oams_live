<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\SampleReceiptModel;
use App\Models\AuctionModel;
use App\Models\AuctionItemModel;
use App\Models\AuctionStockModel;
use App\Models\StockModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;


class Samplereceipt extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new SampleReceiptModel();
        $data['sampleReceipt'] = $model->select('sample_receipt.*,buyer.name as buyer_name')
            ->join('buyer', 'buyer.id = sample_receipt.buyer_id')
            ->join('auction', 'auction.id = sample_receipt.auction_id')
            ->orderBy('sample_receipt.id')
            ->where('sample_receipt.status !=', 0)
            ->findAll();
        return $this->respond($data);
    }
    public function create()
    {
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'lot_no' => 'required|numeric',
            'quantity' => 'required|numeric',
        ];

        $messages = [
            'lot_no' => [
                'required' => 'The Lot Number field is required.',
                "numeric" => "The Lot Number must be numeric."
            ],
            'quantity' => [
                'required' => 'The Quantity field is required.',
                "numeric" => "The Quantity must be numeric."
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            // print_r($this->request->getVar('buyer_id'));exit;
            $auctionstockModel = new AuctionStockModel();
            $buyer_ids = $this->request->getVar('buyer_id');
            $auctionitem_id = $this->request->getVar('auctionitem_id');
            $auctionstock_data = $auctionstockModel->where('auction_item_id', $auctionitem_id)->first();
            $total_quantity = count($buyer_ids) * $this->request->getVar('quantity');
            // echo '<pre>';
            // print_r($total_quantity);
            // exit;
            if ($auctionstock_data['qty'] - $total_quantity > 0) {
                $auctionstockModel->where('auction_item_id', $auctionitem_id)->set('qty', $auctionstock_data['qty'] - $total_quantity)->update();
                foreach ($buyer_ids as $buyer_id) {
                    if (!empty($buyer_id)) {
                        $model = new SampleReceiptModel();

                        $data = [
                            'buyer_id' => $buyer_id,
                            'quantity' => $this->request->getVar('quantity'),
                            'lot_no' => $this->request->getVar('lot_no'),
                            'auction_id' => $this->request->getVar('auction_id'),
                            'auction_item_id' => $this->request->getVar('auctionitem_id'),
                            'created_by' => $this->request->getVar('created_by'),
                            'status' => 1,
                        ];


                        $model->insert($data);
                    }
                }

                $response = [
                    'status' => 200,
                    'error' => false,
                    'messages' => 'Inserted Successfully',
                    'data' => $data // Note: 'data' will hold the last inserted record, consider revising if needed
                ];

                return $this->respondCreated($response);
            } else {
                $response = [
                    'status' => 422,
                    'error' => false,
                    'messages' => [
                        'quantity' => 'Quantity Should be less than Auction quantity'
                    ],
                ];
                return $this->respondCreated($response);
            }
        }
    }


    public function show($id = null)
    {
        $model = new SampleReceiptModel();

        $data = $model->select('sample_receipt.*,buyer.name as buyer_name')
            ->join('buyer', 'buyer.id = sample_receipt.buyer_id')
            ->where('sample_receipt.id', $id)
            ->where('sample_receipt.status !=', 0)
            ->orderBy('sample_receipt.id')->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $model = new SampleReceiptModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'lot_no' => 'required|numeric',
            'quantity' => 'required|numeric',
        ];

        $messages = [
            'lot_no' => [
                'required' => 'The Lot Number field is required.',
                "numeric" => "The Lot Number must be numeric."
            ],
            'quantity' => [
                'required' => 'The Quantity field is required.',
                "numeric" => "The Quantity must be numeric."
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {

            $data = [
                'buyer_id' => $this->request->getVar('buyer_id'),
                'quantity' => $this->request->getVar('quantity'),
                'lot_no' => $this->request->getVar('lot_no'),
                'updated_by' => $this->request->getVar('updated_by'),
                'status' => $this->request->getVar('status'),
            ];
            $model->update($id, $data);
            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Inserted Successfully',
                'data' => $data,
            ];

            return $this->respond($response);
        }
    }

    public function delete($id = null)
    {
        $model = new SampleReceiptModel();

        $data = $model->find($id);

        if ($data) {
            $model->delete($id);

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

    public function auctionSaleNo()
    {
        // return 'hii';
        $model = new AuctionModel();
        $auctions = $model->select('auction.id,auction.sale_no,auction.type')
            ->where('auction.status', 1)
            ->findAll();

        $data['auction'] = $auctions;
        return $this->respond($data);
    }

    public function salenoWiseLot()
    {
        $model = new AuctionItemModel();
        $auctionModel = new AuctionModel();
        $isLeaf = 0;
        $data1 = [
            'auction_id' => $this->request->getVar('auction_id'),
        ];
        $aucData = $auctionModel->where('id', $data1['auction_id'])->first();
        if ($aucData) {
            if ($aucData['type'] == 1) {
                $isLeaf = 1;
            }
        }
        // print_r($data1['auction_id']);exit;
        $data['lot_no'] = $model->select('auction_items.id, auction_items.lot_no')
            ->where('auction_items.auction_id', $data1['auction_id'])
            ->where('auction_items.status', 1)
            ->findAll();

        if ($data['lot_no']) {

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Success',
                'data' => $data,
                'is_leaf' => $isLeaf,
            ];

            return $this->respond($response);
        } else {
            $response = [
                'status' => 404,
                'error' => false,
                'message' => 'Success',
                'data' => ['lot_no' => []],
                'is_leaf' => $isLeaf,
            ];
            return $this->respond($response);
        }
    }

    public function salenoWiseLotnoSelect()
    {
        $auction_model = new AuctionModel();
        $auctionitem_model = new AuctionItemModel();

        $data['auction'] = $auction_model
            ->select('id, sale_no')
            ->where('status', 1)
            ->findAll();

        foreach ($data['auction'] as &$auction) {
            $auction['auction_item'] = $auctionitem_model
                ->select('id, lot_no')
                ->where('status', 1)
                ->where('auction_id', $auction['id'])
                ->findAll();
        }

        // echo '<pre>';print_r($data);exit;
        return $this->respond($data);
    }
}
