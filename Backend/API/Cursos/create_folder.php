<?php
$data = json_decode(file_get_contents("php://input"));

$folderPath = $data->folderPath;

if (!file_exists($folderPath)) {
    if (mkdir($folderPath, 0777, true)) {
        echo json_encode(["success" => true, "message" => "Folder created successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to create folder"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Folder already exists"]);
}
?>
