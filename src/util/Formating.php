<?php
class Formating{
    public static function numberFormat($number){
        return number_format($number, 2, ",", ".");
    }
}
?>