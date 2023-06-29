<?php
require_once dirname( __DIR__ ) . '/model/FamiliaModel.php';
require_once dirname( __DIR__ ) . '/model/UsuarioModel.php';
require_once dirname( __DIR__ ) . '/model/GruposFamiliaresModel.php';
require_once dirname( __DIR__ ) . '/util/Response.php';
require_once dirname( __DIR__ ) . '/util/ValidateApp.php';
require_once dirname( __DIR__ ) . '/model/TokenAccess.php';

class FamiliaController{
    public function registrarFamilia(){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['idJefeUsu', 'descFam', 'direccion']
        );

        if (!$validate_keys[0]){
            return (new Response(
                false, 
                "Datos incompletos (".implode(", ", $validate_keys[1]).")", 
                400
            ))->json();
        }

        $user = (new UsuarioModel())->where("idUsu", "=", $JSON_DATA['idJefeUsu'])->getFirst();
        if (!isset($user)) {
            return (new Response(
                false, 
                "Usuario no existe",
                400
            ))->json();
        }

        if ((new ValidateApp())->isDuplicated("gruposfamiliares", "idUsu", $JSON_DATA['idJefeUsu'])){
            return (new Response(
                false, 
                "Su cédula ya se encuentra registrada en una Familia", 
                400
            ))->json();
        }

        $idFam = (new FamiliaModel())->insert([
            "descFam"=>$JSON_DATA['descFam'], 
            "direccion"=>$JSON_DATA['direccion'],
            "hashFam" => bin2hex(random_bytes(20)),
            "idUsu" => $JSON_DATA['idJefeUsu']
        ]);

        if ($idFam == false){
            return (new Response(
                false, 
                "No se ha podido registrar a la familia, intende de nuevo",
                500
            ))->json();
        }

        $user->setStatusUsu(1);
        $user->where("idUsu", "=", $JSON_DATA['idJefeUsu'])->update();

        $result = (new GruposFamiliaresModel())->insert([
            "idUsu" => $JSON_DATA['idJefeUsu'],
            "idFam" => $idFam
        ]);
        
        return (new Response(
            true, 
            "Familia Registrada exitosamente",
            201
        ))->json();
    }

    public function getAll($token){
        $familias = (new FamiliaModel())->inner("usuarios", "idUsu")->getAll();

        foreach ($familias as $familia) {
            $familia->setUsers((new UsuarioModel())->inner("gruposfamiliares", "idUsu")->inner("roles", "idRol")->where("gru.idFam", "=", $familia->getIdFam())->getAll());
        }
        
        return (new Response(
            count($familias) > 0,
            count($familias) > 0 ? "Familias encontrados" : "No hay familias disponibles",
            count($familias) > 0 ? 200 : 500,
            $familias
        ))->json();
    }

    public function getByToken($token){

        if (!(new TokenAccess())->validateToken($token)){
            return (new Response(
                false,
                "Sesion no encontrada",
                404
            ))->json();
        }

        $idFam = explode("|", $token)[1];

        $fam = (new FamiliaModel)->inner("usuarios", "idUsu")->where("fam.idFam", "=", $idFam)->getFirst();

        if (!isset($fam)){
            return (new Response(
                false, 
                "Familia no encontrada", 
                404
            ))->json();
        }

        $fam->setUsers((new UsuarioModel())->inner("gruposfamiliares", "idUsu")->inner("roles", "idRol")->where("gru.idFam", "=", $idFam)->getAll());

        $response = new Response(
            true, 
            "Datos encontrados", 
            200, 
            $fam
        );
        return $response->json();
    }

    public function registrarMiembroFamiliar($token){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['hash']
        );

        if (!$validate_keys[0]){
            return (new Response(
                false, 
                "Datos incompletos (".implode(", ", $validate_keys[1]).")", 
                400
            ))->json();
        }

        if (!(new TokenAccess())->validateToken($token)){
            return (new Response(
                false,
                "Sesion no encontrada",
                404
            ))->json();
        }

        if ((new ValidateApp())->isDuplicated("gruposfamiliares", "idUsu", explode("|", $token)[0])){
            return (new Response(
                false, 
                "Su cédula ya se encuentra registrada en una Familia", 
                400
            ))->json();
        }

        $idFam = explode("|", $token)[1];
        if ($idFam != "00"){
            return (new Response(
                false, 
                "Usted ya pertenece a una Familia", 
                400
            ))->json();
        }

        $familia = (new FamiliaModel())->where("hashFam", "=", $JSON_DATA['hash'])->getFirst();
        if (!isset($familia)){
            return (new Response(
                false, 
                "codigo HASH no coincide con ninguna familia", 
                404
            ))->json();
        }

        $usuario = (new UsuarioModel())->where("idUsu", "=", explode("|", $token)[0])->getFirst();
        if (!isset($usuario)){
            return (new Response(
                false, 
                "Usuario no existe", 
                404
            ))->json();
        }

        $usuario->setStatusUsu(1);
        $usuario->where("idUsu", "=", explode("|", $token)[0])->update();

        $result = (new GruposFamiliaresModel())->insert([
            'idUsu' => explode("|", $token)[0],
            'idFam' => $familia->getIdFam()
        ]);

        $user = (new UsuarioModel())->where("idUsu", "=", explode("|", $token)[0])->getFirst();

        (new TokenAccess())->where("token", "=", $token)->delete();

        $token = $user->getIdUsu()."|".$familia->getIdFam()."|".$user->getRol()->getIdRol()."|".bin2hex(random_bytes(50));

        (new TokenAccess())->insert([
            "idUsu" => $user->getIdUsu(),
            "token" => $token
        ]);

        $data_response = [
            "token" => $token
        ];

        return (new Response(
            true, 
            "Relacion familiar creada", 
            201,
            $data_response
        ))->json();
    }

    public function editarJefeFamiliar($token){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['idJefeFamilia', 'idFam']
        );

        if (!$validate_keys[0]){
            return (new Response(
                false, 
                "Datos incompletos (".implode(", ", $validate_keys[1]).")", 
                400
            ))->json();
        }

        $fam = (new FamiliaModel())->where('idFam', '=', $JSON_DATA['idFam'])->getFirst();
        if (!isset($fam)) {
            return (new Response(
                false, 
                "Familia no existe", 
                404
            ))->json();
        }

        $usuario = (new UsuarioModel())->where('idUsu', '=', $JSON_DATA['idJefeFamilia'])->getFirst();
        if (!isset($usuario)) {
            return (new Response(
                false, 
                "Usuario no existe", 
                404
            ))->json();
        }

        $group = (new GruposFamiliaresModel())->where('idUsu', '=', $JSON_DATA['idJefeFamilia'])->where('idFam', '=', $JSON_DATA['idFam'])->getFirst();
        if (!isset($group)) {
            return (new Response(
                false, 
                "Usuario no pertenece a esta familia", 
                404
            ))->json();
        }

        $fam->setIdUsu($JSON_DATA['idJefeFamilia']);
        $success = $fam->where("idFam", "=", $JSON_DATA['idFam'])->update();
        return (new Response(
            $success, 
            $success ? "Jefe familiar actualizado" : "No se ha podido actualizar ", 
            $success ? 200 : 500
        ))->json();
    }
}
?>