<?php
// Path to the JSON file outside the public_html directory
$customHeader = null;

// Check if the Authorization header is present
if (isset($_SERVER['HTTP_AUTHORIZATION']) && $_SERVER['REQUEST_METHOD'] === 'GET') {

    // Optionally, use apache_request_headers() to get all headers
    if (function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        $customHeader = isset($headers['Authorization']) ? $headers['Authorization'] : null;
    } else {
        // If apache_request_headers() is not available, fallback to $_SERVER
        $customHeader = $_SERVER['HTTP_AUTHORIZATION'];
    }

    // Validate the Authorization header
    if ($customHeader === 'sw_get') {
        header('Content-Type: application/json');

        // Ensure the file exists before reading
        $filePaths = glob('../../../rw_secure/*.json');

        if (empty($filePaths)) {
            echo json_encode(['error' => 'No JSON files found']);
        } else {
            foreach ($filePaths as $filePath) {
                echo file_get_contents($filePath);
            }
        }
    } else {
        echo json_encode(['error' => 'Invalid Authorization key']);
    }
} else {
    echo json_encode(['error' => 'Authorization header or request method not valid']);
}

// Read and output the JSON file
$allowed_ip = '88.159.229.158';
$allowed_ip_server = '62.84.245.237';

// Get the visitor's IP address
$visitor_ip = $_SERVER['REMOTE_ADDR'];
$current_server = $_SERVER['SERVER_ADDR'];

?>