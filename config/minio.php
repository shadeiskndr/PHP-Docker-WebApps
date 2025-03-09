<?php
return [
    'endpoint' => 'http://minio:9000',
    'use_path_style_endpoint' => true,
    'credentials' => [
        'key' => 'minioadmin',
        'secret' => 'minioadmin',
    ],
    'region' => 'us-east-1',
    'version' => 'latest',
    'bucket' => 'media'
];
