<?php


class Venta
{
    private $idventa;
    private $fecha;
    private $cantidad;
    private $preciounitario;
    private $total;
    private $fk_idcliente;
    private $fk_idproducto;
    private $nombre_cliente;
    private $nombre_producto;

    public function __construct()
    {
        $this->cantidad = 0;
        $this->preciounitario = 0.0;
        $this->total = 0.0;
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

    public function cargarFormulario($request)
    {
        $this->idventa = isset($request["id"]) ? $request["id"] : "";

        if (isset($request["txtAnio"]) && isset($request["txtMes"]) && isset($request["txtDia"])) {
            $this->fecha = $request["txtAnio"] . "-" .  $request["txtMes"] . "-" .  $request["txtDia"] . " " . $request["txtHora"];
        }
        $this->cantidad = isset($request["txtCantidad"]) ? $request["txtCantidad"] : "";
        $this->preciounitario = isset($request["txtPrecioUni"]) ? $request["txtPrecioUni"] : 0.0;
        $this->total = isset($request["txtTotal"]) ? $request["txtTotal"] : 0.0;
        $this->fk_idcliente = isset($request["lstCliente"]) ? $request["lstCliente"] : "";
        $this->fk_idproducto = isset($request["lstProducto"]) ? $request["lstProducto"] : "";
    }

    public function insertar()
    {

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE);
        $sql = "INSERT INTO ventas(
                fecha,
                cantidad,
                preciounitario,
                total,
                fk_idcliente,
                fk_idproducto
            )VALUE ( 
                '" . $this->fecha . "',
                '" . $this->cantidad . "',
                '" . $this->preciounitario . "',
                '" . $this->total . "',
                '" . $this->fk_idcliente . "',
                '" . $this->fk_idproducto . "'

            );";

        if (!$mysqli->query($sql)) {
            printf("error en query:%s\n", $mysqli->error . " " . $sql);
        }
        $this->idventa = $mysqli->insert_id;

        $mysqli->close();
    }

    public function actualizar()
    {

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE);
        $sql = "UPDATE ventas SET
                fecha = '" . $this->fecha . "',
                cantidad = " . $this->cantidad . ",
                preciounitario = " . $this->preciounitario . ",
                total =" . $this->total . ",
                fk_idcliente ='" . $this->fk_idcliente . "',
                fk_idproducto ='" . $this->fk_idproducto . "'
                WHERE idventa =" . $this->idventa;

        if (!$mysqli->query($sql)) {
            printf("error en query:%s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }




    public function eliminar()
    {

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE);
        $sql = "DELETE FROM ventas WHERE idventa =" . $this->idventa;

        if (!$mysqli->query($sql)) {
            printf("error en query:%s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId()
    {

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE);
        $sql = "SELECT 
                idventa,
                cantidad,
                preciounitario,
                total,
                fk_idcliente,
                fk_idproducto
                FROM ventas
                WHERE idventa = $this->idventa";

        if (!$resultado = $mysqli->query($sql)) {
            printf("error en query:%s\n", $mysqli->error . " " . $sql);
        }

        if ($fila = $resultado->fetch_assoc()) {
            $this->idventa = $fila["idventa"];
            $this->cantidad = $fila["cantidad"];
            $this->preciounitario = $fila["preciounitario"];
            $this->total = $fila["total"];
            $this->fk_idcliente = $fila["fk_idcliente"];
            $this->fk_idproducto = $fila["fk_idproducto"];
        }

        $mysqli->close();
    }

    public function obtenerTodos()
    {

        $aVentas = array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE);
        $sql = "SELECT 
                A.idventa,
                A.fecha,
                A.cantidad,
                A.preciounitario,
                A.total,
                A.fk_idcliente,
                A.fk_idproducto
            FROM ventas A
            ORDER BY idventa DESC";

        $resultado = $mysqli->query($sql);
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $obj = new Venta();
                $obj->idventa = $fila["idventa"];
                $obj->fecha = $fila["fecha"];
                $obj->cantidad = $fila["cantidad"];
                $obj->preciounitario = $fila["preciounitario"];
                $obj->total = $fila["total"];
                $obj->fk_idcliente = $fila["fk_idcliente"];
                $obj->fk_idproducto = $fila["fk_idproducto"];
                $aVentas[] = $obj;
            }
        }
        return $aVentas;
    }


    public function cargarGrilla()
    {

        $aVentas = array();
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE);
        $sql = "SELECT 
                A.idventa,
                A.fecha,
                A.cantidad,
                A.fk_idcliente,
                B.nombre as nombre_cliente,
                A.fk_idproducto,
                A.total,
                A.preciounitario,
                C.nombre as nombre_producto
           FROM ventas A
            INNER JOIN clientes B ON A.fk_idcliente = B.idcliente
            INNER JOIN productos C ON A.fk_idproducto = C.idproducto
            ORDER BY A.fecha DESC";

        $resultado = $mysqli->query($sql);
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $obj = new Venta();
                $obj->idventa = $fila["idventa"];
                $obj->fecha = $fila["fecha"];
                $obj->cantidad = $fila["cantidad"];
                $obj->preciounitario = $fila["preciounitario"];
                $obj->total = $fila["total"];
                $obj->fk_idcliente = $fila["fk_idcliente"];
                $obj->nombre_cliente = $fila["nombre_cliente"];
                $obj->fk_idproducto = $fila["fk_idproducto"];
                $obj->nombre_producto = $fila["nombre_producto"];
                $aVentas[] = $obj;
            }
        }
        return $aVentas;
    }


    public function obtenerFacturacionMensual($mes)
    {

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE);
        $sql = "SELECT 
            SUM(total) AS total
            FROM ventas 
            WHERE MONTH(fecha) = $mes";


        if (!$resultado = $mysqli->query($sql)) {
            printf("error en query:%s\n", $mysqli->error . " " . $sql);
        }
        $fila = $resultado->fetch_assoc();
        $mysqli->close();
        return $fila["total"];
    }
    public function obtenerFacturacionAnual($anio)
    {

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, config::BBDD_NOMBRE);
        $sql = "SELECT 
            SUM(total) AS total
            FROM ventas 
            WHERE YEAR(fecha) = $anio";


        if (!$resultado = $mysqli->query($sql)) {
            printf("error en query:%s\n", $mysqli->error . " " . $sql);
        }
        $fila = $resultado->fetch_assoc();
        $mysqli->close();
        return $fila["total"];
    }
}

?>