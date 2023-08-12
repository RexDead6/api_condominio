<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class FamiliaUrbanizacionModel extends GenericModel implements JsonSerializable{
	use JsonSerializeTrait;

    protected $idUrb;
    protected $idFam;

    public function __construct($propiedades = null) {
        parent::__construct('familiaurbanizacion', FamiliaUrbanizacionModel::class, $propiedades);
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