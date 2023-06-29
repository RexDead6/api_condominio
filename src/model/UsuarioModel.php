<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/model/RolModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class UsuarioModel extends GenericModel implements JsonSerializable{
	use JsonSerializeTrait;
    protected $idUsu;
    protected $Rol;
	public $idRol;
    protected $statusUsu;
    protected $cedUsu;
    protected $nomUsu;
    protected $apeUsu;
	protected $generoUsu;
    protected $telUsu;
    public $claveUsu;
    protected $imgUsu;
	protected $Productos;

    public function __construct($propiedades = null) {
        parent::__construct('usuarios', UsuarioModel::class, $propiedades);
    }

	public function set_value($name, $value){
		$this->{$name} = $value;
    }

    public function __set($name, $value) {

		if (!isset($this->Rol)){
			$this->Rol = new RolModel();
		}

        if (isset($this->Rol->$name)){
			$this->Rol->set_value($name, $value);
        } else {
            $this->{$name} = $value;
        }
    }

	/**
	 * @return mixed
	 */
	public function getIdUsu() {
		return $this->idUsu;
	}
	
	/**
	 * @param mixed $idUsu 
	 * @return self
	 */
	public function setIdUsu($idUsu): self {
		$this->idUsu = $idUsu;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getStatusUsu() {
		return $this->statusUsu;
	}
	
	/**
	 * @param mixed $statusUsu 
	 * @return self
	 */
	public function setStatusUsu($statusUsu): self {
		$this->statusUsu = $statusUsu;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getCedUsu() {
		return $this->cedUsu;
	}
	
	/**
	 * @param mixed $cedUsu 
	 * @return self
	 */
	public function setCedUsu($cedUsu): self {
		$this->cedUsu = $cedUsu;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getNomUsu() {
		return $this->nomUsu;
	}
	
	/**
	 * @param mixed $nomUsu 
	 * @return self
	 */
	public function setNomUsu($nomUsu): self {
		$this->nomUsu = $nomUsu;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getApeUsu() {
		return $this->apeUsu;
	}
	
	/**
	 * @param mixed $apeUsu 
	 * @return self
	 */
	public function setApeUsu($apeUsu): self {
		$this->apeUsu = $apeUsu;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getTelUsu() {
		return $this->telUsu;
	}
	
	/**
	 * @param mixed $telUsu 
	 * @return self
	 */
	public function setTelUsu($telUsu): self {
		$this->telUsu = $telUsu;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getClaveUsu() {
		return $this->claveUsu;
	}
	
	/**
	 * @param mixed $claveUsu 
	 * @return self
	 */
	public function setClaveUsu($claveUsu): self {
		$this->claveUsu = $claveUsu;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getImgUsu() {
		return $this->imgUsu;
	}
	
	/**
	 * @param mixed $imgUsu 
	 * @return self
	 */
	public function setImgUsu($imgUsu): self {
		$this->imgUsu = $imgUsu;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRol() {
		return $this->Rol;
	}
	
	/**
	 * @param mixed $Rol 
	 * @return self
	 */
	public function setRol($Rol): self {
		$this->Rol = $Rol;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getGeneroUsu() {
		return $this->generoUsu;
	}
	
	/**
	 * @param mixed $generoUsu 
	 * @return self
	 */
	public function setGeneroUsu($generoUsu): self {
		$this->generoUsu = $generoUsu;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getProductos() {
		return $this->Productos;
	}
	
	/**
	 * @param mixed $Productos 
	 * @return self
	 */
	public function setProductos($Productos): self {
		$this->Productos = $Productos;
		return $this;
	}
}

?>