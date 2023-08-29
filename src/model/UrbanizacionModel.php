<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class UrbanizacionModel extends GenericModel implements JsonSerializable{
    use JsonSerializeTrait;

    protected $idUrb;
    protected $nomUrb;
    protected $direccion;
    protected $status;
    protected $isAdmin;

    public function __construct($propiedades = null){
        parent::__construct('urbanizacion', UrbanizacionModel::class, $propiedades);
    }

    public function set_value($name, $value){
		$this->{$name} = $value;
    }

    public function setIsAdmin($isAdmin){
        $this->isAdmin = $isAdmin;
    }

    public function getIdUrb(){
        return $this->idUrb;
    }

    public function setIdUrb($idUrb){
        $this->idUrb = $idUrb;
    }

    public function getNomUrb() {
        return $this->nomUrb;
    }

    public function setNomUrn($nomUrb) {
        $this->nomUrb = $nomUrb;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}
?>