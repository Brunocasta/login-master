<?php

class TipoProducto
{

    private $idtipoproducto;
    private $nombre;


    public function __construct()
    {
    }

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
        return $this;
    }
    public function cargarFormulario($request){
        $this->idtipoproducto = isset($request["id"])? $request["id"] : "";
        $this->nombre = isset($request["txtNombre"])? $request["txtNombre"] : "";
       
    }

    public function insertar()
    {

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE);
        $sql = "INSERT INTO tipoproductos(
            nombre
        )VALUE(
            '". $this->nombre ."'
        );";

        if (!$mysqli->query($sql)) {
            printf("error en query: %s\n", $mysqli->error . "" . $sql);
        }
        $this->idtipoproducto = $mysqli->insert_id;

        $mysqli->close();
    }

    public function actualizar()
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE);
        $sql = "UPDATE tipoproductos SET
                    nombre = '" . $this->nombre . "'
                    WHERE idtipoproducto =" . $this->idtipoproducto;

        if (!$mysqli->query($sql)) {
            printf("error en query: %s\n", $mysqli->error . "" . $sql);
        }
        $mysqli->close();
    }

    public function eliminar(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE);
        $sql = "DELETE FROM tipoproductos WHERE idtipoproducto =" . $this->idtipoproducto;

        if (!$mysqli->query($sql)) {
            printf("error en query:%s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE);
        $sql =" SELECT 
                idtipoproducto,
                nombre
                FROM tipoproductos 
                WHERE idtipoproducto = $this->idtipoproducto";
        
        if (!$resultado = $mysqli->query($sql)) {
            printf("error en query:%s\n", $mysqli->error . " " . $sql);
        }

        if( $fila = $resultado->fetch_assoc()){
             $this->idtipoproducto = $fila["idtipoproducto"];
             $this->nombre = $fila["nombre"];
        }
        $mysqli->close();
        
    }

    public function obtenerTodos(){
        $aTipoProductos=array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE);
        $sql = "SELECT 
        A.idtipoproducto,
        A.nombre
    FROM tipoproductos A
    ORDER BY idtipoproducto DESC";

        $resultado = $mysqli->query($sql);
        if($resultado){
            while($fila=$resultado->fetch_assoc()){
                $obj = new TipoProducto();
                $obj ->idtipoproducto = $fila["idtipoproducto"];
                $obj ->nombre = $fila["nombre"];
                $aTipoProductos[]=$obj;
            }
            return $aTipoProductos;
        }
    }
}
