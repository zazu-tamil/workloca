<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper('jwt'); // ✅ Load JWT helper
        header('Content-Type: application/json');
    }

    // ✅ LOGIN (GET)
    public function login_get()
    {
        $username = $this->input->get('username');
        $password = $this->input->get('password');

        // ✅ Debug: Check if inputs are received
        if (empty($username) || empty($password)) {
            http_response_code(400);
            echo json_encode(['status' => false, 'message' => 'Missing username or password']);
            return;
        }

        $user = $this->User_model->check_login($username, $password);

        if ($user) {
            $token = generate_jwt([
                'user_id' => $user->user_id,
                'user_name' => $user->user_name,
                'level' => $user->level
            ]);

            http_response_code(200);
            echo json_encode([
                'status' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => $user->user_id,
                    'name' => $user->staff_name,
                    'level' => $user->level
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['status' => false, 'message' => 'Invalid credentials']);
        }
    }

    // ✅ REGISTER (POST)
    public function register_post()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['user_name']) || empty($data['user_pwd'])) {
            http_response_code(400);
            echo json_encode(['status' => false, 'message' => 'Username and Password required']);
            return;
        }

        $insert = [
            'staff_name' => $data['staff_name'] ?? '',
            'user_name' => $data['user_name'],
            'user_pwd' => $data['user_pwd'], // plain text
            'level' => $data['level'] ?? 'User',
            'ref_id' => $data['ref_id'] ?? 0,
            'status' => 'Active'
        ];

        if ($this->db->insert('user_login_info', $insert)) {
            http_response_code(201);
            echo json_encode([
                'status' => true,
                'message' => 'User registered successfully',
                'user_id' => $this->db->insert_id()
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => false, 'message' => 'Database insert failed']);
        }
    }

    // ✅ TOKEN TEST (GET)
    public function test_token_get()
    {
        $token = $this->input->get('token');
        $decoded = verify_jwt($token);

        if ($decoded) {
            http_response_code(200);
            echo json_encode(['status' => true, 'decoded' => $decoded]);
        } else {
            http_response_code(401);
            echo json_encode(['status' => false, 'message' => 'Invalid or expired token']);
        }
    }

    // ✅ PROFILE (Protected API)
    public function profile_get()
    {
        $headers = $this->input->request_headers();
        
        // ✅ Check Authorization header first, then fallback to token parameter
        $auth = isset($headers['Authorization']) ? $headers['Authorization'] : $this->input->get('token');

        if (empty($auth)) {
            http_response_code(401);
            echo json_encode(['status' => false, 'message' => 'No token provided']);
            return;
        }

        // ✅ Extract Bearer token
        $token = $auth;
        if (preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
            $token = $matches[1];
        }

        $data = verify_jwt($token);

        if ($data) {
            $user = $this->User_model->get_user($data['user_id']);
            http_response_code(200);
            echo json_encode(['status' => true, 'user' => $user]);
        } else {
            http_response_code(401);
            echo json_encode(['status' => false, 'message' => 'Unauthorized or Invalid Token']);
        }
    }
}
