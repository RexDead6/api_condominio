<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/model/UsuarioModel.php';
require_once dirname( __DIR__ ) . '/model/ProveedorModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class CompraModel extends GenericModel implements JsonSerializable {
    use JsonSerializeTrait;

    protected $idCom;
    protected $proveedor;
    protected $monto;
    protected $porcentaje;
	protected $productos;
	protected $fecha_compra;

    public function __construct($propiedades = null) {
        parent::__construct('compra', CompraModel::class, $propiedades);
    }

	public function set_value($name, $value){
		$this->{$name} = $value;
    }

    public function __set($name, $value) {
        if (isset($this->$name)){
            $this->{$name} = $value;
        } else {
            if (!isset($this->proveedor)){
                $this->proveedor = new ProveedorModel();
            }
            $this->proveedor->set_value($name, $value);
        }
    }

	/**
	 * @return mixed
	 */
	public function getIdCom() {
		return $this->idCom;
	}
	
	/**
	 * @param mixed $idCom 
	 * @return self
	 */
	public function setIdCom($idCom): self {
		$this->idCom = $idCom;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getProveedor() {
		return $this->proveedor;
	}
	
	/**
	 * @param mixed $proveedor 
	 * @return self
	 */
	public function setProveedor($proveedor): self {
		$this->proveedor = $proveedor;
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
	
	/**
	 * @return mixed
	 */
	public function getPorcentaje() {
		return $this->porcentaje;
	}
	
	/**
	 * @param mixed $porcentaje 
	 * @return self
	 */
	public function setPorcentaje($porcentaje): self {
		$this->porcentaje = $porcentaje;
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
	public function getFecha_compra() {
		return $this->fecha_compra;
	}
	
	/**
	 * @param mixed $fecha_compra 
	 * @return self
	 */
	public function setFecha_compra($fecha_compra): self {
		$this->fecha_compra = $fecha_compra;
		return $this;
	}
}
?>