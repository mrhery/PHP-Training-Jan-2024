<?php
header("Content-Type: text/plain");

//$str = file_get_contents("php://input");
//$str = file_put_contents("data.csv");
//echo $str;

$f = fopen("php://input", "rb");
//while(!feof($f)){
//	echo fgetc($f);
//	break;
//}

$f = fopen("data.csv", "a+");

$id = $_POST["id"];

fwrite($f, "$id,anwar,011,anwar@anwar");
fclose($f);

//$fo = fopen("data.csv");



