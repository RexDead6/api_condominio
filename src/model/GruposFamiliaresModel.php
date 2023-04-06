<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class GruposFamiliaresModel extends GenericModel{

    protected $idUsu;
    protected $idFam;

    public function __construct($propiedades = null) {
        parent::__construct('gruposfamiliares', GruposFamiliaresModel::class, $propiedades);
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