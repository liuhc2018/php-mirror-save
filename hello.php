<?php
/**
 * Created by PhpStorm.
 * User: captain
 * Date: 2018/4/16
 * Time: 23:39
 */

while(ob_get_level()) ob_end_clean();
header('Connection: close');
ignore_user_abort();
ob_start();
echo("Connection Closed\n");
$size = ob_get_length();
header("Content-Length: $size");
ob_end_flush();
flush();

sleep(5);

?>