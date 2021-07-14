<?php

include_once "config.php";
include_once "entidades/usuario.php";

$usuario = new Usuario();
$usuario->usuario = "brunocasta";
$usuario->clave = $usuario->encriptarClave("bruno123");
$usuario->nombre = "Bruno";
$usuario->apellido = "Castagnola";
$usuario->correo = "castabruno92@gmail.com";
$usuario->insertar();
echo "Usuario insertado.";
?>