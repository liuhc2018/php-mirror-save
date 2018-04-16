<?php
    /**
     * Created by PhpStorm.
     * User: captain
     * Date: 2018/4/15
     * Time: 22:39
     */
    $file = $_POST["file"];
    if(file_exists($file)){
        echo "Exist\n";
    }else{
        echo "Not exist\n";
    }
?>