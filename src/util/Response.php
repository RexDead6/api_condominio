<?php
class Response{

    public $status;
    private $code;
    public $message;
    public $data;

    public static $CODE_CORRECTO = "CORRECTO";
    public static $INSERCION_EXITOSA = "INSERCION_EXITOSA";
    public static $ACTUALIZACION_EXITOSA = "ACTUALIZACION_EXITOSA";
    public static $ELIMINACION_EXITOSA = "ELIMINACION_EXITOSA";
    public static $ERROR = "ERROR";
    public static $INSERCION_FALLIDA = "INSERCION_FALLIDA";
    public static $ACTUALIZACION_FALLIDA = "ACTUALIZACION_FALLIDA";
    public static $ELIMINACION_FALLIDA = "ELIMINACION_FALLIDA";
    public static $DATOS_INVALIDOS = "DATOS_INVALIDOS";
    public static $FECHA_INVALIDA = "FECHA_INVALIDA";
    public static $DATOS_DUPLICADOS = "DATOS_DUPLICADOS";
    public static $NOT_FOUND = "NOT_FOUND";

    public function __construct($status = null, $message = null, $code = null, $data = null){
        $this->status = $status;
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    //Método para enviar la respuesta en formato JSON
    public function json($obj = null){

        header('Content-Type: application/json; charset=utf-8');
        http_response_code($this->code);

        //Validamos si se envía un objeto/array y lo retornamos en caso de true
        if(is_array($obj) || is_object($obj)){
            return json_encode($obj);
        }

        return json_encode($this);
    }

    //Método para obtener el mensaje por defecto, en caos de querer añadir alguno simplemente crear un 'case'
    public static function getDefaultMessage($code){

        switch($code){

            case self::$CODE_CORRECTO:
                return new Response(true, 'Se ha realizado la operación de manera exitosa!');

            case self::$INSERCION_EXITOSA:
                return new Response(true,'Se ha insertado el registro correctamente!');

            case self::$ACTUALIZACION_EXITOSA:
                return new Response(true, 'Se ha actualizado el registro correctamente!');

            case self::$ELIMINACION_EXITOSA:
                return new Response(true, 'Se ha eliminado el registro correctamente!');

            case self::$ERROR:
                return new Response(false, 'Se ha producido un error al realizar la operación'); 

            case self::$INSERCION_FALLIDA:
                return new Response(false, 'No se ha insertado correctamente el registro');

            case self::$ACTUALIZACION_FALLIDA:
                return new Response(false, 'No se ha actualizado correctamente el registro');

            case self::$ELIMINACION_FALLIDA:
                return new Response(false, 'No se ha eliminado correctamente el registro');

            case self::$DATOS_INVALIDOS:
                return new Response(false, 'Faltan datos o los datos son inválidos');
            
            case self::$FECHA_INVALIDA:
                return new Response(false, 'Error 404, El formato de la fecha o la hora no es válido');

            case self::$DATOS_DUPLICADOS:
                return new Response(false, 'Ya existe un registro con la misma información');

            case self::$NOT_FOUND:
                return new Response(false, 'Error 404, Recurso no encontrado');
            default: 
                return new Response(false, 'No se ha establecido ningún mensaje');
        }
    }

    /* Getters */
    public function getCode(){return $this->code;}
    public function getMessage(){return $this->message;}
    public function getData(){return $this->data;}
    public function getStatus(){return $this->status;}
    
    /* Setters */
    public function setCode($code){$this->code = $code;}
    public function setMessage($message){$this->message = $message;}
    public function setData($data){$this->data = $data;}
    public function setStatus($status){$this->status = $status;}

}
?>