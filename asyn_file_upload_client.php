<?php
/**
 * Created by PhpStorm.
 * User: captain
 * Date: 2018/4/17
 * Time: 10:10
 */
    include "file_client.php";

    function curlUploadFile($file_path,$remoteDestDir,$url){
        $post_data = array(
            //要上传的本地文件地址
            "file" => new CURLFile($file_path),
            "savePath"=>$remoteDestDir
        );
        $ch = curl_init();
        curl_setopt($ch , CURLOPT_URL , $url);
        curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch , CURLOPT_POST, 1);
        curl_setopt($ch , CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        echo $output;
    }

    while(ob_get_level()) ob_end_clean();
    header('Connection: close');
    ignore_user_abort();
    ob_start();
    echo("Connection Closed\n");
    $size = ob_get_length();
    header("Content-Length: $size");
    ob_end_flush();
    flush();

    $val=curlUploadFile($_POST["filePath"],$_POST["savePath"],$_POST["uploadUrl"]);

?>