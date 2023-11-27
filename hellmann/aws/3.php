Amazon AWS<br/><br/>
<?php

// Require autoloader.
require 'aws_sdk_php/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;
use Aws\Textract\Exception\TextractException;
use Aws\Textract\TextractClient;
use Aws\Sqs\SqsClient;
use Aws\Sns\SnsClient;

$credentials = new Aws\Credentials\Credentials('AKIA5PKPUVBSQ4TIBA27', 'vr6++/w4TCIKdI2/eFot4s8aIouZSnd2eRe5d39I');

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region' => 'us-east-2',
    'credentials' => $credentials,
    'debug' => false
]);


$SnSclient = new Aws\Sns\SnsClient([
    'version' => 'latest',
    'region' => 'us-east-2',
    'credentials' => $credentials,
    'debug' => false
]);

try {
    $result = $SnSclient->listTopics([
    ]);
    echo "List SNS Topics:</br><pre>";
    var_dump($result);
    echo "</pre>";
} catch (AwsException $e) {
    // output error message if fails
    error_log($e->getMessage());
}


$queueName = "ocr";

$client = new Aws\Sqs\SqsClient([
    'version' => 'latest',
    'region' => 'us-east-2',
    'credentials' => $credentials,
    'debug' => false
]);

try {
    $result = $client->getQueueUrl([
        'QueueName' => $queueName // REQUIRED
    ]);
    echo "get Que URL:</br><pre>";
    var_dump($result);
    echo "</pre";
} catch (AwsException $e) {
    // output error message if fails
    echo "ERR: <pre>". $e->getMessage() . "</pre>";
}


$result = $s3->listBuckets();
echo "<pre>listBuckets:\n";
foreach ($result['Buckets'] as $bucket) {
    // Each Bucket value will contain a Name and CreationDate
    echo "{$bucket['Name']} - {$bucket['CreationDate']}\n";
}
echo "</pre>";
$bucket = 'gal-hellmann';
$fileNameKey = 'ocr/testDoc_3.txt'; //'*** Your Object Key ***';

try {
    // Upload data.
    $result = $s3->putObject([
        'Bucket' => $bucket,
        'Key' => $fileNameKey,
        'Body' => 'test document made by Gal Sarig',
        'ACL' => 'public-read'
    ]);

    // Print the URL to the object.
    echo "Object created!! url: " . $result['ObjectURL'] . PHP_EOL;
} catch (S3Exception $e) {
    echo "Object NOT created :( " . $e->getMessage() . PHP_EOL;
}


$teClient = new Aws\Textract\TextractClient([
    'version' => 'latest',
    'region' => 'us-east-2',
    'credentials' => $credentials,
    'debug' => false
]);





/*

$result = $teClient->getDocumentAnalysis([
    'JobId' => 'cdfca9b57967d006c1648c2ab266b7bf0054b2d10d73360dc1b8e4e06cd301e5', // REQUIRED
    'MaxResults' => 100,
    'NextToken' => ''
]);
echo "<br/>Job Results: <br/><pre>" . $result ."</pre>";

*/






$fileToAnalyzeNameKey = "ocr/Gear4music-invoice-1.pdf";
try {
    $result = $teClient->startDocumentAnalysis([
        //'Bytes' => '',
        'FeatureTypes' => ['TABLES', 'FORMS'],
        'DocumentLocation' => [
            'S3Object' => [
                'Bucket' => $bucket,
                'Name' => $fileToAnalyzeNameKey
            ],
        ],
        "NotificationChannel" => [
            "RoleArn" => "arn:aws:iam::926269352037:role/SNS_SQS",
            "SNSTopicArn" => "arn:aws:sns:us-east-2:926269352037:ocr"
        ]
    ]);

    echo '<br/><pre>' . $fileToAnalyzeNameKey . ' anaylizing STARTED!!!';
    print_r($result);
    echo '</pre>';
} catch (TextractException $e) {
    echo '<br/>' . $fileToAnalyzeNameKey . ' anaylizing NOT started :(<br/><pre>';
    echo $e->getMessage() . PHP_EOL;
    echo '</pre>';
}

$fileToAnalyzeNameKey = "ocr/Gear4music-invoice-1.jpg";
try {
    $result = $teClient->analyzeDocument([
        //'Bytes' => '',
        'FeatureTypes' => ['TABLES','FORMS'],
        'Document' => [
            'S3Object' => [
                'Bucket' => $bucket,
                'Name' => $fileToAnalyzeNameKey
            ],
        ],
    ]);
    echo '<br/><pre>' . $fileToAnalyzeNameKey . ' anaylized!!!';
    print_r($result);
    echo '</pre>';
} catch (TextractException $e) {
    echo '<br/>' . $fileToAnalyzeNameKey . ' NOT anaylized :(<br/><pre>';
    echo $e->getMessage() . PHP_EOL;
    echo '</pre>';
}





echo "<br/>Analysing file not hosted in S3<br/>";
// The file in this project.
$filename = "tst.pdf";
$file = fopen($filename, "rb");
$contents = fread($file, filesize($filename));
fclose($file);
$options = [
    'Document' => [
        'Bytes' => $contents
    ],
    'FeatureTypes' => ['TABLES', 'FORMS']
];
$result = $teClient->analyzeDocument($options);

echo "analyzeDocument result=";
//print_r($result);

// If debugging:
// echo print_r($result, true);
$blocks = $result['Blocks'];
// Loop through all the blocks:
foreach ($blocks as $key => $value) {
    if (isset($value['BlockType']) && $value['BlockType']) {
        $blockType = $value['BlockType'];
        if (isset($value['Text']) && $value['Text']) {
            $text = $value['Text'];
            if ($blockType == 'WORD') {
                echo "Word: " . print_r($text, true) . "\n";
            } else if ($blockType == 'LINE') {
                echo "Line: " . print_r($text, true) . "\n";
            }
        }
    }
}
?>

