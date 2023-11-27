<!DOCTYPE html>
<html lang="en">
<header>
    <meta charset="utf-8">
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css"/>
    <!--    <link rel="stylesheet" href="../style_rtl.css">-->

    <?php
//    // Prevent caching
//    header("Cache-Control: no-cache, no-store, must-revalidate");
//    header("Pragma: no-cache");
//    header("Expires: 0");
//    header("Content-Type:application/json");

    //$tmpID = mt_rand(1e8, 1e9);
    //$fileName = "file_$tmpID";
    ?>

    <script>
        // Note that the name "myDropzone" is the camelized id of the form
        Dropzone.options.myDropzone = {
            // Configuration options go here
            init: function () {
                this.on("success", function (file, response) {
                    // You can display the filename wherever you want in your UI
                    window.location.href = "hashImageFile.php?fileName=" + file.name;
                    // alert("File uploaded: " + file.name);
                });
            },
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 5, // KB ?? MB
            acceptedFiles: "image/*",
            maxFiles: 1, //Maximum number of files
            clickable: true,
            dictDefaultMessage: '',
        };
    </script>

    <style>
        #myDropzone {
            width: 400px;
            height: 300px;
            border: 2px dashed #ccc;
            border-radius: 5px;
            /*position: relative;*/
            /*position: absolute;*/
            /*top: 40%;*/
            /*left: 50%;*/
            /*transform: translate(-50%, -50%);*/
            /*resize: none; !* Prevent resizing of the textarea *!*/
            /*padding: 10px; !* Optional: Add padding for better appearance *!*/
        }

        .dropzone-container {
            display: flex;
            justify-content: center;
            align-items: center;
            /* Set the desired width and height for the container */
            /*width: 100%; !* Adjust as needed *!*/
            /*height: 100vh; !* Adjust as needed *!*/
            /* Other styling for the container */
            /* background-color: #f0f0f0; */
            /* ... */
        }

    </style>

</header>

<body>

<br/>
<h1 style="text-align: center;">IronOut</h1>
<h2 style="text-align: center;">Get Hash code for a screenshot taken by admin</h2>
<br/>
<br/>

<div class="dropzone-container">
    <form action="uploadFile.php" class="dropzone" id="myDropzone" style="text-align: center;">
        <br/>
        <br/>נא לגרור לפה את צילום המסך
        <br/><br/>או ללחוץ באיזור זה כדי לפתוח את סייר הקבצים
        <input name="file" type="file" accept="image/*" style="display: none;"/>
    </form>
</div>


</body>
</html>


