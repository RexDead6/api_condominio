<?php
require_once '../model/GenericModel.php';
require_once '../model/RolModel.php';
require_once '../util/JsonSerialize.php';

class ServicioModel extends GenericModel implements JsonSerializable{
	use JsonSerializeTrait;
    
    protected $idSer;
    protected $PagoMovil;
    protected $descSer;
    protected $isMensualSer;
    protected $montoSer;
    protected $statusSer;

    public function __construct($propiedades = null) {
        parent::__construct('servicios', ServicioModel::class, $propiedades);
    }

	public function __set($name, $value){
		if (isset($this->$name)){
			$this->{$name} = $value;
		} else {
			if (!isset($this->PagoMovil)){
				$this->PagoMovil = new PagoMovilModel();
			}
			$this->PagoMovil->set_value($name, $value);
		}
	}

	/**
	 * @return mixed
	 */
	public function getIdSer() {
		return $this->idSer;
	}
	
	/**
	 * @param mixed $idSer 
	 * @return self
	 */
	public function setIdSer($idSer): self {
		$this->idSer = $idSer;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getPagoMovil() {
		return $this->PagoMovil;
	}
	
	/**
	 * @param mixed $PagoMovil 
	 * @return self
	 */
	public function setPagoMovil($PagoMovil): self {
		$this->PagoMovil = $PagoMovil;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getDescSer() {
		return $this->descSer;
	}
	
	/**
	 * @param mixed $descSer 
	 * @return self
	 */
	public function setDescSer($descSer): self {
		$this->descSer = $descSer;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getIsMensualSer() {
		return $this->isMensualSer;
	}
	
	/**
	 * @param mixed $isMensualSer 
	 * @return self
	 */
	public function setIsMensualSer($isMensualSer): self {
		$this->isMensualSer = $isMensualSer;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getMontoSer() {
		return $this->montoSer;
	}
	
	/**
	 * @param mixed $montoSer 
	 * @return self
	 */
	public function setMontoSer($montoSer): self {
		$this->montoSer = $montoSer;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getStatusSer() {
		return $this->statusSer;
	}
	
	/**
	 * @param mixed $statusSer 
	 * @return self
	 */
	public function setStatusSer($statusSer): self {
		$this->statusSer = $statusSer;
		return $this;
	}
}
?>