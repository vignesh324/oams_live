<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\InwardItemModel;
use App\Models\InwardModel;
use CodeIgniter\API\ResponseTrait;
use Config\Services;


class inwarditems extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new InwardItemModel();
        $inwarditems = $model->orderBy('id')->findAll();

        foreach ($inwarditems as &$inwarditem) {
            $inwarditem['inward'] = (new InwardModel())->where('id', $inwarditem['inward_id'])->findAll();
        }
        $data['inwarditems'] = $inwarditems;
        return $this->respond($data);
    }
    public function create()
    {
        $model = new InwardItemModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'invoice_id' => 'required|numeric',
            'inward_id' => 'required|numeric',
            'grade_id' => 'required|numeric',
            'bag_type' => 'required',
            'sno_from' => 'required',
            'sno_to' => 'required',
            'weight_net' => 'required|numeric',
            'weight_tare' => 'required|numeric',
            'weight_gross' => 'required|numeric',
            'total_net' => 'required|numeric',
            'total_tare' => 'required|numeric',
            'total_gross' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'invoice_id' => $this->request->getVar('invoice_id'),
                'inward_id' => $this->request->getVar('inward_id'),
                'grade_id' => $this->request->getVar('grade_id'),
                'bag_type' => $this->request->getVar('bag_type'),
                'sno_from' => $this->request->getVar('sno_from'),
                'sno_to' => $this->request->getVar('sno_to'),
                'weight_net' => $this->request->getVar('weight_net'),
                'weight_tare' => $this->request->getVar('weight_tare'),
                'weight_gross' => $this->request->getVar('weight_gross'),
                'total_net' => $this->request->getVar('total_net'),
                'total_tare' => $this->request->getVar('total_tare'),
                'total_gross' => $this->request->getVar('total_gross'),
                'created_by' => $this->request->getVar('session_user_id'),
                'status' => 1,
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
        $model = new InwardItemModel();
        $inwardItem = $model->find($id);

        if ($inwardItem) {
            // Load related inward details
            $inwardModel = new InwardModel();
            $inwardDetails = $inwardModel->find($inwardItem['inward_id']);

            if ($inwardDetails) {
                $inwardItem['inward'] = $inwardDetails;
            }

            $data['inwarditem'] = $inwardItem;
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }

    public function update($id = null)
    {
        $model = new InwardItemModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'invoice_id' => 'required|numeric',
            'inward_id' => 'required|numeric',
            'grade_id' => 'required|numeric',
            'bag_type' => 'required',
            'sno_from' => 'required',
            'sno_to' => 'required',
            'weight_net' => 'required|numeric',
            'weight_tare' => 'required|numeric',
            'weight_gross' => 'required|numeric',
            'total_net' => 'required|numeric',
            'total_tare' => 'required|numeric',
            'total_gross' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'invoice_id' => $this->request->getVar('invoice_id'),
                'inward_id' => $this->request->getVar('inward_id'),
                'grade_id' => $this->request->getVar('grade_id'),
                'bag_type' => $this->request->getVar('bag_type'),
                'sno_from' => $this->request->getVar('sno_from'),
                'sno_to' => $this->request->getVar('sno_to'),
                'weight_net' => $this->request->getVar('weight_net'),
                'weight_tare' => $this->request->getVar('weight_tare'),
                'weight_gross' => $this->request->getVar('weight_gross'),
                'total_net' => $this->request->getVar('total_net'),
                'total_tare' => $this->request->getVar('total_tare'),
                'total_gross' => $this->request->getVar('total_gross'),
                'updated_by' => $this->request->getVar('session_user_id'),
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
        $model = new InwardItemModel();

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
}
