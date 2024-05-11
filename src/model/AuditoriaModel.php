<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/model/UsuarioModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class AuditoriaModel extends GenericModel implements JsonSerializable {
    use JsonSerializeTrait;
    
    protected $idAud;
    protected $Usuario;
    protected $Descripcion;
    protected $fecha;

    public function __construct($propiedades = null) {
        parent::__construct('auditoria', AuditoriaModel::class, $propiedades);
    }

    public function __set($name, $value) {

		if (!isset($this->Usuario)){
			$this->Usuario = new UsuarioModel();
		}

        if (property_exists($this->Usuario, $name)){
			$this->Usuario->set_value($name, $value);
        } else {
            $this->{$name} = $value;
        }
    }
}
?>