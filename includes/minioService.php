<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class MinioService {
    private static $instance = null;
    private $s3Client;
    private $bucket;
    private $publicEndpoint;
    private $logger;

    private function __construct() {
        $config = require_once __DIR__ . '/../config/minio.php';
        
        $this->s3Client = new S3Client([
            'version' => $config['version'],
            'region'  => $config['region'],
            'endpoint' => $config['endpoint'],
            'use_path_style_endpoint' => $config['use_path_style_endpoint'],
            'credentials' => [
                'key'    => $config['credentials']['key'],
                'secret' => $config['credentials']['secret'],
            ],
        ]);
        
        $this->bucket = $config['bucket'];
        $this->publicEndpoint = $config['public_endpoint'] ?? 'http://localhost:9000';
        
        $this->ensureBucketExists();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function ensureBucketExists() {
        try {
            $this->s3Client->headBucket(['Bucket' => $this->bucket]);
        } catch (S3Exception $e) {
            if ($e->getStatusCode() === 404) {
                $this->createBucketWithRetry();
            } else {
                throw new Exception("Failed to check bucket existence: " . $e->getMessage());
            }
        }
    }

    private function createBucketWithRetry($maxRetries = 3) {
        for ($i = 0; $i < $maxRetries; $i++) {
            try {
                $this->s3Client->createBucket(['Bucket' => $this->bucket]);
                $this->setBucketPolicy();
                error_log("Successfully created and configured bucket: " . $this->bucket);
                return;
            } catch (S3Exception $e) {
                error_log("Attempt " . ($i + 1) . " failed to create bucket: " . $e->getMessage());
                if ($i === $maxRetries - 1) {
                    throw new Exception("Failed to create bucket after {$maxRetries} attempts");
                }
                sleep(1); // Wait before retry
            }
        }
    }

    public function uploadFile($filePath, $fileName, $contentType = null) {
        // Validate file exists and is readable
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new InvalidArgumentException("File does not exist or is not readable: {$filePath}");
        }

        // Validate file size (e.g., max 100MB)
        $maxSize = 100 * 1024 * 1024; // 100MB
        if (filesize($filePath) > $maxSize) {
            throw new InvalidArgumentException("File size exceeds maximum allowed size");
        }

        try {
            $fileStream = fopen($filePath, 'r');
            if (!$fileStream) {
                throw new Exception("Failed to open file stream");
            }
            
            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'Key'    => $fileName,
                'Body'   => $fileStream,
                'ContentType' => $contentType ?: $this->getMimeType($filePath),
                'ACL'    => 'public-read',
                'Metadata' => [
                    'uploaded_at' => date('c'),
                    'original_name' => basename($filePath)
                ]
            ]);
            
            fclose($fileStream);
            
            return '/minio-proxy.php?file=' . urlencode($fileName);
        } catch (S3Exception $e) {
            error_log("MinIO upload error: " . $e->getMessage());
            throw new Exception("Failed to upload file to storage");
        }
    }

    public function getFileUrl($fileName) {
        $url = $this->s3Client->getObjectUrl($this->bucket, $fileName);
        return str_replace(parse_url($url, PHP_URL_HOST) . ':' . parse_url($url, PHP_URL_PORT), 
                          parse_url($this->publicEndpoint, PHP_URL_HOST) . ':' . parse_url($this->publicEndpoint, PHP_URL_PORT), 
                          $url);
    }

    private function getMimeType($filePath) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        return $mimeType ?: 'application/octet-stream';
    }
    
    public function deleteFile($fileName) {
        try {
            $this->s3Client->deleteObject([
                'Bucket' => $this->bucket,
                'Key'    => $fileName,
            ]);
            return true;
        } catch (S3Exception $e) {
            error_log("Error deleting file from MinIO: " . $e->getMessage());
            return false;
        }
    }

    public function fileExists($fileName) {
        try {
            $this->s3Client->headObject([
                'Bucket' => $this->bucket,
                'Key'    => $fileName,
            ]);
            return true;
        } catch (S3Exception $e) {
            return false;
        }
    }

    public function getFileMetadata($fileName) {
        try {
            $result = $this->s3Client->headObject([
                'Bucket' => $this->bucket,
                'Key'    => $fileName,
            ]);
            return [
                'size' => $result['ContentLength'],
                'type' => $result['ContentType'],
                'last_modified' => $result['LastModified'],
                'metadata' => $result['Metadata'] ?? []
            ];
        } catch (S3Exception $e) {
            return null;
        }
    }

    public function listFiles($prefix = '') {
        try {
            $result = $this->s3Client->listObjects([
                'Bucket' => $this->bucket,
                'Prefix' => $prefix
            ]);
            
            return $result['Contents'] ?? [];
        } catch (S3Exception $e) {
            error_log("Error listing files from MinIO: " . $e->getMessage());
            return [];
        }
    }
}
