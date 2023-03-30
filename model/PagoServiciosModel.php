<?php
require_once '../model/GenericModel.php';
require_once '../util/JsonSerialize.php';

class PagoServiciosModel extends GenericModel implements JsonSerializable{
    use JsonSerializeTrait;

    public $idFac;
    protected $tipoPag;
    protected $refPag;
    protected $montoPag;
    protected $comprobantePag;

    public function __construct($propiedades = null) {
        parent::__construct('pagoservicios', PagoServiciosModel::class, $propiedades);
    }

	/**
	 * @return mixed
	 */
	public function getTipoPag() {
		return $this->tipoPag;
	}
	
	/**
	 * @param mixed $tipoPag 
	 * @return self
	 */
	public function setTipoPag($tipoPag): self {
		$this->tipoPag = $tipoPag;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getRefPag() {
		return $this->refPag;
	}
	
	/**
	 * @param mixed $refPag 
	 * @return self
	 */
	public function setRefPag($refPag): self {
		$this->refPag = $refPag;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getMontoPag() {
		return $this->montoPag;
	}
	
	/**
	 * @param mixed $montoPag 
	 * @return self
	 */
	public function setMontoPag($montoPag): self {
		$this->montoPag = $montoPag;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getComprobantePag() {
		return $this->comprobantePag;
	}
	
	/**
	 * @param mixed $comprobantePag 
	 * @return self
	 */
	public function setComprobantePag($comprobantePag): self {
		$this->comprobantePag = $comprobantePag;
		return $this;
	}
}
?>