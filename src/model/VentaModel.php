<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class VentaModel extends GenericModel implements JsonSerializable{
    use JsonSerializeTrait;

    protected $idVen;
    protected $UsuarioVenta;
	public $idVenUsu;
	public $idCliUsu;
    protected $UsuarioCliente;
	protected $productos;
    protected $fechaVen;
    protected $montoVen;
    protected $status;
	protected $pagos;

    public function __construct($propiedades = null){
        parent::__construct('venta', VentaModel::class, $propiedades);
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
	public function getUsuarioVenta() {
		return $this->UsuarioVenta;
	}
	
	/**
	 * @param mixed $UsuarioVenta 
	 * @return self
	 */
	public function setUsuarioVenta($UsuarioVenta): self {
		$this->UsuarioVenta = $UsuarioVenta;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getUsuarioCliente() {
		return $this->UsuarioCliente;
	}
	
	/**
	 * @param mixed $UsuarioCliente 
	 * @return self
	 */
	public function setUsuarioCliente($UsuarioCliente): self {
		$this->UsuarioCliente = $UsuarioCliente;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getProductos() {
		return $this->productos;
	}
	
	/**
	 * @param mixed $productos 
	 * @return self
	 */
	public function setProductos($productos): self {
		$this->productos = $productos;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getFechaVen() {
		return $this->fechaVen;
	}
	
	/**
	 * @param mixed $fechaVen 
	 * @return self
	 */
	public function setFechaVen($fechaVen): self {
		$this->fechaVen = $fechaVen;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getMontoVen() {
		return $this->montoVen;
	}
	
	/**
	 * @param mixed $montoVen 
	 * @return self
	 */
	public function setMontoVen($montoVen): self {
		$this->montoVen = $montoVen;
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
	public function getPagos() {
		return $this->pagos;
	}
	
	/**
	 * @param mixed $pagos 
	 * @return self
	 */
	public function setPagos($pagos): self {
		$this->pagos = $pagos;
		return $this;
	}
}
?>