<?php
// This script proxies requests to MinIO so they appear to come from the same origin

// Get the filename from the URL
$filename = $_GET['file'] ?? '';
if (empty($filename)) {
    header('HTTP/1.1 400 Bad Request');
    exit('Missing file parameter');
}

// Validate filename to prevent directory traversal
$filename = basename($filename);

// Construct the full MinIO URL (using Docker service name since this runs on the server)
$minioUrl = "http://minio:9000/media/{$filename}";

// Get the file content
$content = @file_get_contents($minioUrl);
if ($content === false) {
    header('HTTP/1.1 404 Not Found');
    exit('File not found');
}

// Set appropriate content type based on file extension
$extension = pathinfo($filename, PATHINFO_EXTENSION);
$contentTypes = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'mp4' => 'video/mp4',
    'mov' => 'video/quicktime'
];

$contentType = $contentTypes[strtolower($extension)] ?? 'application/octet-stream';
header("Content-Type: {$contentType}");

// Output the file content
echo $content;
