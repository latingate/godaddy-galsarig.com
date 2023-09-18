<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- jQuery (should appear before Bootstrap) -->
    <script src="/tools/jquery/jquery-3.3.1.min.js"></script>
    <!-- Bootstrap core CSS -->
    <link href="/tools/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="/tools/bootstrap/dist/js/bootstrap.js"></script>
    <!-- jQuery UI -->
    <script src="/tools/jquery-ui/jquery-ui.js"></script>
    <link rel="stylesheet" href="/tools/jquery-ui/jquery-ui.css">
    <link rel="stylesheet" href="/tools/jquery-ui/jquery-ui.theme.css">
</head>

<div class="container">

<?php

function encrypt($string) {
    global $password;
    $cipher = "aes-128-gcm";
    $key = substr(hash('sha256', $password, true), 0, 32);
    $iv_len = openssl_cipher_iv_length($cipher);
    $tag_length = 16;
    $iv = openssl_random_pseudo_bytes($iv_len);
    $tag ="";

    $output = openssl_encrypt($string, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag, "", $tag_length);
    $output = base64_encode($iv.$tag.$output);

    return $output;
}

function decrypt($string) {
    global $password;
    $encrypted_string = base64_decode($string);
    $key = substr(hash('sha256', $password, true), 0, 32);
    $cipher = "aes-128-gcm";
    $iv_len = openssl_cipher_iv_length($cipher);
    $tag_length = 16;
    $iv = substr($encrypted_string, 0, $iv_len);
    $tag = substr($encrypted_string, $iv_len, $tag_length);
    $encrypt_text = substr($encrypted_string,$iv_len + $tag_length);

    $output = openssl_decrypt($encrypt_text, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);

    return $output;
}

$textToDecrypt = $_GET['textToDecrypt'];
$textStringToEncrypt = $_POST['textStringToEncrypt'];
$textStringToDecrypt = $_POST['textStringToDecrypt'];
$userPassword = $_POST['userPassword'];

if ($textToDecrypt) {
    // GET is stronger than PUT
    $textStringToDecrypt = $textToDecrypt;
}

$mainPassword1 = "khjI8C69KGHbc9873kc59TTSX7YGHDX7jsc5v93kv";
$mainPassword2 = "87vujh38fHC9D0VYh83kcv90HYC83JKF05AUJSMJX";
$password = $mainPassword1.$userPassword.$mainPassword2;

$results = false;
$error = false;
$resultsHTML = "";

if (empty($userPassword) && (!empty($textStringToEncrypt) || (!empty($textStringToDecrypt)))) {
    $resultsHTML .= "Password is required!!<br/>";
    $error = true;
}

if ($textStringToEncrypt && !empty($userPassword)) {
    $encyptedText = encrypt($textStringToEncrypt);
    if ($encyptedText) {
        $resultsHTML .= "<label for='EncryptedText'>Encrypted text</label><input type='text' class='form-control' id='EncryptedText' value='" . $encyptedText . "'><br/>";
        $results = true;
    }
}

if ($textStringToDecrypt) {
    $decyptedText = Decrypt($textStringToDecrypt);
    if ($decyptedText) {
        $resultsHTML .= "<label for='EncryptedText'>Decrypted text</label><input type='text'  class='form-control' id='DecryptedText' value='" . $decyptedText . "'><br/>";
        $results = true;
    }
}

if ($results) {
    echo "<h1>Results</h1>" . $resultsHTML;
} elseif ($error) {
    echo "<h1>Error</h1>" . $resultsHTML;
} else {
    if (!empty($textStringToDecrypt) && !empty($userPassword)) {
        echo "<h3>No results!!</h3>Please check the password<br/><br/>";
    }
}


?>

<H1>Encrypt / Decrypt</H1>

<form method="POST">
    <div class="form-group">
        <label for="textStringToEncrypt">Text to Encrypt</label>
        <input type="text" class="form-control"  id="textStringToEncrypt" name="textStringToEncrypt" value="<?=$textStringToEncrypt?>">
        <br/>
        <label for="textStringToDecrypt">Text to Decrypt</label>
        <input type="text" class="form-control" id="textStringToDecrypt" name="textStringToDecrypt" value="<?=$textStringToDecrypt?>">
        <br/>
        <label for="userPassword">Password</label>
        <input type="text" size='70' class="form-control" id="userPassword" name="userPassword" value="<?=$userPassword?>">
        <br/>
        <input type="submit" value="Submit">
    </div>
</form>

</div>