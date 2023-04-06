<?php
class FileManager{
    public static function uploadFile($TMP_FILE, $TARGET){
        if (!move_uploaded_file($TMP_FILE['tmp_name'], $TARGET)){
            return [false, "No se ha podido cargar su imagen"];
        }

        return [true, ""];
    }
}
?>