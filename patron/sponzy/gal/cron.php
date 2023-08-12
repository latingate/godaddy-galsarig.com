<?php
$myfile = fopen("newfile.txt", "a");
$txt = "The time is " . date("h:i:sa" . "\n");
fwrite($myfile, $txt);
fclose($myfile);
?>
