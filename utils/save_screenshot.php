<?php

// Load WordPress environment
$path = $_SERVER['DOCUMENT_ROOT'];
include_once $path . '/wp-load.php';

if (isset($_POST['screenshots'])) {
    $screenshots = $_POST['screenshots'];
    $filepaths = [];
    
    foreach ($screenshots as $screenshot) {
        if (isset($screenshot['imgBase64']) && isset($screenshot['filename'])) {
            $imgBase64 = $screenshot['imgBase64'];
            $filename = $screenshot['filename'];

            $img = str_replace('data:image/png;base64,', '', $imgBase64);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);

            $upload_dir = wp_upload_dir();
            $upload_path = $upload_dir['basedir'] . '/crea-tu-frase/order/';

            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            $file = $upload_path . $filename;
            file_put_contents($file, $data);

            $filepaths[] = '/wp-content/uploads/crea-tu-frase/order/' . $filename;
        }
    }

    echo json_encode(['status' => 'success', 'filepaths' => $filepaths]);
} else {
    echo json_encode(['status' => 'error']);
}
