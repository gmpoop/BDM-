<?php
// Decodificar JSON si existe
$jsonData = json_decode(file_get_contents("php://input"), true);

if (isset($jsonData['folderPath'])) {
    $folderPath = $jsonData['folderPath'];
} else {
    $folderPath = $_POST['folderPath'] ?? '';
}

if (empty($folderPath)) {
    echo json_encode(["success" => false, "message" => "Missing folder path"]);
    exit;
}

// Verificar si la carpeta existe y, si no, crearla
if (!file_exists($folderPath)) {
    if (!mkdir($folderPath, 0777, true)) {
        echo json_encode(["success" => false, "message" => "Failed to create folder"]);
        exit;
    }
}

// Verificar si el archivo está presente en $_FILES
if (isset($_FILES['video'])) {
    $targetFile = $folderPath . '/' . basename($_FILES["video"]["name"]);

    if (move_uploaded_file($_FILES["video"]["tmp_name"], $targetFile)) {
        echo json_encode(["success" => true, "message" => "File uploaded successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to upload file"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No video file uploaded"]);
}
?>
