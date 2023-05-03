<?php
require_once 'conexion.php';
require_once 'php-jwt-main/src/JWT.php';
require_once 'php-jwt-main/src/Key.php';
require_once 'php-jwt-main/src/SignatureInvalidException.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

if(!empty($_POST["btnIngresar"])){

    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    $sql = $conexion->query("SELECT * FROM USUARIO WHERE USUARIO = '$usuario' AND CLAVE = '$clave'");

    if($sql){
        $data = $sql->fetch_assoc();

        $id         = $data['id'];
        $usuario    = $data['usuario'];
        $clave      = $data['clave'];

        $jwt = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6Im1hcmlhIiwiaWF0IjoxNTE2MjM5MDIyfQ.nKrp407H2DSH-fbBYoPVMq9CaeR03ra90rtH0YsjD98";
        $decodeJwtToken = JWT::decode($jwt, new Key("hola", 'HS256'));

        print_r((array)$decodeJwtToken);

        //$jwtToken = createJwt($id, $usuario, $clave);
        //print_r($jwtToken);
            
        //header("location:inicio.php");
    }
}

/**
 * Creacion del JWT
 */
function createJwt($id, $usuario, $clave){
    
    $time = time();
    $key  = "miclave";                  /**Clave privada */
    $algoritmo = "HS256";

    $payload = array(
        "iat"   => $time,                           //Tiempo en el que inicia el Token
        "exp"   => $time + (60*60*24),              //Tiempo de Expiracion del Token
        "data"  =>[
            "id"        => $id,
            "usuario"   => $usuario,
            "clave"     => $clave
        ]
    );

    //JWT::encode($payload, $key, $algoritmo);
    $jwtToken = JWT::encode($payload, $key, $algoritmo);

    return $jwtToken;
}

/**
 * Decodificacion de un JWT
 */
function decodeJwt($jwtToken){

    /**Decodificacion */
    $privateKey = "miclave";

    $decodeJwtToken = JWT::decode($jwtToken, new Key($privateKey, 'HS256'));

    //print_r((array)$decodeJwtToken);
    return (array) $decodeJwtToken;     /**Convertimos Array y retornamos */
}


?>