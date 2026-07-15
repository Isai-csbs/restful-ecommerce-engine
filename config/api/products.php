<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$request_method = $_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        // Fetch all products cleanly
        $query = "SELECT id, title, description, price, stock_quantity FROM products ORDER BY id DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        http_response_code(200);
        echo json_encode(array("status" => "success", "data" => $products));
        break;
        
    case 'POST':
        // Securely add a new product (Admin feature imitation)
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->title) && !empty($data->price) && isset($data->stock_quantity)) {
            $query = "INSERT INTO products (title, description, price, stock_quantity) 
                      VALUES (:title, :description, :price, :stock_quantity)";
            
            $stmt = $db->prepare($query);
            
            // Sanitize inputs to prevent XSS/SQL Injection
            $title = htmlspecialchars(strip_tags($data->title));
            $description = htmlspecialchars(strip_tags($data->description ?? ''));
            $price = filter_var($data->price, FILTER_VALIDATE_FLOAT);
            $stock = filter_var($data->stock_quantity, FILTER_VALIDATE_INT);
            
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock_quantity', $stock);
            
            if($stmt->execute()) {
                http_response_code(201);
                echo json_encode(array("status" => "success", "message" => "Product created successfully."));
            } else {
                http_response_code(500);
                echo json_encode(array("status" => "error", "message" => "Unable to create product."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("status" => "error", "message" => "Incomplete data payload."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("status" => "error", "message" => "Method Not Allowed"));
        break;
}
?>
