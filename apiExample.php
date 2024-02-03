<?php

$o = fopen("php://input", "rb");
$str = stream_get_contents($o);
fclose($o);

$obj = json_decode($str);
echo "username is " . $obj->username;
