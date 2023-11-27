<?php

// upload file from dropzone

$ds = DIRECTORY_SEPARATOR;
$storeFolder = 'files';

if (!empty($_FILES)) {
    $tempFile = $_FILES['file']['tmp_name'];
    $extension = pathinfo($tempFile, PATHINFO_EXTENSION);
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
    $targetFile =  $targetPath. $_FILES['file']['name'];
//    $tmpID = mt_rand(1e8, 1e9);
//    $targetFile = "file_$tmpID.$extension";
    move_uploaded_file($tempFile,$targetFile);
}

?>
