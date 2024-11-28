<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Libraries\JwtLibrary;


class User extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new UserModel();
        $data['users'] = $model->select('user.*, user_roles.role')
            ->join('user_roles', 'user_roles.id = user.role_id', 'left')
            ->findAll();
        return $this->respond($data);
    }

    public function create()
    {
        $model = new UserModel();
        $password = $this->request->getVar('password');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]',
            'username' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\-]*$/]|is_unique[user.username]',
            'password' => 'required',
            'email' => 'required|valid_email|is_unique[user.email]|min_length[6]',
            'phone' => 'required',
            'role_id' => 'required',
        ];

        $messages = [
            'name' => [
                'required' => 'The Name field is required.',
                'regex_match' => 'The Name field contains invalid characters.',
            ],
            'username' => [
                'required' => 'The Username field is required.',
                'regex_match' => 'The Username field contains invalid characters.',
                'is_unique' => 'The Username is already taken.',
            ],
            'password' => [
                'required' => 'The password field is required.',
            ],
            'email' => [
                'required' => 'The email field is required.',
                'valid_email' => 'Please enter a valid email address.',
                'is_unique' => 'The email address is already registered.',
                'min_length' => 'The email must be at least {param} characters long.',
            ],
            'phone' => [
                'required' => 'The phone field is required.',
            ],
            'role_id' => [
                'required' => 'The role field is required.',
            ],
        ];
        
        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'name' => $this->request->getVar('name'),
                'username' => $this->request->getVar('username'),
                'password' => $hashedPassword,
                'email'  => $this->request->getVar('email'),
                'phone'  => $this->request->getVar('phone'),
                'role_id'  => $this->request->getVar('role_id'),
                'created_by' => $this->request->getVar('created_by'),
                'status' => 1,
            ];

            $model->insert($data);

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'User Inserted Successfully',
                'data' => $data
            ];

            return $this->respondCreated($response);
        }
    }

    public function show($id = null)
    {
        $model = new UserModel();
        $data = $model->select('user.*, user_roles.role')
            ->join('user_roles', 'user_roles.id = user.role_id', 'left')
            ->where('user.id', $id)->first();

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No user found with id : ' . $id);
        }
    }
    public function update($id = null)
    {
        $model = new UserModel();
        $password = $this->request->getVar('password');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]',
            'username' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\-]*$/]|is_unique[user.username,id,' . $id . ']',
            // 'password' => 'required',
            'email' => 'required|valid_email|is_unique[user.email,id,' . $id . ']|min_length[6]',
            'phone' => 'required',
            'role_id' => 'required',
        ];

        $messages = [
            'name' => [
                'required' => 'The Name field is required.',
                'regex_match' => 'The Name field contains invalid characters.',
            ],
            'username' => [
                'required' => 'The Username field is required.',
                'regex_match' => 'The Username field contains invalid characters.',
                'is_unique' => 'The Username is already taken.',
            ],
            'password' => [
                'required' => 'The password field is required.',
            ],
            'email' => [
                'required' => 'The email field is required.',
                'valid_email' => 'Please enter a valid email address.',
                'is_unique' => 'The email address is already registered.',
                'min_length' => 'The email must be at least {param} characters long.',
            ],
            'phone' => [
                'required' => 'The phone field is required.',
            ],
            'role_id' => [
                'required' => 'The role field is required.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'name' => $this->request->getVar('name'),
                'username' => $this->request->getVar('username'),
                'email'  => $this->request->getVar('email'),
                'phone'  => $this->request->getVar('phone'),
                'role_id'  => $this->request->getVar('role_id'),
                'updated_by' => $this->request->getVar('updated_by'),
                'status' => 1,
            ];
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $data['password'] = $hashedPassword;
            }

            // Check if user exists
            $data1 = $model->find($id);

            if ($data1) {
                // Update user data
                $model->update($id, $data);

                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => 'User Updated Successfully',
                    'data' => $data // return updated data
                ];

                return $this->respond($response);
            } else {
                return $this->failNotFound('No Data Found with id : ' . $id);
            }
        }
    }
    public function delete($id = null)
    {
        $model = new UserModel();

        $data = $model->find($id);

        if ($data) {
            $model->delete($id);

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'User Deleted Successfully',
                'data' => $data
            ];

            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }
}
