<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/model/UsuarioModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class AjustesModel extends GenericModel implements JsonSerializable{
    use JsonSerializeTrait;
    
    protected $name;
    protected $value;

    public function __construct($propiedades = null) {
        parent::__construct('ajustes', AjustesModel::class, $propiedades);
    }

    /**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @param mixed $key 
	 * @return self
	 */
	public function setName($name): self {
		$this->name = $name;
		return $this;
	}

    /**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * @param mixed $value 
	 * @return self
	 */
	public function setValue($value): self {
		$this->value = $value;
		return $this;
	}
}
?>