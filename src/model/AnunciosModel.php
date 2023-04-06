<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/model/UsuarioModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class AnunciosModel extends GenericModel implements JsonSerializable{
    use JsonSerializeTrait;
    
    protected $idAnu;
    protected $Usuario;
    protected $descAnu;
	protected $image;
    protected $fechaAnu;

    public function __construct($propiedades = null) {
        parent::__construct('anuncios', AnunciosModel::class, $propiedades);
    }

    public function __set($name, $value) {
        if (isset($this->$name)){
            $this->{$name} = $value;
        } else {
            if (!isset($this->Usuario)){
                $this->Usuario = new UsuarioModel();
            }
            $this->Usuario->set_value($name, $value);
        }
    }

	/**
	 * @return mixed
	 */
	public function getIdAnu() {
		return $this->idAnu;
	}
	
	/**
	 * @param mixed $idAnu 
	 * @return self
	 */
	public function setIdAnu($idAnu): self {
		$this->idAnu = $idAnu;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getUsuario() {
		return $this->Usuario;
	}
	
	/**
	 * @param mixed $Usuario 
	 * @return self
	 */
	public function setUsuario($Usuario): self {
		$this->Usuario = $Usuario;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getDescAnu() {
		return $this->descAnu;
	}
	
	/**
	 * @param mixed $descAnu 
	 * @return self
	 */
	public function setDescAnu($descAnu): self {
		$this->descAnu = $descAnu;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getFechaAnu() {
		return $this->fechaAnu;
	}
	
	/**
	 * @param mixed $fechaAnu 
	 * @return self
	 */
	public function setFechaAnu($fechaAnu): self {
		$this->fechaAnu = $fechaAnu;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getImage() {
		return $this->image;
	}
	
	/**
	 * @param mixed $image 
	 * @return self
	 */
	public function setImage($image): self {
		$this->image = $image;
		return $this;
	}
}
?>