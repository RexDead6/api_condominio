<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/model/FamiliaModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class FamiliaUrbanizacionModel extends GenericModel implements JsonSerializable{
	use JsonSerializeTrait;

    protected $idUrb;
    protected $idFam;
	protected $familia;

    public function __construct($propiedades = null) {
        parent::__construct('familiaurbanizacion', FamiliaUrbanizacionModel::class, $propiedades);
    }

	public function set_value($name, $value){
		$this->{$name} = $value;
    }

    public function __set($name, $value) {

		if (!isset($this->familia)){
			$this->familia = new FamiliaModel();
		}

        if (property_exists($this->familia, $name)){
			$this->familia->set_value($name, $value);
        } else {
            $this->{$name} = $value;
        }
    }

	/**
	 * @return mixed
	 */
	public function getFamilia() {
		return $this->familia;
	}
	
	/**
	 * @param mixed $idUsu 
	 * @return self
	 */
	public function setFamilia($familia): self {
		$this->familia = $familia;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getIdUrb() {
		return $this->idUrb;
	}
	
	/**
	 * @param mixed $idUsu 
	 * @return self
	 */
	public function setIdUrb($idUrb): self {
		$this->idUrb = $idUrb;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getIdFam() {
		return $this->idFam;
	}
	
	/**
	 * @param mixed $idFam 
	 * @return self
	 */
	public function setIdFam($idFam): self {
		$this->idFam = $idFam;
		return $this;
	}
}
?>