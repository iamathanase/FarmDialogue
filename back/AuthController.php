<?php
/**
 * Authentication Controller
 * Handles user registration, login, and authentication
 */

require_once 'config.php';

class AuthController {
    private $conn;
    
    public function __construct($database) {
        $this->conn = $database;
    }
    
    /**
     * User Registration
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            sendError('Method not allowed', 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
        if (!isset($input['email']) || !isset($input['password'])) {
            sendError('Email and password are required', 400);
        }
        
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $firstName = htmlspecialchars($input['firstName'] ?? 'User');
        $lastName = htmlspecialchars($input['lastName'] ?? '');
        $phone = htmlspecialchars($input['phone'] ?? '');
        $role = htmlspecialchars($input['role'] ?? 'customer');
        $password = password_hash($input['password'], PASSWORD_BCRYPT);
        
        // Check if user already exists
        $checkQuery = "SELECT user_id FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            sendError('Email already registered', 409);
        }
        
        // Insert new user
        $insertQuery = "INSERT INTO users (first_name, last_name, email, phone, password, role, is_verified) 
                       VALUES (?, ?, ?, ?, ?, ?, FALSE)";
        $stmt = $this->conn->prepare($insertQuery);
        $stmt->bind_param("ssssss", $firstName, $lastName, $email, $phone, $password, $role);
        
        if ($stmt->execute()) {
            $userId = $stmt->insert_id;
            sendResponse([
                'userId' => $userId,
                'email' => $email,
                'message' => 'Registration successful'
            ], 201);
        } else {
            sendError('Registration failed', 500);
        }
    }
    
    /**
     * User Login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            sendError('Method not allowed', 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['email']) || !isset($input['password'])) {
            sendError('Email and password are required', 400);
        }
        
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        
        // Get user
        $query = "SELECT user_id, first_name, password, role, is_verified FROM users WHERE email = ? AND is_active = TRUE";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            sendError('Invalid credentials', 401);
        }
        
        $user = $result->fetch_assoc();
        
        // Verify password
        if (!password_verify($input['password'], $user['password'])) {
            sendError('Invalid credentials', 401);
        }
        
        // Generate JWT token (simplified - in production use a proper JWT library)
        $token = bin2hex(random_bytes(32));
        
        sendResponse([
            'token' => $token,
            'userId' => $user['user_id'],
            'userName' => $user['first_name'],
            'userRole' => $user['role'],
            'isVerified' => $user['is_verified'],
            'message' => 'Login successful'
        ], 200);
    }
    
    /**
     * Verify Email Token
     */
    public function verifyEmail() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            sendError('Method not allowed', 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['token'])) {
            sendError('Verification token required', 400);
        }
        
        sendResponse(['message' => 'Email verification endpoint'], 200);
    }
    
    /**
     * Password Reset Request
     */
    public function passwordResetRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            sendError('Method not allowed', 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['email'])) {
            sendError('Email is required', 400);
        }
        
        sendResponse(['message' => 'Password reset email sent'], 200);
    }
}

// Endpoint routing
$action = $_GET['action'] ?? 'login';
$authController = new AuthController($conn);

switch ($action) {
    case 'register':
        $authController->register();
        break;
    case 'login':
        $authController->login();
        break;
    case 'verify-email':
        $authController->verifyEmail();
        break;
    case 'password-reset':
        $authController->passwordResetRequest();
        break;
    default:
        sendError('Action not found', 404);
        break;
}
?>
