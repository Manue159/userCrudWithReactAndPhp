<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

include 'DbConnect.php';
$objDb = new DbConnect;
$conn = $objDb->connect();

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case "GET":
        try {
            // Décomposer l'URI pour trouver l'ID
            $path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
            $indexPathPosition = array_search('api', $path);
            $id = isset($path[$indexPathPosition + 2]) ? $path[$indexPathPosition + 2] : null;

            $sql = "SELECT * FROM users";

            if ($id && is_numeric($id)) {
                $sql .= " WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    echo json_encode($user);
                } else {
                    http_response_code(404);
                    echo json_encode(["error" => "User not found"]);
                }
            } else {
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($users);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
        break;
    case "POST":
        $user = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO users(id, name, email, mobile, created_at) VALUES(null, :name, :email, :mobile, :created_at)";
        $stmt = $conn->prepare($sql);
        $created_at = date('Y-m-d');
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':mobile', $user->mobile);
        $stmt->bindParam(':created_at', $created_at);

        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record created successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to create record.'];
        }
        echo json_encode($response);
        break;

    case "PUT":
        $user = json_decode(file_get_contents('php://input'));
        $sql = "UPDATE users SET name= :name, email =:email, mobile =:mobile, updated_at =:updated_at WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $updated_at = date('Y-m-d');
        $stmt->bindParam(':id', $user->id);
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':mobile', $user->mobile);
        $stmt->bindParam(':updated_at', $updated_at);

        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record updated successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to update record.'];
        }
        echo json_encode($response);
        break;


    case "DELETE":
        $sql = "DELETE FROM users WHERE id = :id";
        $path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $indexPathPosition = array_search('api', $path);
        $id = isset($path[$indexPathPosition + 2]) ? $path[$indexPathPosition + 2] : null;

        if (!$id) {
            echo json_encode(['status' => 0, 'message' => 'ID not provided']);
            exit;
        }

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    echo json_encode(['status' => 1, 'message' => 'Record deleted successfully.']);
                } else {
                    echo json_encode(['status' => 0, 'message' => 'Record not found or already deleted.']);
                }
            } else {
                $errorInfo = $stmt->errorInfo();
                echo json_encode(['status' => 0, 'message' => 'Failed to delete record.', 'error' => $errorInfo]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 0, 'message' => 'Server error.', 'error' => $e->getMessage()]);
        }
        break;
}
