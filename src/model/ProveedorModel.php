<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/model/UsuarioModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class ProveedorModel extends GenericModel implements JsonSerializable {
    use JsonSerializeTrait;

    protected $idProv;
    protected $RIF;
    protected $nomProv;

    public function __construct($propiedades = null) {
        parent::__construct('proveedor', ProveedorModel::class, $propiedades);
    }

    public function set_value($name, $value){
		$this->{$name} = $value;
    }

	/**
	 * @return mixed
	 */
	public function getIdProv() {
		return $this->idProv;
	}
	
	/**
	 * @param mixed $idProv 
	 * @return self
	 */
	public function setIdProv($idProv): self {
		$this->idProv = $idProv;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getRIF() {
		return $this->RIF;
	}
	
	/**
	 * @param mixed $RIF 
	 * @return self
	 */
	public function setRIF($RIF): self {
		$this->RIF = $RIF;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getNomProv() {
		return $this->nomProv;
	}
	
	/**
	 * @param mixed $nomProv 
	 * @return self
	 */
	public function setNomProv($nomProv): self {
		$this->nomProv = $nomProv;
		return $this;
	}
}
?>