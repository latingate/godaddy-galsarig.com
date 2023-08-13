<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Encrypt / Decrypt your secret messages</title>
    <meta property="og:url" content="https://galsarig.com/encrypt">
    <meta property="og:title" content="יש לך הודעה סודית להעביר? הצפן אותה, ורק מי שיש לו את הסיסמא יוכל לקרוא אותה" />
    <meta property="og:description" content="יש לך הודעה סודית להעביר? הצפן אותה, ורק מי שיש לו/ה את הסיסמא יוכל לקרוא אותה" />
    <!--meta property="og:image" content="http://reva5.co.il/images/xr18_debugger.jpg" />-->

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

    <script>
        function copyLink(elementID) {
            var copyText = document.getElementById(elementID);
            copyText.select();
            copyText.setSelectionRange(0, 99999); /*For mobile devices*/
            document.execCommand("copy");
        }

    </script>

    <style>
        .hebrewField {
            direction: rtl;
            text-align: right;
        }
    </style>

</head>

<body>

<div class="container">

    <?php

    function encrypt($string) {
        //$string = nl2br($string);
        global $password;
        $cipher = "aes-128-gcm";
        $key = substr(hash('sha256', $password, true), 0, 32);
        $iv_len = openssl_cipher_iv_length($cipher);
        $tag_length = 16;
        $iv = openssl_random_pseudo_bytes($iv_len);
        $tag = "";

        $output = openssl_encrypt($string, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag, "", $tag_length);
        $output = base64_encode($iv . $tag . $output);

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
        $encrypt_text = substr($encrypted_string, $iv_len + $tag_length);

        $output = openssl_decrypt($encrypt_text, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);

        return $output;
    }

    function creatlyBitlink($longURL) {
        //$apiv4 = 'https://api-ssl.bitly.com/v4/bitlinks';
        $apiv4 = 'https://api-ssl.bitly.com/v4/shorten';
        $genericAccessToken = '294cab89654e9145c601be5e0f94ba06dd07b777';

        $data = array(
            'long_url' => $longURL
        );
        $payload = json_encode($data);

        $header = array(
            'Authorization: Bearer ' . $genericAccessToken,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        );

        $ch = curl_init($apiv4);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        //print_r($result);
        $res = json_decode($result, true);
        return $res['link'];
    }

    $textToDecrypt = $_GET['textToDecrypt'];
    $textStringToEncrypt = $_POST['textStringToEncrypt'];
    $textStringToDecrypt = $_POST['textStringToDecrypt'];
    $userPassword = $_POST['userPassword'];
    $createShortURL = $_POST['createShortURL'];

    $mainPassword1 = "khjI8C69KGHbc9873kc59TTSX7YGHDX7jsc5v93kv";
    $mainPassword2 = "87vujh38fHC9D0VYh83kcv90HYC83JKF05AUJSMJX";
    $password = $mainPassword1 . $userPassword . $mainPassword2;

    $results = false;
    $error = false;
    $resultsHTML = "";

    if (empty($userPassword) && (!empty($textStringToEncrypt) || (!empty($textStringToDecrypt)))) {
        $resultsHTML .= "Password is required!!<br/>";
        $error = true;
    }

    if ($textToDecrypt) {
        // GET is stronger than PUT
        $textStringToDecrypt = $textToDecrypt;
    }

    if ($textStringToEncrypt && !empty($userPassword)) {
        $encyptedText = encrypt($textStringToEncrypt);
        if ($encyptedText) {
            $resultsHTML .= "<label for='EncryptedText'>Encrypted text</label><div class='form-group row'><div class='col-8'><input type='text' class='form-control' id='EncryptedText' value='" . $encyptedText . "'></div><div class='col-4 '><button onclick='copyLink(\"EncryptedText\")'>Copy Text</button></div></div><br/>";
            if ($createShortURL) {
                $bitlyShortURL = creatlyBitlink($_SERVER['SCRIPT_URI'] . '?textToDecrypt=' . $encyptedText);
                $resultsHTML .= "<label for='bitlyShortURL'>bitly Short URL</label><div class='form-group row'><div class='col-8'><input type='text' class='form-control' id='bitlyShortURL' value='" . $bitlyShortURL . "'></div><div class='col-4 '><button onclick='copyLink(\"bitlyShortURL\")'>Copy Link</button></div></div><br/>";
            }
            $results = true;
        }
    }

    if ($textStringToDecrypt) {
        $decyptedText = Decrypt($textStringToDecrypt);
        if ($decyptedText) {
            //$resultsHTML .= "<label for='EncryptedText'>Decrypted text</label><input type='text'  class='form-control' id='DecryptedText' value='" . $decyptedText . "'><br/>";
            $resultsHTML .= "<h4>Decrypted text:</h4><h5 class='text-danger' id='decyptedText'>" . $decyptedText . "</h5><br/>";
            $results = true;
            // move the below 2 lines to the end ???
            $textStringToDecrypt = "";
            $userPassword = "";
        }
    }

    ?>

    <br/>
    <div class="p-3 bg-warning text-center">
        <h2>Encrypt / Decrypt Text Messages</h2>
    </div>
    <br/>

    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="hebrew" name="hebrew">
        <label for="hebrew">Hebrew (Right to Left)?</label>
    </div>

    <?php
    if (empty($results)) {
        ?>

        <form method="POST" action="<?= $_SERVER['SCRIPT_URI'] ?>">
            <div class="form-group">
                <?php
                if (empty($textStringToDecrypt)) {
                    ?>
                    <label for="textStringToEncrypt">Text to Encrypt</label>
                    <input type="text" class="form-control" id="textStringToEncrypt" name="textStringToEncrypt"
                           placeholder="Type your secret message here.."
                           value="<?= $textStringToEncrypt ?>">
                    <h5 class="text-danger mt-2">OR</h5>
                <?php
                }
                ?>
                <label for="textStringToDecrypt">Text to Decrypt</label>
                <input type="text" class="form-control" id="textStringToDecrypt" name="textStringToDecrypt"
                       placeholder="If you got encrypted text - paste it here"
                       value="<?= $textStringToDecrypt ?>">
                <br/>
                <label for="userPassword">Password key</label>
                <input type="text" size='70' class="form-control" id="userPassword" name="userPassword"
                       placeholder="A password key shared with your partner"
                       value="<?= $userPassword ?>">
                <br/>
                <?php
                if (empty($textStringToDecrypt)) {
                    ?>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="createShortURL" name="createShortURL">
                        <label for="createShortURL">Create short URL (bit.ly)?</label>
                    </div>
                    <?php
                }
                ?>
                <div class="text-center">
                    <button type="submit" class="btn btn-info p-2"><div class="h4">Submit</div></button>
                </div>
            </div>
        </form>

        <?php
    }
    ?>

    <?php
    if ($results) {
        //echo "<h1>Result</h1>";
        echo $resultsHTML;
    } elseif ($error) {
        echo "<h1>Error</h1>" . $resultsHTML;
    } else {
        if (!empty($textStringToDecrypt) && !empty($userPassword)) {
            echo "<h3>No results!!</h3>Please check the password<br/><br/>";
        }
    }
    ?>
    <div class="row">
        <div class="col-12">
            <div class="p-2 bg-warning text-center">
                <h4>
                    <a href="<?= $_SERVER['SCRIPT_URI'] ?>" class="text-white">Click here to start fresh</a>
                </h4>
            </div>
        </div>
    </div>
    <div class="row text-secondary">
        <div class="col-6 text-left">©2020 Gal Sarig</div>
        <div class="col-6 text-right">beta version 0.63</div>
    </div>
</div>

<script>
    $('#hebrew').click(function() {
        $('#textStringToEncrypt').addClass('hebrewField');
        $('#userPassword').addClass('hebrewField');
        $('#decyptedText').addClass('hebrewField');
    });
</script>
</body>