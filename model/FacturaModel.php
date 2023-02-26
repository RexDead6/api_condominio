<?php
require_once '../model/GenericModel.php';
require_once '../model/ServicioModel.php';
require_once '../model/FamiliaModel.php';
require_once '../util/JsonSerialize.php';

class FacturaModel extends GenericModel implements JsonSerializable{
    use JsonSerializeTrait;
    protected $idFac;
    protected $Servicio;
    protected $Familia;
    protected $montoFac;
    protected $fechapagoFac;
	protected $status;
	protected $meses;

    public function __construct($propiedades = null) {
        parent::__construct('factura', FacturaModel::class, $propiedades);
    }

    public function __set($name, $value) {
        if (isset($this->$name)){
            $this->{$name} = $value;
        } else {
            if (!isset($this->Servicio)){
                $this->Servicio = new ServicioModel();
            }
            if (isset($this->Servicio->$name)){
                $this->Servicio->set_value($name, $value);
            } else {
                if (!isset($this->Familia)){
                    $this->Familia = new FamiliaModel();
                }
                $this->Familia->set_value($name, $value);
            }
        }
    }

	/**
	 * @return mixed
	 */
	public function getIdFac() {
		return $this->idFac;
	}
	
	/**
	 * @param mixed $idFac 
	 * @return self
	 */
	public function setIdFac($idFac): self {
		$this->idFac = $idFac;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getServicio() {
		return $this->Servicio;
	}
	
	/**
	 * @param mixed $Servicio 
	 * @return self
	 */
	public function setServicio($Servicio): self {
		$this->Servicio = $Servicio;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getFamilia() {
		return $this->Familia;
	}
	
	/**
	 * @param mixed $Familia 
	 * @return self
	 */
	public function setFamilia($Familia): self {
		$this->Familia = $Familia;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getMontoFac() {
		return $this->montoFac;
	}
	
	/**
	 * @param mixed $montoFac 
	 * @return self
	 */
	public function setMontoFac($montoFac): self {
		$this->montoFac = $montoFac;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getFechapagoFac() {
		return $this->fechapagoFac;
	}
	
	/**
	 * @param mixed $fechapagoFac 
	 * @return self
	 */
	public function setFechapagoFac($fechapagoFac): self {
		$this->fechapagoFac = $fechapagoFac;
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
	public function getMeses() {
		return $this->meses;
	}
	
	/**
	 * @param mixed $meses 
	 * @return self
	 */
	public function setMeses($meses): self {
		$this->meses = $meses;
		return $this;
	}
}
?>