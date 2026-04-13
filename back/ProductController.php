<?php
/**
 * Product Controller
 * Handles product listing, creation, and management
 */

require_once 'config.php';

class ProductController {
    private $conn;
    
    public function __construct($database) {
        $this->conn = $database;
    }
    
    /**
     * Get all products with filtering
     */
    public function getProducts() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            sendError('Method not allowed', 405);
        }
        
        $category = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? null;
        $limit = min($_GET['limit'] ?? 20, 100);
        $offset = $_GET['offset'] ?? 0;
        
        $query = "SELECT p.*, u.first_name, u.last_name, u.role 
                  FROM products p
                  JOIN users u ON p.user_id = u.user_id
                  WHERE p.is_available = TRUE";
        
        $params = [];
        $paramTypes = "";
        
        if ($category) {
            $query .= " AND p.category = ?";
            $params[] = $category;
            $paramTypes .= "s";
        }
        
        if ($search) {
            $searchTerm = "%$search%";
            $query .= " AND (p.product_name LIKE ? OR p.description LIKE ?)";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $paramTypes .= "ss";
        }
        
        $query .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $paramTypes .= "ii";
        
        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($paramTypes, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        sendResponse([
            'products' => $products,
            'count' => count($products),
            'limit' => $limit,
            'offset' => $offset
        ], 200);
    }
    
    /**
     * Get single product details
     */
    public function getProduct($productId) {
        $query = "SELECT p.*, u.first_name, u.last_name, u.role
                  FROM products p
                  JOIN users u ON p.user_id = u.user_id
                  WHERE p.product_id = ? AND p.is_available = TRUE";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            sendError('Product not found', 404);
        }
        
        $product = $result->fetch_assoc();
        sendResponse($product, 200);
    }
    
    /**
     * Create new product (requires authentication)
     */
    public function createProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            sendError('Method not allowed', 405);
        }
        
        // Check authentication
        $token = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        if (!$token) {
            sendError('Unauthorized', 401);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        $required = ['userId', 'productName', 'price', 'quantityAvailable'];
        foreach ($required as $field) {
            if (!isset($input[$field])) {
                sendError("$field is required", 400);
            }
        }
        
        $userId = intval($input['userId']);
        $productName = htmlspecialchars($input['productName']);
        $description = htmlspecialchars($input['description'] ?? '');
        $category = htmlspecialchars($input['category'] ?? 'general');
        $price = floatval($input['price']);
        $quantity = intval($input['quantityAvailable']);
        $unit = htmlspecialchars($input['unit'] ?? 'kg');
        
        $query = "INSERT INTO products (user_id, product_name, description, category, price, quantity_available, unit)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssids", $userId, $productName, $description, $category, $price, $quantity, $unit);
        
        if ($stmt->execute()) {
            sendResponse([
                'productId' => $stmt->insert_id,
                'message' => 'Product created successfully'
            ], 201);
        } else {
            sendError('Product creation failed', 500);
        }
    }
    
    /**
     * Update product
     */
    public function updateProduct($productId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            sendError('Method not allowed', 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $query = "UPDATE products SET ";
        $updates = [];
        $params = [];
        $paramTypes = "";
        
        if (isset($input['productName'])) {
            $updates[] = "product_name = ?";
            $params[] = htmlspecialchars($input['productName']);
            $paramTypes .= "s";
        }
        
        if (isset($input['price'])) {
            $updates[] = "price = ?";
            $params[] = floatval($input['price']);
            $paramTypes .= "d";
        }
        
        if (isset($input['quantityAvailable'])) {
            $updates[] = "quantity_available = ?";
            $params[] = intval($input['quantityAvailable']);
            $paramTypes .= "i";
        }
        
        if (isset($input['isAvailable'])) {
            $updates[] = "is_available = ?";
            $params[] = intval($input['isAvailable']);
            $paramTypes .= "i";
        }
        
        if (empty($updates)) {
            sendError('No fields to update', 400);
        }
        
        $query .= implode(", ", $updates);
        $query .= " WHERE product_id = ?";
        $params[] = $productId;
        $paramTypes .= "i";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($paramTypes, ...$params);
        
        if ($stmt->execute()) {
            sendResponse(['message' => 'Product updated successfully'], 200);
        } else {
            sendError('Product update failed', 500);
        }
    }
    
    /**
     * Delete product
     */
    public function deleteProduct($productId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            sendError('Method not allowed', 405);
        }
        
        $query = "DELETE FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $productId);
        
        if ($stmt->execute()) {
            sendResponse(['message' => 'Product deleted successfully'], 200);
        } else {
            sendError('Product deletion failed', 500);
        }
    }
}

// Endpoint routing
$action = $_GET['action'] ?? 'list';
$productId = $_GET['id'] ?? null;

$productController = new ProductController($conn);

switch ($action) {
    case 'list':
        $productController->getProducts();
        break;
    case 'get':
        if (!$productId) {
            sendError('Product ID required', 400);
        }
        $productController->getProduct($productId);
        break;
    case 'create':
        $productController->createProduct();
        break;
    case 'update':
        if (!$productId) {
            sendError('Product ID required', 400);
        }
        $productController->updateProduct($productId);
        break;
    case 'delete':
        if (!$productId) {
            sendError('Product ID required', 400);
        }
        $productController->deleteProduct($productId);
        break;
    default:
        sendError('Action not found', 404);
        break;
}
?>
