<?php
/**
 * FarmDialogue Backend API Router
 */

require_once 'config.php';

// Get request method and path
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestPath = str_replace('/farmdialogue/back/', '', $requestPath);

// Simple router
$route = explode('/', trim($requestPath, '/'));
$controller = $route[0] ?? 'home';
$action = $route[1] ?? 'index';

// Route to appropriate controller
switch ($controller) {
    case 'api':
        if (isset($route[1])) {
            $apiEndpoint = $route[1];
            routeAPI($apiEndpoint, $action, $requestMethod);
        }
        break;
    
    case 'admin':
        echo json_encode(['message' => 'Admin panel']);
        break;
    
    default:
        sendResponse(['message' => 'FarmDialogue API v1.0 - Welcome!']);
        break;
}

// API Router function
function routeAPI($endpoint, $action, $method) {
    switch ($endpoint) {
        case 'users':
            handleUserEndpoint($action, $method);
            break;
        
        case 'auth':
            handleAuthEndpoint($action, $method);
            break;
        
        case 'products':
            handleProductEndpoint($action, $method);
            break;
        
        case 'messages':
            handleMessageEndpoint($action, $method);
            break;
        
        case 'vendors':
            handleVendorEndpoint($action, $method);
            break;
        
        default:
            sendError('Endpoint not found', 404);
            break;
    }
}

// User endpoint handler
function handleUserEndpoint($action, $method) {
    switch ($action) {
        case 'index':
        case 'list':
            if ($method === 'GET') {
                sendResponse(['message' => 'User list endpoint']);
            }
            break;
        
        case 'profile':
            if ($method === 'GET') {
                sendResponse(['message' => 'User profile endpoint']);
            }
            break;
        
        default:
            sendError('User action not found', 404);
            break;
    }
}

// Auth endpoint handler
function handleAuthEndpoint($action, $method) {
    switch ($action) {
        case 'register':
            if ($method === 'POST') {
                sendResponse(['message' => 'Register endpoint']);
            }
            break;
        
        case 'login':
            if ($method === 'POST') {
                sendResponse(['message' => 'Login endpoint']);
            }
            break;
        
        case 'logout':
            if ($method === 'POST') {
                sendResponse(['message' => 'Logout endpoint']);
            }
            break;
        
        default:
            sendError('Auth action not found', 404);
            break;
    }
}

// Product endpoint handler
function handleProductEndpoint($action, $method) {
    switch ($action) {
        case 'list':
            if ($method === 'GET') {
                sendResponse(['message' => 'Product list endpoint']);
            }
            break;
        
        case 'create':
            if ($method === 'POST') {
                sendResponse(['message' => 'Product create endpoint']);
            }
            break;
        
        default:
            sendError('Product action not found', 404);
            break;
    }
}

// Message endpoint handler
function handleMessageEndpoint($action, $method) {
    switch ($action) {
        case 'send':
            if ($method === 'POST') {
                sendResponse(['message' => 'Message send endpoint']);
            }
            break;
        
        case 'list':
            if ($method === 'GET') {
                sendResponse(['message' => 'Message list endpoint']);
            }
            break;
        
        default:
            sendError('Message action not found', 404);
            break;
    }
}

// Vendor endpoint handler
function handleVendorEndpoint($action, $method) {
    switch ($action) {
        case 'list':
            if ($method === 'GET') {
                sendResponse(['message' => 'Vendor list endpoint']);
            }
            break;
        
        case 'register':
            if ($method === 'POST') {
                sendResponse(['message' => 'Vendor register endpoint']);
            }
            break;
        
        default:
            sendError('Vendor action not found', 404);
            break;
    }
}
?>
