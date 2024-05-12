<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/model/UsuarioModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class FamiliaModel extends GenericModel implements JsonSerializable{
    use JsonSerializeTrait;
    protected $idFam;
    protected $descFam;
    protected $hashFam;
    protected $direccion;
	protected $fecha_creacion;
	protected $users;
	protected $JefeFamilia;

    public function __construct($propiedades = null) {
        parent::__construct('familias', FamiliaModel::class, $propiedades);
    }

	public function set_value($name, $value){
		$this->{$name} = $value;
    }

	public function __set($name, $value) {

		if (!isset($this->JefeFamilia)){
			$this->JefeFamilia = new UsuarioModel();
		}

        if (property_exists($this->JefeFamilia, $name)){
			$this->JefeFamilia->set_value($name, $value);
        } else {
			$this->{$name} = $value;
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

	/**
	 * @return mixed
	 */
	public function getUsers() {
		return $this->users;
	}
	
	/**
	 * @param mixed $users 
	 * @return self
	 */
	public function setUsers($users): self {
		$this->users = $users;
		return $this;
	}

	public function getJefeFamilia() {
		return $this->JefeFamilia;
	}

	public function setIdUsu($idUsu): self {
		$this->idUsu = $idUsu;
		return $this;
	}

	public function getIdUsu() {
		return $this->idUsu;
	}

	public function setFecha_creacion($fecha_creacion): self {
		$this->fecha_creacion = $fecha_creacion;
		return $this;
	}

	public function getFecha_creacion() {
		return $this->fecha_creacion;
	}
}
?>