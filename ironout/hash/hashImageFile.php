<!DOCTYPE html>
<html lang="en">
<header>
    <meta charset="utf-8">
    <!--    <link rel="stylesheet" href="../style_rtl.css">-->

    <script>
        function copyToClipboard() {
            var textField = document.getElementById("hash");
            textField.select();
            textField.setSelectionRange(0, 99999); // For mobile devices

            navigator.clipboard.writeText(textField.value)
                .then(() => {
                    console.log('Text successfully copied to clipboard');
                    // alert("Text copied to clipboard: " + textField.value);
                })
                .catch(err => {
                    console.error('Unable to copy:', err);
                    alert("Unable to copy to clipboard. Please copy manually.");
                });
        }
    </script>
</header>
<body>

<h1 style="text-align: center;">IronOut</h1>
<h2 style="text-align: center;">Get Hash code for a screenshot taken by admin</h2>
<br/>

<?php

$fileName = $_GET['fileName'];

function compute_hash($filepath, $hash_algorithm)
{
    return hash_file($hash_algorithm, $filepath);
}

$fileNameEncoded = str_replace(' ', '%20', $fileName);
$path = 'files';
$fileAndPath = "$path/$fileName";
$fileNameAndPathEncoded = str_replace(' ', '%20', $fileAndPath);
$sha256_hash = compute_hash($fileAndPath, 'sha256');
//$md5_hash = compute_hash($fileAndPath, 'md5');

//echo "<br/>SHA-256 hash of the image file:<br/>$sha256_hash\n";
//echo "<br/>MD5 hash of the image: $md5_hash\n";

?>

<form style="text-align: center;">
    <div style="font-size: large;">
<!--        http://godaddy.galsarig.com/ironout/hash/-->
<!--        http://galsarig.com/ironout/hash/-->
        <img src="<?= $fileNameAndPathEncoded ?>" style="border: 3px solid #000000; max-width:25%;; max-height: 500px"/>
        <br/>File name: <?= $fileName ?>
        <br/><br/>
        <h3>Hash sha256:</h3>
        <input type="text" id="hash" value="<?=$sha256_hash?>" size=80 style="font-size: large; text-align: center;">
        <br/><br/>
        <button type="button" id="copy" onclick="copyToClipboard()" style="font-size: large; color: red;">Copy Hash to
            clipboard
        </button>
    </div>
</form>

<br/>
<h2 style="text-align: center;">
    <a href="/ironout/hash">Hash another file</a>
</h2>

</body>