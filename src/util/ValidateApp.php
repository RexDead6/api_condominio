<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';

use LDAP\Result;
class ValidateApp extends BaseModel{

    /**
     * Metodo para validar que existan ciertas claves dentro de un array
     * 
     * @param array: $array datos para validar las key
     * @param mixed: $keys llaves a validar en el array, convierte en array al pasar otro tipo de dato
     * 
     * @return array [
     *                  0 => boolean: Resultado de la validación,
     *                  1 => array: llaves faltantes
     *               ] 
     */
    public static function keys_array_exist($array, $keys){
        $result = [true, array()];
        if (!is_array($keys)){
            $keys = [$keys];
        }

        foreach ($keys as $key){
            if (!array_key_exists($key, $array)){
                $result[0] = false;
                $result[1][] = $key;
            }
        }

        return $result;
    }

    //Validar que un registro no se encuentre duplicado
    public function isDuplicated($table, $column, $value){

        $sql = "SELECT $column FROM $table WHERE $column = '$value'";
        $query = $this->getConnection()->prepare($sql);
        $query->execute();
                
        if($query->rowCount() > 0){

            return true;
        }
    }
}
?>