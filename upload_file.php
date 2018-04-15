<?php
    class FileInfo{
        var $name,$type,$location;
        var $size;
        function __construct($name,$type,$size,$location){
            $this->name=$name;
            $this->type=$type;
            $this->size=$size;
            $this->location=$location;
        }
    }

    class Result{
        var $status;
        var $fileInfo;

        function __construct($status,$fileInfo){
            $this->status=$status;
            $this->fileInfo=$fileInfo;
        }
    }

    const upDir="upload/";

    function upload($savePath=upDir){
        if ($_FILES["file"]["error"] > 0) {
            echo "error：: " . $_FILES["file"]["error"] . "\n";
        } else {
            //create save dir
            if (!file_exists($savePath) && !mkdir($savePath, 0777, true)) {
                return new Result(false, NULL);;
            }

            $localDestFile=NULL;
            if($savePath[strlen($savePath)-1]==='\\' || $savePath[strlen($savePath)-1]==='/'){
                $localDestFile=$savePath.$_FILES["file"]["name"];
            }else{
                $localDestFile=$savePath."/".$_FILES["file"]["name"];
            }

            $fileInfo = new FileInfo($_FILES["file"]["name"], $_FILES["file"]["type"], $_FILES["file"]["size"], $localDestFile);
            move_uploaded_file($_FILES["file"]["tmp_name"], $localDestFile);
            return new Result(true, $fileInfo);
        }
    }

    $savePath="savePath";
    if(isset($_POST[$savePath])){
        $result=upload($_POST[$savePath]);
    }else{
        $result=upload();
    }

    if($result->status){
        echo "Success upload";
    }else{
        echo "Fail upload";
    }
?>