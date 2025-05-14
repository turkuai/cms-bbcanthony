<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Adjust for security in production
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$configPath = __DIR__ . '/../config.php';
if (!file_exists($configPath)) {
    die(json_encode(['error' => 'config.php not found at ' . $configPath]));
}
require_once $configPath;

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Fetch all links
        $result = $conn->query('SELECT id, name, href FROM links');
        $links = [];
        while ($row = $result->fetch_assoc()) {
            $links[] = $row;
        }
        echo json_encode($links);
        break;

    case 'POST':
        // Add a new link
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $conn->real_escape_string($data['name'] ?? '');
        $href = $conn->real_escape_string($data['href'] ?? '');

        if (empty($name) || empty($href)) {
            http_response_code(400);
            echo json_encode(['error' => 'Name and href are required']);
            break;
        }

        $stmt = $conn->prepare('INSERT INTO links (name, href) VALUES (?, ?)');
        $stmt->bind_param('ss', $name, $href);
        if ($stmt->execute()) {
            echo json_encode(['id' => $conn->insert_id, 'name' => $name, 'href' => $href]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add link']);
        }
        $stmt->close();
        break;

    case 'PUT':
        // Update an existing link
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $conn->real_escape_string($data['id'] ?? '');
        $name = $conn->real_escape_string($data['name'] ?? '');
        $href = $conn->real_escape_string($data['href'] ?? '');

        if (empty($id) || empty($name) || empty($href)) {
            http_response_code(400);
            echo json_encode(['error' => 'ID, name, and href are required']);
            break;
        }

        $stmt = $conn->prepare('UPDATE links SET name = ?, href = ? WHERE id = ?');
        $stmt->bind_param('ssi', $name, $href, $id);
        if ($stmt->execute()) {
            echo json_encode(['id' => $id, 'name' => $name, 'href' => $href]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update link']);
        }
        $stmt->close();
        break;

    case 'DELETE':
        // Delete a link
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $conn->real_escape_string($data['id'] ?? '');

        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'ID is required']);
            break;
        }

        $stmt = $conn->prepare('DELETE FROM links WHERE id = ?');
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete link']);
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