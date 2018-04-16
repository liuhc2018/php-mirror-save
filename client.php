<?php
    const localDestDir="E:/Code/test/";
    const remoteDestDir="upload/";
    const remoteIp="localhost";

    //file_get_contents('http://localhost/hello.php');

    $ch = curl_init();
    curl_setopt($ch , CURLOPT_URL , 'http://localhost/hello.php');
    curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch , CURLOPT_POST, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    echo("main thread");

    /**
     * @param string $srcFileName
     * @param string $localDestDir
     * @param string $remoteDestDir
     * @return NULL
     */
    function saveFile($srcFileName,$localDestDir=localDestDir,$remoteDestDir=remoteDestDir){
        if (!file_exists($localDestDir) && !mkdir($localDestDir, 0777, true)) {
            return false;
        }

        $localDestFile=NULL;
        if($localDestDir[strlen($localDestDir)-1]==='\\' || $localDestDir[strlen($localDestDir)-1]==='/'){
            $localDestFile=$localDestDir.$srcFileName;
        }else{
            $localDestFile=$localDestDir."/".$srcFileName;
        }
        rename($srcFileName,$localDestFile);
        curlUploadFile($localDestFile,$remoteDestDir);
        echo "finish";
    }

    //saveFile("Setup_pdfeditor.exe");

    function curlUploadFile($file_path,$remoteDestDir=remoteDestDir,$url="http://".remoteIp."/upload_file.php"){
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

    //curlUploadFile("E:/Code/test/check_vcredist.exe");

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

    //echo getFile("Setup_pdfeditor.exe");

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

    //curlDownFile('http://localhost/upload/PhpStorm-2018.1.1.exe','E:/Code/test/');

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

    //deleteFile("Setup_pdfeditor.exe");

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
        if($output == "Exist"){
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