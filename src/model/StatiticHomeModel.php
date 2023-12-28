<?php
require_once dirname( __DIR__ ) . '/model/GenericModel.php';
require_once dirname( __DIR__ ) . '/model/UsuarioModel.php';
require_once dirname( __DIR__ ) . '/util/JsonSerialize.php';

class StatiticHomeModel implements JsonSerializable {
    use JsonSerializeTrait;
    
    protected $ultimosAnuncios;
    protected $serviciosPorPagar;

    public function setUltimosAnuncios($ultimosAnuncios) {
        $this->ultimosAnuncios = $ultimosAnuncios;
        return $this;
    }

    public function getUltimosAnuncios(){
        return $this->ultimosAnuncios;
    }

    public function setServiciosPorPagar($serviciosPorPagar) {
        $this->serviciosPorPagar = $serviciosPorPagar;
        return $this;
    }

    public function getServiciosPorPagar(){
        return $this->serviciosPorPagar;
    }
}
?>