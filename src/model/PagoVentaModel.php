<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class PagoVentaModel extends GenericModel implements JsonSerializable{
    use JsonSerializeTrait;

    protected $idVen;
    protected $tipoPag;
    protected $refPag;
    protected $monto;

    public function __construct($propiedades = null){
        parent::__construct('pagosventa', PagoVentaModel::class, $propiedades);
    }

	/**
	 * @return mixed
	 */
	public function getIdVen() {
		return $this->idVen;
	}
	
	/**
	 * @param mixed $idVen 
	 * @return self
	 */
	public function setIdVen($idVen): self {
		$this->idVen = $idVen;
		return $this;
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
	public function getMonto() {
		return $this->monto;
	}
	
	/**
	 * @param mixed $monto 
	 * @return self
	 */
	public function setMonto($monto): self {
		$this->monto = $monto;
		return $this;
	}
}
?>