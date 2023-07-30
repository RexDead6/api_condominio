<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/model/UsuarioModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class ProductoModel extends GenericModel implements JsonSerializable {
    use JsonSerializeTrait;

    protected $idPro;
	protected $usuario;
    protected $nomPro;
	protected $divisa;
    protected $costoPro;
    protected $existPro;
	protected $imgPro;
	protected $status;

    public function __construct($propiedades = null) {
        parent::__construct('producto', ProductoModel::class, $propiedades);
    }
    
    public function set_value($name, $value){
		$this->{$name} = $value;
    }

	public function __set($name, $value) {
        if (isset($this->$name)){
            $this->{$name} = $value;
        } else {
            if (!isset($this->usuario)){
                $this->usuario = new UsuarioModel();
            }
            $this->usuario->set_value($name, $value);
        }
    }

	/**
	 * @return mixed
	 */
	public function getIdPro() {
		return $this->idPro;
	}
	
	/**
	 * @param mixed $idPro 
	 * @return self
	 */
	public function setIdPro($idPro): self {
		$this->idPro = $idPro;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getUser() {
		return $this->User;
	}
	
	/**
	 * @param mixed $User 
	 * @return self
	 */
	public function setUser($User): self {
		$this->User = $User;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getNomPro() {
		return $this->nomPro;
	}
	
	/**
	 * @param mixed $nomPro 
	 * @return self
	 */
	public function setNomPro($nomPro): self {
		$this->nomPro = $nomPro;
		return $this;
	}

	/**
	 * @param mixed $divisa 
	 * @return self
	 */
	public function setDivisa($divisa): self {
		$this->divisa = $divisa;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getMontoSer() {
		return $this->montoSer;
	}
	
	/**
	 * @return mixed
	 */
	public function getCostoPro() {
		return $this->costoPro;
	}
	
	/**
	 * @param mixed $costoPro 
	 * @return self
	 */
	public function setCostoPro($costoPro): self {
		$this->costoPro = $costoPro;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getExistPro() {
		return $this->existPro;
	}
	
	/**
	 * @param mixed $existPro 
	 * @return self
	 */
	public function setExistPro($existPro): self {
		$this->existPro = $existPro;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getImgPro() {
		return $this->imgPro;
	}
	
	/**
	 * @param mixed $imgPro 
	 * @return self
	 */
	public function setImgPro($imgPro): self {
		$this->imgPro = $imgPro;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUsuario() {
		return $this->usuario;
	}
	
	/**
	 * @param mixed $usuario 
	 * @return self
	 */
	public function setUsuario($usuario): self {
		$this->usuario = $usuario;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * @param mixed $status 
	 * @return self
	 */
	public function setStatus($status): self {
		$this->status = $status;
		return $this;
	}
}
?>