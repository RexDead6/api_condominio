<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class BancosModel extends GenericModel implements JsonSerializable{
    use JsonSerializeTrait;

    protected $idBan;
    protected $nomBan;
    protected $codBan;

    public function __construct($propiedades = null){
        parent::__construct('bancos', BancosModel::class, $propiedades);
    }

    public function set_value($name, $value){
		$this->{$name} = $value;
    }

	/**
	 * @return mixed
	 */
	public function getIdBan() {
		return $this->idBan;
	}
	
	/**
	 * @param mixed $idBan 
	 * @return self
	 */
	public function setIdBan($idBan): self {
		$this->idBan = $idBan;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getNomBan() {
		return $this->nomBan;
	}
	
	/**
	 * @param mixed $nomBan 
	 * @return self
	 */
	public function setNomBan($nomBan): self {
		$this->nomBan = $nomBan;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getCodBan() {
		return $this->codBan;
	}
	
	/**
	 * @param mixed $codBan 
	 * @return self
	 */
	public function setCodBan($codBan): self {
		$this->codBan = $codBan;
		return $this;
	}
}
?>