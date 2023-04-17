<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';

class TokenAccess extends GenericModel{
    protected $id;
    protected $idUsu;
    protected $token;
    protected $fecha_registro;
    public function __construct($propiedades = null) {
        parent::__construct('token_access', TokenAccess::class, $propiedades);
    }

    public function validateToken($token){
        return $this->where("token", "=", $token ?? "")->getFirst() != null;
    }

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return self
	 */
	public function setId($id): self {
		$this->id = $id;
		return $this;
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
	public function getToken() {
		return $this->token;
	}
	
	/**
	 * @param mixed $token 
	 * @return self
	 */
	public function setToken($token): self {
		$this->token = $token;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getFecha_registro() {
		return $this->fecha_registro;
	}
	
	/**
	 * @param mixed $fecha_registro 
	 * @return self
	 */
	public function setFecha_registro($fecha_registro): self {
		$this->fecha_registro = $fecha_registro;
		return $this;
	}
}
?>