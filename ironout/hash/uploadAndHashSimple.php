<!DOCTYPE html>
<html>
<head>
    <title>File Upload and Hash</title>
</head>
<body>

<form action="" method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload File" name="submit">
</form>

<?php
if (isset($_POST["submit"])) {
    $targetDirectory = "files/";
    $targetFile = $targetDirectory . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file already exists
//    if (file_exists($targetFile)) {
//        echo "Sorry, the file already exists.";
//        $uploadOk = 0;
//    }

    // Check file size (you can adjust the file size limit here if needed)
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats (here we allow only txt files, you can modify it as needed)
//    if ($fileType !== "txt") {
//        echo "Sorry, only TXT files are allowed.";
//        $uploadOk = 0;
//    }

    // If $uploadOk is set to 0, an error occurred
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload file and generate hash
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
            $fileHash = hash_file('sha256', $targetFile);
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded and its SHA256 hash is: " . $fileHash;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

</body>
</html>
