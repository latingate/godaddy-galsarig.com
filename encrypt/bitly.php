<?php

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

echo creatlyBitlink('https://stackoverflow.com/questions/ask');

?>
