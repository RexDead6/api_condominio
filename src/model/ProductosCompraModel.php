<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/model/UsuarioModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class ProductosCompraModel extends GenericModel implements JsonSerializable {
    use JsonSerializeTrait;

    protected $producto;
    protected $cantidad;
    protected $costo;

    public function __construct($propiedades = null) {
        parent::__construct('compraproductos', ProductosCompraModel::class, $propiedades);
    }

    public function set_value($name, $value){
		$this->{$name} = $value;
    }

	public function __set($name, $value) {
        if (isset($this->$name)){
            $this->{$name} = $value;
        } else {
            if (!isset($this->producto)){
                $this->producto = new ProductoModel();
            }
            $this->producto->set_value($name, $value);
        }
    }

	/**
	 * @return mixed
	 */
	public function getProducto() {
		return $this->producto;
	}
	
	/**
	 * @param mixed $producto 
	 * @return self
	 */
	public function setProducto($producto): self {
		$this->producto = $producto;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getCantidad() {
		return $this->cantidad;
	}
	
	/**
	 * @param mixed $cantidad 
	 * @return self
	 */
	public function setCantidad($cantidad): self {
		$this->cantidad = $cantidad;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getCosto() {
		return $this->costo;
	}
	
	/**
	 * @param mixed $costo 
	 * @return self
	 */
	public function setCosto($costo): self {
		$this->costo = $costo;
		return $this;
	}
}
?>