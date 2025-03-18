<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class MinioService {
    private static $instance = null;
    private $s3Client;
    private $bucket;
    private $publicEndpoint;

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
        // Use public endpoint when available, or use default localhost
        $this->publicEndpoint = $config['public_endpoint'] ?? 'http://localhost:9000';
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function uploadFile($filePath, $fileName, $contentType = null) {
        try {
            // Use file stream instead of loading entire file into memory
            $fileStream = fopen($filePath, 'r');
            
            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'Key'    => $fileName,
                'Body'   => $fileStream,
                'ContentType' => $contentType,
                'ACL'    => 'public-read',
            ]);
            
            // Close the file stream
            fclose($fileStream);
            
            // Return proxy URL
            return '/minio-proxy.php?file=' . $fileName;
        } catch (S3Exception $e) {
            error_log("Error uploading file to MinIO: " . $e->getMessage());
            return null;
        }
    }
    

    public function getFileUrl($fileName) {
        $url = $this->s3Client->getObjectUrl($this->bucket, $fileName);
        // Replace internal endpoint with public endpoint
        return str_replace(parse_url($url, PHP_URL_HOST) . ':' . parse_url($url, PHP_URL_PORT), 
                          parse_url($this->publicEndpoint, PHP_URL_HOST) . ':' . parse_url($this->publicEndpoint, PHP_URL_PORT), 
                          $url);
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
