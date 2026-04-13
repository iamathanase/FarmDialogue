<?php
/**
 * FarmDialogue Backend Configuration
 * Database connection and global settings
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Change to your database password
define('DB_NAME', 'farmdialogue');
define('DB_PORT', 3306);

// API Configuration
define('API_BASE_URL', 'http://localhost/farmdialogue/back/api/');
define('FRONTEND_URL', 'http://localhost/farmdialogue/front/');

// JWT Secret Key (Change this to a strong secret)
define('JWT_SECRET', 'your_jwt_secret_key_change_this_2026');

// Application Settings
define('APP_NAME', 'FarmDialogue');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // 'development' or 'production'

// Security Settings
define('HASH_ALGORITHM', 'bcrypt');
define('SESSION_TIMEOUT', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutes

// File Upload Settings
define('MAX_FILE_SIZE', 5242880); // 5MB
define('UPLOAD_DIR', '../uploads/');
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);

// Email Settings
define('MAIL_FROM', 'noreply@farmdialogue.com');
define('MAIL_FROM_NAME', 'FarmDialogue');

// Enable error reporting for development
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Create database connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if ($conn->connect_error) {
        throw new Exception("Database Connection Failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection error']);
    exit();
}

// Helper function for sending JSON responses
function sendResponse($data = null, $statusCode = 200, $message = null) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    
    $response = [
        'status' => $statusCode,
        'data' => $data,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    echo json_encode($response);
    exit();
}

// Helper function for error responses
function sendError($message, $statusCode = 400) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    
    $response = [
        'status' => $statusCode,
        'error' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    echo json_encode($response);
    exit();
}

// Enable CORS for frontend communication
header('Access-Control-Allow-Origin: ' . FRONTEND_URL);
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
?>
