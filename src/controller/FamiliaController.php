<?php
require_once dirname( __DIR__ ) . '/model/FamiliaModel.php';
require_once dirname( __DIR__ ) . '/model/UsuarioModel.php';
require_once dirname( __DIR__ ) . '/model/UrbanizacionModel.php';
require_once dirname( __DIR__ ) . '/model/GruposFamiliaresModel.php';
require_once dirname( __DIR__ ) . '/model/FamiliaUrbanizacion.php';
require_once dirname( __DIR__ ) . '/util/Response.php';
require_once dirname( __DIR__ ) . '/util/ValidateApp.php';
require_once dirname( __DIR__ ) . '/model/TokenAccess.php';

class FamiliaController{
    public function registrarFamilia(){
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['idJefeUsu', 'descFam', 'direccion', 'idUrb']
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

        $urb = (new UrbanizacionModel())->where("idUrb", "=", $JSON_DATA["idUrb"])->getFirst();
        if (!isset($urb)) {
            return (new Response(
                false, 
                "Comunidad no existe",
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

        $famUrb = (new FamiliaUrbanizacionModel())->insert(["idFam"=>$idFam, "idUrb"=>$JSON_DATA["idUrb"]]);

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

    public function getByUrb($idUrb){
        $fams_raw = (new FamiliaUrbanizacionModel())->where("idUrb", "=", $idUrb)->getAll();
        $familias = [];
        foreach ($fams_raw as $fam_raw) {
            $familias[] = (new FamiliaModel())->inner("usuarios", "idUsu")->where("idFam", "=", $fam_raw->getIdFam())->getFirst();
        }

        return (new Response(
            count($familias) > 0,
            count($familias) > 0 ? "Familias encontrados" : "No hay familias disponibles",
            count($familias) > 0 ? 200 : 404,
            $familias
        ))->json();
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

        $user = (new UsuarioModel())->inner("roles", "idRol")->where("idUsu", "=", explode("|", $token)[0])->getFirst();

        (new TokenAccess())->where("token", "=", $token)->delete();

        $tokenNew = $user->getIdUsu()."|".$familia->getIdFam()."|2|".bin2hex(random_bytes(50));

        (new TokenAccess())->insert([
            "idUsu" => $user->getIdUsu(),
            "token" => $tokenNew
        ]);

        $data_response = [
            "token" => $tokenNew
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

        $userJefe = $fam->getJefeFamilia()->getIdUsu();

        $fam->setIdUsu($JSON_DATA['idJefeFamilia']);
        $success = $fam->where("idFam", "=", $JSON_DATA['idFam'])->update();
        (new TokenAccess())->where("idUsu", "=", $userJefe)->delete();
        (new TokenAccess())->where("idUsu", "=", $JSON_DATA['idJefeFamilia'])->delete();
        
        return (new Response(
            $success, 
            $success ? "Jefe familiar actualizado" : "No se ha podido actualizar ", 
            $success ? 200 : 500
        ))->json();
    }

    public function eliminarMiembroFamilia($token, $idUsu) {
        $user = (new UsuarioModel())->where("idUsu", "=", $idUsu)->getFirst();
        if (!isset($user)) {
            return (new Response(
                false, 
                "Usuario no encontrado", 
                404
            ))->json();
        }

        $familia = (new FamiliaModel())->where("idFam", "=", explode("|", $token)[1])->getFirst();

        if ($familia->getJefeFamilia()->getIdUsu() != ((int) explode("|", $token)[0])) {
            return (new Response(
                false, 
                "Usted no es el jefe de su familia para realizar esta accion", 
                400
            ))->json();
        }

        $relacionFam = (new GruposFamiliaresModel())->where("idUsu", "=", $idUsu)->where("idFam", "=", $familia->getIdFam())->getFirst();
        if (!isset($relacionFam)){
            return (new Response(
                false, 
                "El usuario no pertenece a su Familia", 
                400
            ))->json();
        }

        $success = (new GruposFamiliaresModel())->where("idUsu", "=", $idUsu)->where("idFam", "=", $familia->getIdFam())->delete();
        if ($success) {
            (new TokenAccess())->where("idUsu", "=", $idUsu)->delete();
            $user->setStatusUsu(0);
            $user->where("idUsu", "=", $idUsu)->update();

        }

        return (new Response(
            $success, 
            $success ? "El usuario ha sido eliminado de su familia" : "Ha ocurrido un problema, intente de nuevo", 
            $success ? 200 : 500
        ))->json();
    }

    public function reportFamilias($idUrb){
        $usuarios = (new BaseModel())->rawQuery("SELECT usu.cedUsu, usu.nomUsu, usu.apeUsu, usu.generoUsu, usu.telUsu, fam.descFam FROM usuarios AS usu
                                                    INNER JOIN gruposfamiliares AS gru ON usu.idUsu = gru.idUsu
                                                    INNER JOIN familias AS fam ON fam.idFam = gru.idFam
                                                    INNER JOIN familiaurbanizacion AS furb ON furb.idFam = fam.idFam
                                                    WHERE furb.idUrb = ".$idUrb."
                                                    ORDER BY fam.descFam ASC;");
        
        $pdfManager = new PDFManager();
        $pdfManager->template("usuarios.template.html", [
            "n_habitantes" => count($usuarios),
            "rows" => $usuarios
        ]);
        $ran = bin2hex(random_bytes(5));
        $name = "usuarios_".$ran.".pdf";
        $pdfManager->output($name);
        return (
            new Response(
                true,
                "Reporte generado con exito",
                200,
                [
                    "name"=>$name
                ]
            )
        )->json();
    }
}
?>