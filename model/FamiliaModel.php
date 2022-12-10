<?php
require_once '../model/GenericModel.php';
require_once '../model/UsuarioModel.php';
require_once '../util/JsonSerialize.php';

class FamiliaModel extends GenericModel implements JsonSerializable{
    use JsonSerializeTrait;
    protected $idFam;
    protected $jefeUsu;
    protected $descFam;
    protected $hashFam;
    protected $direccion;

    public function __construct($propiedades = null) {
        parent::__construct('familias', FamiliaModel::class, $propiedades);
    }

    public function __set($name, $value) {
        if (isset($this->$name)){
            $this->{$name} = $value;
        } else {
            if (!isset($this->jefeUsu)){
                $this->jefeUsu = new UsuarioModel();
            }
            $this->jefeUsu->set_value($name, $value);
        }
    }

	/**
	 * @return mixed
	 */
	public function getIdFam() {
		return $this->idFam;
	}
	
	/**
	 * @param mixed $idFam 
	 * @return self
	 */
	public function setIdFam($idFam): self {
		$this->idFam = $idFam;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getJefeUsu() {
		return $this->jefeUsu;
	}
	
	/**
	 * @param mixed $jefeUsu 
	 * @return self
	 */
	public function setJefeUsu($jefeUsu): self {
		$this->jefeUsu = $jefeUsu;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getDescFam() {
		return $this->descFam;
	}
	
	/**
	 * @param mixed $descFam 
	 * @return self
	 */
	public function setDescFam($descFam): self {
		$this->descFam = $descFam;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getHashFam() {
		return $this->hashFam;
	}
	
	/**
	 * @param mixed $hashFam 
	 * @return self
	 */
	public function setHashFam($hashFam): self {
		$this->hashFam = $hashFam;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDireccion() {
		return $this->direccion;
	}
	
	/**
	 * @param mixed $direccion 
	 * @return self
	 */
	public function setDireccion($direccion): self {
		$this->direccion = $direccion;
		return $this;
	}
}
?>