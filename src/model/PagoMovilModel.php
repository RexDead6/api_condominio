<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/model/BaseModel.php';
require_once dirname( __DIR__ ) . '/model/BancosModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class PagoMovilModel extends GenericModel implements JsonSerializable{
    use JsonSerializeTrait;

    protected $idPmv;
    public $idUsu;
    protected $Banco;
    protected $status;
    protected $telPmv;
    protected $cedPmv;
	protected $venta;

    public function __construct($propiedades = null){
        parent::__construct('pagomovil', PagoMovilModel::class, $propiedades);
    }

	public function set_value($name, $value){
		$this->{$name} = $value;
    }

    public function __set($name, $value) {
        if (isset($this->$name)){
            $this->{$name} = $value;
        } else {
            if (!isset($this->Banco)){
                $this->Banco = new BancosModel();
            }
            $this->Banco->set_value($name, $value);
        }
    }

	/**
	 * @return mixed
	 */
	public function getIdPmv() {
		return $this->idPmv;
	}
	
	/**
	 * @param mixed $idPmv 
	 * @return self
	 */
	public function setIdPmv($idPmv): self {
		$this->idPmv = $idPmv;
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
	public function getIdBan() {
		return $this->idBan;
	}
	
	/**
	 * @param mixed $idBan 
	 * @return self
	 */
	public function setIdBan($idBan): self {
		$this->idBan = $idBan;
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
	
	/**
	 * @return mixed
	 */
	public function getTelPmv() {
		return $this->telPmv;
	}
	
	/**
	 * @param mixed $telPmv 
	 * @return self
	 */
	public function setTelPmv($telPmv): self {
		$this->telPmv = $telPmv;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getCedPmv() {
		return $this->cedPmv;
	}
	
	/**
	 * @param mixed $cedPmv 
	 * @return self
	 */
	public function setCedPmv($cedPmv): self {
		$this->cedPmv = $cedPmv;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getVenta() {
		return $this->venta;
	}
	
	/**
	 * @param mixed $venta 
	 * @return self
	 */
	public function setVenta($venta): self {
		$this->venta = $venta;
		return $this;
	}
}
?>