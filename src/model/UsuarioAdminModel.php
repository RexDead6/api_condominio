<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class UsuarioAdminModel extends GenericModel implements JsonSerializable{
    use JsonSerializeTrait;

    protected $idUsu;
    protected $idUrb;

    public function __construct($propiedades = null){
        parent::__construct('usuarioadmin', UsuarioAdminModel::class, $propiedades);
    }

    public function set_value($name, $value){
		$this->{$name} = $value;
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

    public function getIdUrb(){
        return $this->idUrb;
    }

    public function setIdUrb($idUrb){
        $this->idUrb = $idUrb;
    }
}
?>