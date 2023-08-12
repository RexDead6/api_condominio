<?php
require_once dirname(__DIR__) . '/model/UsuarioModel.php';
require_once dirname(__DIR__) . '/model/FamiliaModel.php';
require_once dirname(__DIR__) . '/model/TokenAccess.php';
require_once dirname(__DIR__) . '/util/Crypt.php';
require_once dirname(__DIR__) . '/util/Response.php';
require_once dirname(__DIR__) . '/util/ValidateApp.php';

class UsuarioController
{

    public function test()
    {
        return (
            new Response(
            true,
            "API FUNCIONANDO",
            200
            )
        )->json();
    }

    public function registrarUsu()
    {
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist(
            $JSON_DATA,
            ['cedUsu', 'nomUsu', 'apeUsu', 'generoUsu', 'telUsu', 'claveUsu']
        );

        if (!$validate_keys[0]) {
            return (
                new Response(
                false,
                "Datos incompletos (" . implode(", ", $validate_keys[1]) . ")",
                400
                )
            )->json();
        }

        if ((new ValidateApp())->isDuplicated("usuarios", "cedUsu", $JSON_DATA['cedUsu'])) {
            return (
                new Response(
                false,
                "Su cédula ya se encuentra registrada",
                400
                )
            )->json();
        }

        $JSON_DATA['claveUsu'] = Crypt::hash($JSON_DATA['claveUsu']);

        $id_usuario = (new UsuarioModel())->insert($JSON_DATA);
        $user = (new UsuarioModel())->where("idUsu", "=", $id_usuario)->inner("roles", 'idRol')->getFirst();
        $token = $id_usuario . "|00|" . $user->getRol()->getNivelRol() . "|" . bin2hex(random_bytes(50));

        (new TokenAccess())->insert([
            "idUsu" => $id_usuario,
            "token" => $token
        ]);

        $data_response = [
            "token" => $token
        ];
        return (
            new Response(
            true,
            "Usuario registrado exitosamente",
            201,
            $data_response
            )
        )->json();
    }
    public function getAll()
    {
        $response = new Response(
            true,
            "Datos encontrados",
            200,
            (new UsuarioModel())->inner("roles", "idRol")->getAll()
        );
        return $response->json();
    }

    public function getInactive()
    {
        $response = new Response(
            true,
            "Datos encontrados",
            200,
            (new UsuarioModel())->inner("roles", "idRol")->where("statusUsu", "=", 0)->getAll()
        );
        return $response->json();
    }

    public function getById($value, $column = "idUsu")
    {
        $user = (new UsuarioModel())->inner("roles", "idRol")->where($column, "=", $value)->getFirst();
        return (
            new Response(
            isset($user),
            isset($user) ? "Usuario encontrado" : "Usuario inexistente",
            isset($user) ? 200 : 404,
            $user
            )
        )->json();
    }

    public function Login()
    {
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $validate_keys = ValidateApp::keys_array_exist($JSON_DATA, ['cedula', 'clave']);

        if (!$validate_keys[0]) {
            return (
                new Response(
                false,
                "Datos incompletos (" . implode(", ", $validate_keys[1]) . ")",
                400
                )
            )->json();
        }

        $user = (new UsuarioModel())->inner("roles", "idRol")->where("cedUsu", "=", $JSON_DATA['cedula'])->getFirst();

        if (!isset($user)) {
            return (
                new Response(
                false,
                "Esta Cédula no se encuentra registrada",
                404
                )
            )->json();
        }

        if (!Crypt::verify($JSON_DATA['clave'], $user->getClaveUsu())) {
            return (
                new Response(
                false,
                "Clave de usuario incorrecta",
                400
                )
            )->json();
        }

        $fam = (new FamiliaModel())->inner("gruposfamiliares", "idFam")->inner("usuarios", "idUsu")->where("gru.idUsu", "=", $user->getIdUsu())->getFirst();
        $tokenfam = ($fam != null) ? $fam->getIdFam() : "00";

        $token = $user->getIdUsu() . "|" . $tokenfam . "|" . $user->getRol()->getNivelRol() . "|" . bin2hex(random_bytes(50));

        (new TokenAccess())->insert([
            "idUsu" => $user->getIdUsu(),
            "token" => $token
        ]);

        $data_response = [
            "token" => $token,
            "isJefe" => ($fam != null) && $fam->getJefeFamilia()->getIdUsu() == $user->getIdUsu()
        ];

        return (
            new Response(
            true,
            "Usuario autenticado exitosamente",
            200,
            $data_response
            )
        )->json();
    }

    public function logout($token)
    {
        (new TokenAccess())->where("token", "=", $token)->delete();
        return (
            new Response(
            true,
            "Sesion cerrada",
            200
            )
        )->json();
    }

    public function update($token)
    {
        $JSON_DATA = json_decode(file_get_contents('php://input'), true) ?? [];
        $user = (new UsuarioModel())->where('idUsu', '=', explode("|", $token)[0])->getFirst();
        if (!isset($user)) {
            return (
                new Response(
                false,
                "Usuario no existe",
                404
                )
            )->json();
        }
        if (isset($JSON_DATA['nomUsu']))
            $user->setNomUsu($JSON_DATA['nomUsu']);
        if (isset($JSON_DATA['apeUsu']))
            $user->setApeUsu($JSON_DATA['apeUsu']);
        if (isset($JSON_DATA['generoUsu']))
            $user->setGeneroUsu($JSON_DATA['generoUsu']);
        if (isset($JSON_DATA['telUsu']))
            $user->setTelUsu($JSON_DATA['telUsu']);

        $result = $user->where('idUsu', '=', explode("|", $token)[0])->update();
        return (
            new Response(
            $result,
            $result ? "Usuario actualizado exitosamente" : "No se ha podido actualizado el usuario",
            $result ? 200 : 500
            )
        )->json();
    }

    public function update_image($token)
    {
        if (!isset($_FILES['img'])) {
            return (
                new Response(
                false,
                "Debe enviar la imagen del usuario",
                400
                )
            )->json();
        }

        $user = (new UsuarioModel())->where("idUsu", "=", explode("|", $token)[0])->getFirst();

        $extension = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
        $path_image = "profile/{$user->getIdUsu()}.{$extension}";
        $resp = FileManager::uploadFile($_FILES['img'], str_replace("\\", "/", dirname(__DIR__)) . "/assets/" . $path_image);
        if (!$resp[0]) {
            return (
                new Response(
                false,
                    $resp[1],
                400
                )
            )->json();
        }

        $user->setImgUsu($path_image);
        $success = $user->where("idUsu", "=", explode("|", $token)[0])->update();

        return (
            new Response(
            $success,
            $success ? "Imagen Actualizada" : "No se ha podido actualizar su imagen",
            $success ? 200 : 500
            )
        )->json();
    }

    public function update_rol($token, $idUsu, $idRol) {
        if (((int) explode("|", $token)[2]) > 2) {
            return (
                new Response(
                false,
                "Permisos insuficientes",
                401
                )
            )->json();
        }  

        $usuario = (new UsuarioModel())->where("idUsu", "=", $idUsu)->getFirst();
        if (!isset($usuario)) {
            return (new Response(
                false, 
                "Usuario no existe", 
                404
            ))->json();
        }
        $rol= (new RolModel())->where("idRol", "=", $idRol)->getFirst();
        if (!isset($rol)) {
            return (new Response(
                false, 
                "Rol no existe", 
                404
            ))->json();
        }

        $usuario->setIdRol($idRol); 
        $result = $usuario->where("idUsu", "=", $idUsu)->update();
        return (new Response(
            $result,
            $result ? "Usuario actualizado" : "No se ha podido actualizar el usuario",
            $result ? 200 : 500,
        ))->json();
    }
}
?>