<?php
return [
    'endpoint' => $_ENV['MINIO_ENDPOINT'] ?? 'http://minio:9000',
    'public_endpoint' => $_ENV['MINIO_PUBLIC_ENDPOINT'] ?? 'http://localhost:9000',
    'use_path_style_endpoint' => true,
    'credentials' => [
        'key' => $_ENV['MINIO_ACCESS_KEY'] ?? 'minioadmin',
        'secret' => $_ENV['MINIO_SECRET_KEY'] ?? 'minioadmin',
    ],
    'region' => $_ENV['MINIO_REGION'] ?? 'us-east-1',
    'version' => 'latest',
    'bucket' => $_ENV['MINIO_BUCKET'] ?? 'media',
    'max_file_size' => $_ENV['MAX_FILE_SIZE'] ?? (100 * 1024 * 1024), // 100MB
    'allowed_types' => [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'video/mp4', 'video/quicktime', 'video/webm',
        'application/pdf', 'text/plain'
    ]
];
