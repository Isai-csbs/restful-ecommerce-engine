<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");

session_start(); // Initialize session to track cart contents

$request_method = $_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        // Retrieve current cart items from the session
        $cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        http_response_code(200);
        echo json_encode(array("status" => "success", "cart" => $cart_items));
        break;

    case 'POST':
        // Add a product item into the session-based cart array
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->product_id)) {
            $p_id = filter_var($data->product_id, FILTER_VALIDATE_INT);
            $qty = isset($data->quantity) ? filter_var($data->quantity, FILTER_VALIDATE_INT) : 1;
            
            if(!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Add or update item quantity
            $_SESSION['cart'][$p_id] = ($_SESSION['cart'][$p_id] ?? 0) + $qty;
            
            http_response_code(200);
            echo json_encode(array("status" => "success", "message" => "Item added to cart."));
        } else {
            http_response_code(400);
            echo json_encode(array("status" => "error", "message" => "Invalid product mapping data."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("status" => "error", "message" => "Method Not Allowed"));
        break;
}
?>
