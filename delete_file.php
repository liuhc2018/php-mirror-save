<?php
    /**
     * Created by PhpStorm.
     * User: captain
     * Date: 2018/4/15
     * Time: 22:28
     */
    $file = $_POST["file"];
    if (!unlink($file))
    {
        echo ("Error delete");
    }
    else
    {
        echo ("Success delete");
    }
?>