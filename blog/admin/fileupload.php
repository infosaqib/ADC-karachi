<?php 
header('Content-Type: application/json');
$data = array();

if (isset($_FILES['upload']['name'])) {
    $file_name = basename($_FILES['upload']['name']);
    $file_path = 'uploads/' . $file_name;
    $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    if (in_array($file_extension, $allowed_extensions)) {
        if (move_uploaded_file($_FILES['upload']['tmp_name'], $file_path)) {
            $data = [
                'uploaded' => 1,
                'fileName' => $file_name,
                'url' => $file_path
            ];
        } else {
            $data = [
                'uploaded' => 0,
                'error' => ['message' => 'Error! File not uploaded']
            ];
        }
    } else {
        $data = [
            'uploaded' => 0,
            'error' => ['message' => 'Invalid file extension']
        ];
    }
}

echo json_encode($data);
