<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        handleGet($pdo);
        break;
    case 'POST':
        handlePost($pdo, $input);
        break;
    case 'PUT':
        handlePut($pdo, $input);
        break;
    case 'DELETE':
        handleDelete($pdo, $input);
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function handleGet($pdo) {
    $sql = "SELECT * FROM todos";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}

function handlePost($pdo, $input) {
    $sql = "INSERT INTO todos (todo_title, todo_body) VALUES (:todo_title, :todo_body)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['todo_title' => $input['todo_title'], 'todo_body' => $input['todo_body']]);
    echo json_encode(['message' => 'Todo created successfully']);
}

function handlePut($pdo, $input) {
    $sql = "UPDATE todos SET todo_title = :todo_title, todo_body = :todo_body WHERE todo_id = :todo_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['todo_title' => $input['todo_title'], 'todo_body' => $input['todo_body'], 'todo_id' => $input['todo_id']]);
    echo json_encode(['message' => 'Todo updated successfully']);
}

function handleDelete($pdo, $input) {
    $sql = "DELETE FROM todos WHERE todo_id = :todo_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['todo_id' => $input['todo_id']]);
    echo json_encode(['message' => 'Todo deleted successfully']);
}
?> 