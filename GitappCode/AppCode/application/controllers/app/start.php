<?php 
require 'vendor/autoload.php';
use Aws\Credentials\CredentialProvider;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;

$config = require('config.php');
 
 
$s3=new S3Client([
	'region'  => 'us-1',
	'version' => 'latest',
	'profile' => 'default',
	'credentials' => [
		'key' => 'xxxxx',
		'secret' => 'xxxxx',
	
	],
]);

 