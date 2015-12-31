<?php
require 'vendor/autoload.php';
use Aws\CloudWatch\CloudWatchClient;
$key = "your_key";
$secret = "your_secret";
$region = "us-west-2";
$version = "latest";
$interval = 15;

// Users/rajamani/Downloads/devserver/bill/vendor/aws/aws-sdk-php/src

// Use the us-west-2 region and latest version of each client.
$sharedConfig = [
    'region'  => $region,
    'version' => $version,
  'credentials' => array(
    'key' => $key,
    'secret'  => $secret,
  ),
    'key'    => $key,
    'secret' => $secret,
];

// Create an SDK class used to share configuration across clients.
$sdk = new Aws\Sdk($sharedConfig);
$client = $sdk->createEc2();
date_default_timezone_set('America/Los_Angeles');
$result  = $client->describeInstances(array(
));
echo 'RESULT='.serialize($result)."\n";
$imageTypeVCPU = array(
"t2.nano" => 1,
"t2.micro" => 1,
"t2.small" => 1,
"t2.medium" => 2,
"t2.large" => 2,
"m4.large" => 2,
"m4.xlarge" => 4,
"m4.2xlarge" => 8,
"m4.4xlarge" => 16,
"m4.10xlarge" => 40,
"m3.medium" => 1,
"m3.large" => 2,
"m3.xlarge" => 4,
"m3.2xlarge" => 8,
"c4.large" => 2,
"c4.xlarge" => 4,
"c4.2xlarge" => 8,
"c4.4xlarge" => 16,
"c4.8xlarge" => 36,
"c3.large" => 2,
"c3.xlarge" => 4,
"c3.2xlarge" => 8,
"c3.4xlarge" => 16,
"c3.8xlarge" => 32,
"r3.large" => 2,
"r3.xlarge" => 4,
"r3.2xlarge" => 8,
"r3.4xlarge" => 16,
"r3.8xlarge" => 32,
"g2.2xlarge" => 8,
"g2.8xlarge" => 32,
"i2.xlarge" => 4,
"i2.2xlarge" => 8,
"i2.4xlarge" => 16,
"i2.8xlarge" => 32,
"d2.xlarge" => 4,
"d2.2xlarge" => 8,
"d2.4xlarge" => 16,
"d2.8xlarge" => 36,
);
//RESULT=O:10:"Aws\Result":1:{s:16:"Aws\Resultdata";a:2:{s:12:"Reservations";a:1:{i:0;a:4:{s:13:"ReservationId";s:10:"r-d01fd826";s:7:"OwnerId";s:12:"792498758900";s:6:"Groups";a:0:{}s:9:"Instances";a:1:{i:0;a:28:{s:10:"InstanceId";s:10:"i-bee9da7a";
$result = (array)$result;
$totalCpu = 0;
foreach( $result as $reservations)
{
 //echo 'RESER='.serialize($reservations)."\n";
 foreach($reservations['Reservations'] as $reservation)
 {
   //echo 'RESERVATION='.serialize($reservation)."\n";
   foreach($reservation["Instances"] as $instance)
   {
        $attributes = $client->describeInstanceAttribute(array(
    'DryRun' => false,
    // InstanceId is required
    'InstanceId' => $instance["InstanceId"],
    // Attribute is required
    // 'Attribute' => 'instanceType | kernel | ramdisk | userData | disableApiTermination | instanceInitiatedShutdownBehavior | rootDeviceName | blockDeviceMapping | productCodes | sourceDestCheck | groupSet | ebsOptimized | sriovNetSupport',
      'Attribute' => 'instanceType',
));
      $attributes = (array)$attributes;
      foreach ($attributes as $attribute){
       $imageType = $attribute["InstanceType"]["Value"]; 
       $vcpu = $imageTypeVCPU[$imageType];
       //echo 'IMAGE_TYPE='.serialize($imageType)."\n";
       //echo 'vCPU='.$vcpu."\n";
       $totalCpu += $vcpu;
      }
   }
 }
}
// IMAGE_TYPE=s:8:"t2.micro";
// vCPU=1
$billingHours = 8;
$cpuHours = $totalCpu*8;
echo "--------------------------Bill-------------------------"."\n";
echo "$0.00 per vCPU-hour (or partial hour)  ".sprintf('%04d', $cpuHours)." hrs   $0.00"."\n";
echo "CT to be collected:                               $0.00"."\n"; 
echo "GST to be collected:                              $0.00"."\n";
echo "US Sales Tax to be collected:                     $0.00"."\n";
echo "VAT to be collected:                              $0.00"."\n";
echo "Total:                                            $0.00"."\n";
echo "-------------------------------------------------------"."\n";
?>
