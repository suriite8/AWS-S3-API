<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Credentials\CredentialsInterface;



class awslib {

    public $bucket        = 'Your BUCKET_NAME';
    public $aws_key       = 'Your AWS Key';
    public $aws_secret    = 'Your screet Key';
    public $aws_s3_region = 'us-east-2';
    public $s3;

     public function __construct() {
        $credentials = new Aws\Credentials\Credentials($this->aws_key, $this->aws_secret);
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-2',
            'credentials' => $credentials
        ]);

      }

    public function pushFileToS3($file,$type,$filename) {
        try {
        // Upload data.
        $result = $this->s3->putObject([
            'Bucket' => $this->bucket,
            'Key'    => $filename,
            'SourceFile' => "$file",
            'ContentType' => "$type",
        ]);

        // Print the URL to the object.
        return $result['ObjectURL'];
      } catch (S3Exception $e) {
          return $e->getMessage();
      }
    }
	// Function to create bucket 
	public function createBucket() {
		$response['status'] = 'fail';
		$BUCKET_NAME = 'tutorialbucket001';

		//Creating S3 Bucket
		try {
			$response['status'] = 'success';
			$result = $this->s3->createBucket([
				'Bucket' => $BUCKET_NAME,
			]);
			$result = $result->toArray();
			$response['data'] = $result;
			
		} catch (AwsException $e) {
			$response['status'] = 'fail';
			// output error message if fails
			$response['data'] = $e->getMessage();
			//echo $e->getMessage();
			//echo "\n";
		}
		return $response;

	}
    public function isAwsFileExist($filename) {
        try {
        // Get the object.
          $result = $this->s3->getObject([
              'Bucket' => $this->bucket,
              'Key'    => $filename
          ]);
          header("Content-Type: {$result['ContentType']}");
          return $result['Body'];

        } catch (S3Exception $e) {

            return $e->getMessage();
        }
  }
    public function test($filename) {
      return "Test Function ".$filename;
    }
}
$awslib = new awslib();
/* $bucketResponse = $awslib->createBucket(); */



$file = dirname ( __FILE__ ).'//pexels-photo-248797.jpeg';
$dataType="image/jpeg";
$filename="image/apidemo";
$pushFileToS3 = $awslib->pushFileToS3($file,$dataType,$filename);
 echo "<pre>",print_r($pushFileToS3);

