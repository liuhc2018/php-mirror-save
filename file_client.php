<?php
    include "client_const.php";

    /**
     * @param string $srcFilePath
     * @param string $localDestDir
     * @param string $remoteDestDir
     * @return NULL
     */
    function saveFile($srcFilePath,$localDestDir=localDestDir,$remoteDestDir=remoteDestDir){
        if(!file_exists($srcFilePath)){
            return false;
        }
        if (!file_exists($localDestDir) && !mkdir($localDestDir, 0777, true)) {
            return false;
        }

        $srcFileName=basename($srcFilePath);
        $localDestPath=NULL;
        if($localDestDir[strlen($localDestDir)-1]==='\\' || $localDestDir[strlen($localDestDir)-1]==='/'){
            $localDestPath=$localDestDir.$srcFileName;
        }else{
            $localDestPath=$localDestDir."/".$srcFileName;
        }
        if(file_exists($localDestPath) && hash_file('md5', $localDestPath) === hash_file('md5', $srcFilePath)){
            return false;
        }
        rename($srcFilePath,$localDestPath);
        asynUploadFile($localDestPath,$remoteDestDir);
        echo "finish";
    }

    function asynUploadFile($file_path,$remoteDestDir=remoteDestDir,$url="http://".remoteIp."/upload_file.php"){
        $post_data = array(
            //要上传的本地文件地址
            "filePath" => $file_path,
            "savePath"=>$remoteDestDir,
            "uploadUrl"=>$url
        );
        $ch = curl_init();
        curl_setopt($ch , CURLOPT_URL , "http://localhost/asyn_file_upload_client.php");
        curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch , CURLOPT_POST, 1);
        curl_setopt($ch , CURLOPT_POSTFIELDS, $post_data);
        curl_exec($ch);
        curl_close($ch);
    }

    /**
     * @param string $fileName
     * @param string $localDestDir
     * @param string $remoteDestDir
     * @return string denotes file path
     */
    function getFile($fileName,$localDestDir=localDestDir,$remoteDestDir=remoteDestDir){
        if(fileExistLocal($fileName,$localDestDir)){
            return $localDestDir.$fileName;
        }else if(fileExistRemote($fileName,$remoteDestDir)){
            curlDownFile("http://localhost/".$remoteDestDir.$fileName,$localDestDir);
            return $localDestDir.$fileName;
        }else{
            return "";
        }
    }

    function curlDownFile($file_url, $save_path = localDestDir) {
        if (trim($file_url) == '') {
            return false;
        }
        if (trim($save_path) == '') {
            $save_path = './';
        }
        //create save dir
        if (!file_exists($save_path) && !mkdir($save_path, 0777, true)) {
            return false;
        }
        $file_name_pos = strrpos($file_url, '/');
        $filename = substr($file_url, $file_name_pos+1);

        if($save_path[strlen($save_path)-1]==='\\' || $save_path[strlen($save_path)-1]==='/'){
            $filename=$save_path.$filename;
        }else{
            $filename=$save_path."/".$filename;
        }

        set_time_limit(0);
        $fp = fopen ($filename, 'w+');
        $ch = curl_init($file_url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        return true;
    }

    /**
     * @param string $fileName
     * @param string $localDestDir
     * @param string $remoteDestDir
     * @return NULL
     */
    function deleteFile($fileName,$localDestDir=localDestDir,$remoteDestDir=remoteDestDir){
        if(fileExistLocal($fileName,$localDestDir)){
            unlink($localDestDir.$fileName);
        }
        if(fileExistRemote($fileName,$remoteDestDir)){
            deleteRemoteFile($fileName,$remoteDestDir);
        }
    }

    function fileExistLocal($fileName,$localDestDir=localDestDir){
        $localDestFile=NULL;
        if($localDestDir[strlen($localDestDir)-1]==='\\' || $localDestDir[strlen($localDestDir)-1]==='/'){
            $localDestFile=$localDestDir.$fileName;
        }else{
            $localDestFile=$localDestDir."/".$fileName;
        }
        return file_exists($localDestFile);
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

    function deleteRemoteFile($fileName,$remoteDestDir=remoteDestDir,$url="http://".remoteIp."/delete_file.php"){
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
    }

?>