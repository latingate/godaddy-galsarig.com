<!DOCTYPE html>
<meta charset="utf-8">
<script src="../tools/vendors/js/vendor.bundle.base.js"></script>

<script src="../tools/vendors/dropzone/dropzone.js"></script>
<link rel="stylesheet" href="../tools/vendors/dropzone/dropzone.css">
<link rel="stylesheet" href="../style_rtl.css">

<body>

<?php
$tmpID = mt_rand(1e8, 1e9);
?>

<br/>
<h2 style="text-align: center;">IronOut</h2>
<h3 style="text-align: center;">Get HASH code for a screenshot taken by admin</h3>
<br/>
<br/>

<form id="form1" action="hashImageFile.php">
    <div style="text-align: center; width: 50%;" id="dropzoneDiv" class="dropzoneGS text-center">
        <br/><br/>
        נא לגרור לפה קובץ, או ללחוץ באיזור זה לצורך פתיחת סייר הקבצים
    </div>
    <p id="stageDisplay" style="text-align: center;">ממתין להעלאת קובץ</p>
    <div style="text-align: center;">
        <input type="submit" id="btn1" value="העלאת הקובץ">
    </div>
</form>

</body>

<SCRIPT>

    $(document).ready(function () {

        $("#dropzoneDiv").dropzone({
            // autoDiscover: false,
            url: "dropzoneUploadFiles.php?tmpID=<?=$tmpID?>",
            paramName: "file",
            acceptedFiles: "image/*",
            maxFiles: 1, //Maximum number of files
            maxFilesize: 5, // MB
            uploadMultiple: false,
            thumbnailWidth: 200,
            thumbnailMethod: "contain", // crop / contain
            autoProcessQueue: true,
            /**
             renameFile: function(file) {
                return file.name = "<?=$tmpID?>_" + Math.floor(Math.random()*(1e6-1e5)+1e5) + "_"+ file.name;
            }
             **/
        });

        /* this is not working.. */
        $("#dropzoneDiv").addClass("dropzone");

        $("#form1").submit(function () {
            ("#stageDisplay").html("מעלה קובץ");
            //myDropzone.processQueue();
            //setTimeout(function(){}, 300000);
        });

    });


</SCRIPT>

