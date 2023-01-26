<?php
require_once '../model/GenericModel.php';
require_once '../util/JsonSerialize.php';

class NotificacionesModel extends GenericModel implements JsonSerializable{
    use JsonSerializeTrait;

    protected $idNot;
    protected $tituloNot;
    protected $descNot;
    protected $imgNot;
    public $statusNot;
    public $allNot;
    public $idUsu;
	protected $tipoNot;

    public function __construct($propiedades = null) {
        parent::__construct('pushmessages', NotificacionesModel::class, $propiedades);
    }

	/**
	 * @return mixed
	 */
	public function getIdNot() {
		return $this->idNot;
	}
	
	/**
	 * @param mixed $idNot 
	 * @return self
	 */
	public function setIdNot($idNot): self {
		$this->idNot = $idNot;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getTituloNot() {
		return $this->tituloNot;
	}
	
	/**
	 * @param mixed $tituloNot 
	 * @return self
	 */
	public function setTituloNot($tituloNot): self {
		$this->tituloNot = $tituloNot;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getDescNot() {
		return $this->descNot;
	}
	
	/**
	 * @param mixed $descNot 
	 * @return self
	 */
	public function setDescNot($descNot): self {
		$this->descNot = $descNot;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getImgNot() {
		return $this->imgNot;
	}
	
	/**
	 * @param mixed $imgNot 
	 * @return self
	 */
	public function setImgNot($imgNot): self {
		$this->imgNot = $imgNot;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTipoNot() {
		return $this->tipoNot;
	}
	
	/**
	 * @param mixed $tipoNot 
	 * @return self
	 */
	public function setTipoNot($tipoNot): self {
		$this->tipoNot = $tipoNot;
		return $this;
	}
}
?>