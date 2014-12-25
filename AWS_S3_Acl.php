<?php
require_once 'vendor/autoload.php';
use Aws\S3\S3Client;
class Acl  {
 // 'private', 'public-read', 'project-private', 'public-read-write', 'authenticated-read', 'bucket-owner-read', 'bucket-owner-full-control'
  public function allItems() {
         $acls = array();
         $client = S3Client::factory(array(
                        'key'    => 'AKIAIAYK3FKW66L6K2HA',
                        'secret' => 'YOUR_AWS_SECRET',));
         $result = $client->listBuckets();
         foreach ($result['Buckets'] as $bucket) {
                $objs = $client->listObjects(array(
                                            'Bucket' => $bucket['Name']));
                foreach($objs['Contents'] as $obj){
                        $oacls = array(
                                            'ID' => $obj['ETag'],
                                            'Bucket' => $bucket['Name'],
                                            'Key' => $obj['Key']);
                         array_push($acls, $oacls);
                 }
         }
         return $acls;
  }

  public function all() {
         $acls = array();
         $client = S3Client::factory(array(
                        'key'    => 'AKIAIAYK3FKW66L6K2HA',
                        'secret' => 'YOUR_AWS_SECRET',));
         $result = $client->listBuckets();
         foreach ($result['Buckets'] as $bucket) {
                $objs = $client->listObjects(array(
                                            'Bucket' => $bucket['Name']));
                foreach($objs['Contents'] as $obj){
                        $oacls = $client->getObjectAcl(array(
                                            'Bucket' => $bucket['Name'],
                                            'Key' => $obj['Key']));
                         array_push($acls, $oacls);
                 }
         }
         return $acls;
  }


  public function get($bucket, $key = null){
             if ($bucket == null) return array();
             $client = S3Client::factory(array(
                        'key'    => 'AKIAIAYK3FKW66L6K2HA',
                        'secret' => 'YOUR_AWS_SECRET',));
            if ($key != null){
             $acls = $client->getObjectAcl(array(
                  'Bucket' => $bucket,
                  'Key' => $key));
             return $acls;
            }
            else {
             $acls = $client->getBucketAcl(array(
                  'Bucket' => $bucket));
             return $acls;
            }
  }

  public function set($bucket, $key, $acl){
    $client = S3Client::factory(array(
                        'key'    => 'AKIAIAYK3FKW66L6K2HA',
                        'secret' => 'YOUR_AWS_SECRET',));
    $result = $client->putObjectAcl(array(
    'ACL'    => $acl,
    'Bucket' => $bucket,
    'Key'    => $key,
    'Body'   => '{}'
    ));
    return $result;
  }
    public function setPermission($bucket, $key, $userid, $permission){
    $client = S3Client::factory(array(
                        'key'    => 'AKIAIAYK3FKW66L6K2HA',
                        'secret' => 'YOUR_AWS_SECRET',));
$result = $client->putObjectAcl(array(
    'Grants' => array(
        array(
            'Grantee' => array(
                'DisplayName' => $userid,
                //'EmailAddress' => 'string',
                'ID' => '504028d05826df18d85d7fd6fb708c8efec3a011083705079fc2e05cf53c5f60',
                // Type is required
                'Type' => 'CanonicalUser',
                //'URI' => 'string',
            ),
            'Permission' => $permission,
        ),
        // ... repeated
    ),
    'Owner' => array(
        'DisplayName' => $userid,
        'ID' => '504028d05826df18d85d7fd6fb708c8efec3a011083705079fc2e05cf53c5f60',
    ),
    // Bucket is required
    'Bucket' => $bucket,
    // 'GrantFullControl' => 'string',
    // 'GrantRead' => 'string',
    // 'GrantReadACP' => 'string',
    // 'GrantWrite' => 'string',
    // 'GrantWriteACP' => 'string',
    // Key is required
    'Key' => $key,
));
    return $result;
  }
    public function setBucketAcl($bucket, $acl){
    $client = S3Client::factory(array(
                        'key'    => 'AKIAIAYK3FKW66L6K2HA',
                        'secret' => 'YOUR_AWS_SECRET',));
    $result = $client->putBucketAcl(array(
    'ACL'    => $acl,
    'Bucket' => $bucket,
    'Body'   => '{}'
    ));
    return $result;
  }
  public function setBucketPermission($bucket, $userid, $permission, $owner){
    $client = S3Client::factory(array(
                        'key'    => 'AKIAIAYK3FKW66L6K2HA',
                        'secret' => 'YOUR_AWS_SECRET',));
    $result = $client->putBucketAcl(array(
             'Grants' => array(
              array(
            'Grantee' => array(
                'DisplayName' => $userid,
                //'EmailAddress' => 'string',
                'ID' => '504028d05826df18d85d7fd6fb708c8efec3a011083705079fc2e05cf53c5f60',
                // Type is required
                'Type' => 'CanonicalUser',
                //'URI' => 'string',
            ),
            'Permission' => $permission,
        ),
        // ... repeated
    ),
    'Owner' => array(
        'DisplayName' => $owner,
        'ID' => '504028d05826df18d85d7fd6fb708c8efec3a011083705079fc2e05cf53c5f60',
    ),
    // Bucket is required
    'Bucket' => $bucket,
    // 'GrantFullControl' => 'string',
    // 'GrantRead' => 'string',
    // 'GrantReadACP' => 'string',
    // 'GrantWrite' => 'string',
    // 'GrantWriteACP' => 'string',
));
    return $result;
  }
}

//$acl = new Acl();
//$acl->setPermission('elasticbeanstalk-us-east-1-415844903624','20130581L7-2013041jsv-Package(v3).zip', 'rravishankar', 'READ_ACP');
//$acl->setBucketPermission('elasticbeanstalk-us-east-1-415844903624', 'rravishankar', 'READ', 'rravishankar');
