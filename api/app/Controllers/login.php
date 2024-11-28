<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\UserModel;
use App\Models\BuyerModel;
use App\Models\ActivityLog;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use App\Libraries\JwtLibrary;
use App\Models\RoleRightsModel;

class login extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property
    private $jwtLib;

    public function __construct()
    {
        $this->jwtLib = new JwtLibrary();
        $this->session = Services::session(); // Initialize session in the constructor
    }
    // Login
    public function loginAuth()
    {


        $rules = [
            "email" => "required|valid_email|min_length[6]",
            "password" => "required",
        ];

        $messages = [
            "email" => [
                "required" => "Email required",
                "valid_email" => "Email address is not in the correct format"
            ],
            "password" => [
                "required" => "Password is required"
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $response = [
                'status' => 422,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];

            return $this->respond($response);
        } else {
            $userModel = new UserModel();

            $userdata = $userModel->where("email", $this->request->getVar("email"))->where("role_id", $this->request->getVar("role_id"))->where("status", 1)->first();
            // print_r($userdata);
            // exit;
            if (!empty($userdata)) {
                // $password = $this->request->getVar('password');
                // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                // print_r($hashedPassword);exit;
                if (password_verify($this->request->getVar("password"), $userdata['password'])) {
                    // Save user ID in session
                    $this->session->set('session_user_id', $userdata['id']);
                    $session_user_id = $this->session->get('session_user_id');

                    $headers = array('alg' => 'HS256', 'typ' => 'JWT');
                    $payload = array('username' => $userdata['username'], 'exp' => (time() + 60));

                    $token = $this->jwtLib->generate_jwt($headers, $payload);

                    // Session ID

                    // Update token in user table
                    $userdata['token'] = $token;

                    $db = \Config\Database::connect();
                    $builder = $db->table('user');
                    $builder->where('id', $userdata['id']);
                    $builder->update(['token' => $userdata['token']]);

                    $this->session->set('access_token', $userdata['token']);
                    $permissions = array();
                    $role_rights = new RoleRightsModel();
                    $permissions = $role_rights->select('role_rights.*,modules.name as module_name')->join('modules', 'modules.id = role_rights.module_id', 'left')
                        ->where('role_rights.role_id', $userdata['role_id'])->findAll();

                    $activityLogModel = new ActivityLog();
                    $description = "Logged in with data:" . json_encode($userdata);
                    $log_data = [
                        'user_id'    => $userdata['id'],
                        'action'     => "login",
                        'description' => $description,
                        'table_name' => "user",
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $activityLogModel->save($log_data);

                    $response = [
                        'status' => 200,
                        'error' => false,
                        'message' => 'User logged in successfully',
                        'data' => [
                            'token' => $token,
                            'session_user_id' => $session_user_id,
                            'permissions' => $permissions,
                            'role_id' => $userdata['role_id'],
                            'user_name' => $userdata['name']
                        ]
                    ];
                } else {
                    // Incorrect password
                    $response = [
                        'status' => 505,
                        'error' => true,
                        'message' => 'Incorrect email or password',
                        'data' => []
                    ];
                }
            } else {
                // User not found
                $response = [
                    'status' => 500,
                    'error' => true,
                    'message' => 'User not found',
                    'data' => []
                ];
            }
            return $this->respond($response);
        }
    }

    public function loginBuyerAuth()
    {


        $rules = [
            "email" => "required|valid_email|min_length[6]",
            "password" => "required",
        ];

        $messages = [
            "email" => [
                "required" => "Email required",
                "valid_email" => "Email address is not in the correct format"
            ],
            "password" => [
                "required" => "Password is required"
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $response = [
                'status' => 422,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];

            return $this->respond($response);
        } else {
            $buyerModel = new BuyerModel();
            $email = $this->request->getVar("email");
            $password = $this->request->getVar("password");
            $userdata = $buyerModel->where("email", $email)->first();

            if (!empty($userdata)) {
                if (password_verify($password, $userdata['password'])) {
                    // Check if user is active
                    if ($userdata['status'] == 2) {
                        $response = [
                            'status' => 505,
                            'error' => true,
                            'message' => 'User Inactive',
                            'data' => []
                        ];
                    } else {
                        $buyer_id = $userdata['id'];
                        $this->session->set('session_user_id', $buyer_id);
                        $session_user_id = $this->session->get('session_user_id');

                        $headers = ['alg' => 'HS256', 'typ' => 'JWT'];
                        $payload = ['username' => $userdata['email'], 'exp' => (time() + 60)];

                        $token = $this->jwtLib->generate_jwt($headers, $payload);

                        // Update token in user table
                        $userdata['token'] = $token;

                        $db = \Config\Database::connect();
                        $builder = $db->table('buyer');
                        $builder->where('id', $userdata['id']);
                        $builder->update(['token' => $userdata['token']]);

                        $this->session->set('access_token', $userdata['token']);

                        $activityLogModel = new ActivityLog();
                        $description = "Logged in with data:" . json_encode($userdata);
                        $log_data = [
                            'user_id'    => $userdata['id'],
                            'action'     => "login",
                            'description' => $description,
                            'table_name' => "buyer",
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        $activityLogModel->save($log_data);

                        $response = [
                            'status' => 200,
                            'error' => false,
                            'message' => 'Buyer logged in successfully',
                            'data' => [
                                'token' => $token,
                                'user_id' => $buyer_id,
                                'role' => 'buyer',
                                'buyer_name' => $userdata['name']
                            ]
                        ];
                    }
                } else {
                    // Incorrect password
                    $response = [
                        'status' => 505,
                        'error' => true,
                        'message' => 'Incorrect email or password',
                        'data' => []
                    ];
                }
            } else {
                // User not found
                $response = [
                    'status' => 500,
                    'error' => true,
                    'message' => 'User not found',
                    'data' => []
                ];
            }

            return $this->respond($response);
        }
    }

    public function logout()
    {
        $jwtLib = new JwtLibrary(); // Instantiate your JWT library
        $bearer_token = $jwtLib->get_bearer_token();
        session()->destroy();

        $db = \Config\Database::connect();

        $builder = $db->table('user');
        $builder->set('token', null);
        $builder->where('token', $bearer_token);
        $builder->update();

        $activityLogModel = new ActivityLog();
        $description = "Logged out with data:{}";
        $log_data = [
            'user_id'    => $this->request->getHeaderLine('Authorization1'),
            'action'     => "logout",
            'description' => $description,
            'table_name' => "user",
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $activityLogModel->save($log_data);

        $data = [
            'token' => $bearer_token
        ];
        $db->table('blacklisted_tokens')->insert($data);

        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'User logged out successfully',
        ];

        return $this->respond($response);
    }

    public function buyerlogout()
    {
        $jwtLib = new JwtLibrary(); // Instantiate your JWT library
        $bearer_token = $jwtLib->get_bearer_token();
        session()->destroy();

        $db = \Config\Database::connect();

        $builder = $db->table('buyer');
        $builder->set('token', null);
        $builder->where('token', $bearer_token);
        $builder->update();

        $activityLogModel = new ActivityLog();
        $description = "Logged out with data:{}";
        $log_data = [
            'user_id'    => $this->request->getHeaderLine('Authorization1'),
            'action'     => "logout",
            'description' => $description,
            'table_name' => "buyer",
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $activityLogModel->save($log_data);

        $data = [
            'token' => $bearer_token
        ];
        $db->table('blacklisted_tokens')->insert($data);

        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Buyer logged out successfully',
        ];

        return $this->respond($response);
    }
}
