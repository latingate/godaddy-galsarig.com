<?php

//// Prevent caching
//header("Cache-Control: no-cache, no-store, must-revalidate");
//header("Pragma: no-cache");
//header("Expires: 0");
//header("Content-Type:application/json");

//session_start();

$tmpID = $_GET["tmpID"];
$currentPath = __FILE__;
$currentPath = dirname( __FILE__);
$output_dir = $currentPath . "/files/".$tmpID."/";
//$output_dir = "uploads/tmp/".$tmpID."/";

if (!file_exists($output_dir)) {
    mkdir($output_dir, 0777, true);
}

if(isset($_FILES["file"]))
{
    $ret = array();

//	This is for custom errors;
    /*	$custom_error= array();
        $custom_error['jquery-upload-file-error']="File already exists";
        echo json_encode($custom_error);
        die();
    */
    $error =$_FILES["file"]["error"];
    //You need to handle  both cases
    //If Any browser does not support serializing of multiple files using FormData()
    if(!is_array($_FILES["file"]["name"])) //single file
    {
        $fileName = $_FILES["file"]["name"];
        move_uploaded_file($_FILES["file"]["tmp_name"],$output_dir.$fileName);
        $ret[]= $fileName;
        echo "<p>UPLOADED OK $fileName $ret";
    }
    else  //Multiple files, file[]
    {
        $fileCount = count($_FILES["file"]["name"]);
        for($i=0; $i < $fileCount; $i++)
        {
            $fileName = $_FILES["file"]["name"][$i];
            move_uploaded_file($_FILES["file"]["tmp_name"][$i],$output_dir.$fileName);
            $ret[]= $fileName;
            echo "<p>UPLOADED OK $fileName $ret";
        }

    }
    echo json_encode($ret);
}

?>