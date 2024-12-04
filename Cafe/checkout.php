<?php
session_start();
include('db.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decode the JSON payload from the frontend
    $data = json_decode(file_get_contents('php://input'), true);

    // Get user details from the session
    $userName = $_SESSION['user_name'];
    $userEmail = $_SESSION['user_email'];
    $productsOrdered = implode(", ", array_map(function($item) {
        return $item['name'];
    }, $data['cart'])); // Convert cart items to a string
    $totalBill = $data['totalBill'];
    $sessionId = session_id(); // Get the current session ID

    // Insert order details into the database
    $stmt = $conn->prepare("INSERT INTO orders (user_name, user_email, products_ordered, total_bill, session_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $userName, $userEmail, $productsOrdered, $totalBill, $sessionId);

    if ($stmt->execute()) {
        // If the query is successful
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();
}
?>
