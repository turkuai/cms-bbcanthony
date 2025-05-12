<?php
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['text'])) {
    file_put_contents("../data/footer.json", json_encode(['text' => $data['text']]));
    echo json_encode(["message" => "Footer saved successfully"]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Invalid input"]);
}
