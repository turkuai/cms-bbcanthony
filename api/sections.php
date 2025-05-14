<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Adjust for security in production
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php'; // Adjust path if config.php is outside web root

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Fetch all sections
        $result = $conn->query('SELECT id, title, description, image_url FROM sections');
        $sections = [];
        while ($row = $result->fetch_assoc()) {
            $sections[] = $row;
        }
        echo json_encode($sections);
        break;

    case 'POST':
        // Add a new section
        $data = json_decode(file_get_contents('php://input'), true);
        $title = $conn->real_escape_string($data['title'] ?? '');
        $description = $conn->real_escape_string($data['description'] ?? '');
        $image_url = $conn->real_escape_string($data['image_url'] ?? '');

        if (empty($title) || empty($description) || empty($image_url)) {
            http_response_code(400);
            echo json_encode(['error' => 'Title, description, and image_url are required']);
            break;
        }

        $stmt = $conn->prepare('INSERT INTO sections (title, description, image_url) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $title, $description, $image_url);
        if ($stmt->execute()) {
            echo json_encode(['id' => $conn->insert_id, 'title' => $title, 'description' => $description, 'image_url' => $image_url]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add section']);
        }
        $stmt->close();
        break;

    case 'PUT':
        // Update an existing section
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $conn->real_escape_string($data['id'] ?? '');
        $title = $conn->real_escape_string($data['title'] ?? '');
        $description = $conn->real_escape_string($data['description'] ?? '');
        $image_url = $conn->real_escape_string($data['image_url'] ?? '');

        if (empty($id) || empty($title) || empty($description) || empty($image_url)) {
            http_response_code(400);
            echo json_encode(['error' => 'ID, title, description, and image_url are required']);
            break;
        }

        $stmt = $conn->prepare('UPDATE sections SET title = ?, description = ?, image_url = ? WHERE id = ?');
        $stmt->bind_param('sssi', $title, $description, $image_url, $id);
        if ($stmt->execute()) {
            echo json_encode(['id' => $id, 'title' => $title, 'description' => $description, 'image_url' => $image_url]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update section']);
        }
        $stmt->close();
        break;

    case 'DELETE':
        // Delete a section
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $conn->real_escape_string($data['id'] ?? '');

        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'ID is required']);
            break;
        }

        $stmt = $conn->prepare('DELETE FROM sections WHERE id = ?');
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete section']);
        }
        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

$conn->close();
?>