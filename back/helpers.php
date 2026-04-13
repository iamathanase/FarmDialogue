<?php
/**
 * Database Helper Functions
 * Utility functions for database operations
 */

/**
 * Execute a SELECT query with prepared statement
 */
function executeSelect($conn, $query, $params = [], $paramTypes = "") {
    try {
        $stmt = $conn->prepare($query);
        
        if (!empty($params) && !empty($paramTypes)) {
            $stmt->bind_param($paramTypes, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result;
    } catch (Exception $e) {
        error_log("Database Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Execute INSERT/UPDATE/DELETE query
 */
function executeQuery($conn, $query, $params = [], $paramTypes = "") {
    try {
        $stmt = $conn->prepare($query);
        
        if (!empty($params) && !empty($paramTypes)) {
            $stmt->bind_param($paramTypes, ...$params);
        }
        
        $stmt->execute();
        
        return [
            'success' => true,
            'affectedRows' => $stmt->affected_rows,
            'insertId' => $stmt->insert_id,
            'message' => 'Query executed successfully'
        ];
    } catch (Exception $e) {
        error_log("Database Error: " . $e->getMessage());
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

/**
 * Get single row from query
 */
function getSingleRow($conn, $query, $params = [], $paramTypes = "") {
    $result = executeSelect($conn, $query, $params, $paramTypes);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

/**
 * Get all rows from query
 */
function getAllRows($conn, $query, $params = [], $paramTypes = "") {
    $result = executeSelect($conn, $query, $params, $paramTypes);
    
    if (!$result) {
        return [];
    }
    
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    
    return $rows;
}

/**
 * Get total count of rows
 */
function getRowCount($conn, $query, $params = [], $paramTypes = "") {
    $result = executeSelect($conn, $query, $params, $paramTypes);
    
    return $result ? $result->num_rows : 0;
}

/**
 * Sanitize input
 */
function sanitizeInput($input) {
    if (is_array($input)) {
        foreach ($input as $key => $value) {
            $input[$key] = sanitizeInput($value);
        }
        return $input;
    }
    
    return htmlspecialchars(stripslashes(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Hash password using bcrypt
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Generate random token
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Validate required fields
 */
function validateRequiredFields($data, $required) {
    $missing = [];
    
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            $missing[] = $field;
        }
    }
    
    return [
        'isValid' => empty($missing),
        'missing' => $missing
    ];
}

/**
 * Validate file upload
 */
function validateFileUpload($file, $allowedExtensions = []) {
    $errors = [];
    
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'File upload failed';
        return ['isValid' => false, 'errors' => $errors];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        $errors[] = 'File size exceeds limit';
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!empty($allowedExtensions) && !in_array($extension, $allowedExtensions)) {
        $errors[] = 'File type not allowed';
    }
    
    return [
        'isValid' => empty($errors),
        'errors' => $errors,
        'extension' => $extension
    ];
}

/**
 * Create upload directory if not exists
 */
function ensureUploadDir($dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

/**
 * Get file upload path
 */
function getUploadPath($filename, $subdir = '') {
    $dir = UPLOAD_DIR . ($subdir ? $subdir . '/' : '');
    ensureUploadDir($dir);
    return $dir . time() . '_' . basename($filename);
}

/**
 * Create pagination
 */
function getPagination($currentPage = 1, $totalRecords = 0, $recordsPerPage = 10) {
    $totalPages = ceil($totalRecords / $recordsPerPage);
    $offset = ($currentPage - 1) * $recordsPerPage;
    
    return [
        'currentPage' => $currentPage,
        'totalPages' => $totalPages,
        'totalRecords' => $totalRecords,
        'recordsPerPage' => $recordsPerPage,
        'offset' => $offset,
        'hasNextPage' => $currentPage < $totalPages,
        'hasPrevPage' => $currentPage > 1
    ];
}

/**
 * Encode JWT token (simplified version - use proper JWT library in production)
 */
function generateJWT($userId, $role) {
    $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload = base64_encode(json_encode([
        'userId' => $userId,
        'role' => $role,
        'iat' => time(),
        'exp' => time() + 86400 // 24 hours
    ]));
    $signature = base64_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    
    return "$header.$payload.$signature";
}

/**
 * Verify JWT token (simplified version)
 */
function verifyJWT($token) {
    $parts = explode('.', $token);
    
    if (count($parts) !== 3) {
        return false;
    }
    
    $header = $parts[0];
    $payload = $parts[1];
    $signature = $parts[2];
    $expectedSignature = base64_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    
    if (!hash_equals($signature, $expectedSignature)) {
        return false;
    }
    
    $decoded = json_decode(base64_decode($payload), true);
    
    if ($decoded['exp'] < time()) {
        return false;
    }
    
    return $decoded;
}

/**
 * Log activity
 */
function logActivity($conn, $userId, $action, $description, $table = null, $recordId = null) {
    if ($userId && isset($_SESSION['userRole']) && $_SESSION['userRole'] === 'admin') {
        $query = "INSERT INTO admin_logs (admin_id, action, description, affected_table, affected_id) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssi", $userId, $action, $description, $table, $recordId);
        $stmt->execute();
    }
}

/**
 * Send email notification
 */
function sendNotificationEmail($to, $subject, $message) {
    // In production, use PHPMailer or similar
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: " . MAIL_FROM . "\r\n";
    
    return mail($to, $subject, $message, $headers);
}

?>
