<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class RolModel extends GenericModel implements JsonSerializable {

	use JsonSerializeTrait;
    protected $idRol;
    protected $nomRol;
    protected $nivelRol;

    public function __construct($propiedades = null) {
        parent::__construct('roles', RolModel::class, $propiedades);
    }

    public function set_value($name, $value){
		$this->{$name} = $value;
    }

	/**
	 * @return mixed
	 */
	public function getIdRol() {
		return $this->idRol;
	}
	
	/**
	 * @param mixed $idRol 
	 * @return self
	 */
	public function setIdRol($idRol): self {
		$this->idRol = $idRol;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getNomRol() {
		return $this->nomRol;
	}
	
	/**
	 * @param mixed $nomRol 
	 * @return self
	 */
	public function setNomRol($nomRol): self {
		$this->nomRol = $nomRol;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getNivelRol() {
		return $this->nivelRol;
	}
	
	/**
	 * @param mixed $nivelRol 
	 * @return self
	 */
	public function setNivelRol($nivelRol): self {
		$this->nivelRol = $nivelRol;
		return $this;
	}
}
?>