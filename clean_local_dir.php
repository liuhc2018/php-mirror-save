<?php
    /**
     * Created by PhpStorm.
     * User: captain
     * Date: 2018/4/17
     * Time: 13:45
     */
    include "client_const.php" ;

    $dirname = localDestDir;//local dir to compute
    $fileArray=array();

    //recursive count files in dir
    function listdir($dirname) {
        global $fileArray;
        $ds = opendir($dirname);
        while($file = readdir($ds)) {
            $path = $dirname.'/'.$file;
            if(is_dir($path)) {
                //echo "DIR:".$file."<br>";
                if($file != "." && $file != "..") {
                    listdir($path);
                }
            }
            else {
                //$a=fileatime($path);
                //echo $path."访问时间：".date("Y-m-d H:i:s",$a)."\n";
                array_push($fileArray,$path);
            }
        }
    }

    function display($fileArray){
        for($i=0;$i<count($fileArray);$i++){
            print($fileArray[$i]."\n");
        }
    }

    function sortByAccessTime($a, $b) {
        $time1=fileatime($a);
        $time2=fileatime($b);
        return $time1===$time2?0:($time1>$time2?1:-1);
    }

    //recursive compute file size in dir
    function totdir($dirname) {
        $tot = 0;
        $ds = opendir($dirname);
        while($file = readdir($ds)) {
            $path = $dirname.'/'.$file;
            if(is_dir($path)) {
                if($file != "." && $file != "..") {
                    $tot += totdir($path);
                }
            }
            else {
                $tot += filesize($path);
            }
        }
        return $tot;
    }

    function fileExistRemote($fileName,$remoteDestDir=remoteDestDir,$url="http://".remoteIp."/exist_file.php"){
        $post_data = array(
            //要上传的本地文件地址
            "file" => $remoteDestDir.$fileName
        );
        $ch = curl_init();
        curl_setopt($ch , CURLOPT_URL , $url);
        curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch , CURLOPT_POST, 1);
        curl_setopt($ch , CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        echo $output;
        if($output == "Exist\n"){
            return true;
        }else{
            return false;
        }
    }

    $dirSize=totdir($dirname);
    echo "$dirname total size: ".$dirSize." bytes\n";

    if($dirSize>maxSize) {//exceed max size
        listdir($dirname);
        usort($fileArray, 'sortByAccessTime');
        for($i=0;$i<count($fileArray);$i++){
            $fileName=basename($fileArray[$i]);
            if(fileExistRemote($fileName)){
                $dirSize-=filesize($fileArray[$i]);
                unlink($fileArray[$i]);//delete local file
                echo "delete file ".$fileArray[$i]."\n";
            }
            if($dirSize<=maxSize){
                break;
            }
        }
    }

    echo "$dirname total size: ".$dirSize." bytes\n";
?>